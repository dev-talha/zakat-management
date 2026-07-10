<?php

namespace App\Services;

use App\Models\BlockchainAnchor;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use kornrunner\Ethereum\Transaction;
use kornrunner\Keccak;

/**
 * Anchors Zakat transaction records to the Ethereum network (Sepolia testnet).
 *
 * Each "anchor" is a 0-value self-transaction from the configured account whose
 * `data` field carries the keccak256 hash of the canonical record. This gives a
 * public, tamper-proof, timestamped proof-of-existence on-chain (verifiable on
 * Etherscan) without putting any PII or funds on the chain.
 *
 * Config precedence: admin Settings (group "blockchain") → config/blockchain.php (.env).
 * Reads work with just the public account; sending needs the private key + gas.
 */
class BlockchainService
{
    private bool $enabled;
    private string $rpc;
    private int $chainId;
    private string $account;
    private string $privateKey;
    private int $gasLimit;
    private string $explorer;

    public function __construct()
    {
        $this->enabled    = (bool) filter_var($this->cfg('enabled', config('blockchain.enabled')), FILTER_VALIDATE_BOOLEAN);
        $this->rpc        = (string) $this->cfg('rpc_url', config('blockchain.rpc_url'));
        $this->chainId    = (int) $this->cfg('chain_id', config('blockchain.chain_id'));
        $this->account    = (string) $this->cfg('account', config('blockchain.account'));
        $this->privateKey = $this->strip0x((string) $this->cfg('private_key', config('blockchain.private_key')));
        $this->gasLimit   = (int) $this->cfg('gas_limit', config('blockchain.gas_limit'));
        $this->explorer   = rtrim((string) $this->cfg('explorer', config('blockchain.explorer')), '/');
    }

    /** Read a setting from the DB (admin UI), falling back to config/.env. */
    private function cfg(string $key, $default)
    {
        try {
            $val = Setting::getValue('blockchain', $key, null);
        } catch (\Throwable $e) {
            $val = null; // settings table not migrated yet
        }
        return ($val === null || $val === '') ? $default : $val;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function canSend(): bool
    {
        return $this->privateKey !== '';
    }

    public function account(): string
    {
        return $this->account;
    }

    public function network(): string
    {
        return (string) $this->cfg('network', config('blockchain.network'));
    }

    public function explorerTx(string $txHash): string
    {
        return $this->explorer . '/tx/' . $txHash;
    }

    public function explorerAddress(?string $addr = null): string
    {
        return $this->explorer . '/address/' . ($addr ?? $this->account);
    }

    // ── Reads ────────────────────────────────────────────────────────────────

    public function blockNumber(): int
    {
        return (int) hexdec($this->rpc('eth_blockNumber'));
    }

    public function networkChainId(): int
    {
        return (int) hexdec($this->rpc('eth_chainId'));
    }

    public function nonce(?string $addr = null, string $block = 'pending'): int
    {
        return (int) hexdec($this->rpc('eth_getTransactionCount', [$addr ?? $this->account, $block]));
    }

    public function gasPriceHex(): string
    {
        return $this->rpc('eth_gasPrice');
    }

    public function balanceWei(?string $addr = null): string
    {
        return $this->hexToDec($this->rpc('eth_getBalance', [$addr ?? $this->account, 'latest']));
    }

    public function balanceEth(?string $addr = null): string
    {
        return $this->weiToEth($this->balanceWei($addr));
    }

    public function receipt(string $txHash): ?array
    {
        return $this->rpc('eth_getTransactionReceipt', [$txHash]);
    }

    /** Update a sent anchor's on-chain status from its receipt. */
    public function syncAnchor(BlockchainAnchor $anchor): void
    {
        if (! $anchor->tx_hash || in_array($anchor->status, ['confirmed', 'failed', 'skipped'], true)) {
            return;
        }
        try {
            $receipt = $this->receipt($anchor->tx_hash);
            if ($receipt) {
                $anchor->block_number = (int) hexdec($receipt['blockNumber'] ?? '0x0');
                $anchor->status       = (($receipt['status'] ?? '0x0') === '0x1') ? 'confirmed' : 'failed';
                $anchor->confirmed_at = now();
                $anchor->save();
            }
        } catch (\Throwable $e) {
            // leave as-is; will retry on next sync
        }
    }

    /** Live status bundle for the admin dashboard. */
    public function status(): array
    {
        try {
            return [
                'ok'          => true,
                'chain_id'    => $this->networkChainId(),
                'is_sepolia'  => $this->networkChainId() === 11155111,
                'block'       => $this->blockNumber(),
                'balance_eth' => $this->balanceEth(),
                'nonce'       => $this->nonce(),
                'account'     => $this->account,
                'contract'    => $this->contractAddress(),
                'can_send'    => $this->canSend(),
                'enabled'     => $this->enabled,
            ];
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    // ── Anchoring ──────────────────────────────────────────────────────────────

    public function hashPayload(array $payload): string
    {
        $canonical = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return '0x' . Keccak::hash($canonical, 256);
    }

    public function contractAddress(): ?string
    {
        $a = $this->cfg('contract_address', config('blockchain.contract_address'));
        return $a ? (string) $a : null;
    }

    /** Sign and broadcast a transaction. $toHexNo0x = '' for contract creation. */
    private function signAndSend(string $toHexNo0x, string $dataHexNo0x, int $gasLimit): string
    {
        if (! $this->canSend()) {
            throw new \RuntimeException('Private key is not configured.');
        }
        $nonce = $this->nonce();
        $tx = new Transaction(
            $nonce === 0 ? '' : dechex($nonce),
            $this->strip0x($this->gasPriceHex()),
            dechex($gasLimit),
            $toHexNo0x,
            '',                                        // value = 0
            $dataHexNo0x
        );
        $raw = $tx->getRaw($this->privateKey, $this->chainId);
        return $this->rpc('eth_sendRawTransaction', ['0x' . $raw]);
    }

    /** Ask the node for a gas estimate (+25% buffer); 0 on failure. */
    public function estimateGas(array $call): int
    {
        try {
            $hex = $this->rpc('eth_estimateGas', [$call]);
            return (int) ceil(hexdec($hex) * 1.25);
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /** Legacy raw self-transaction carrying just the record hash in data. */
    public function sendAnchor(string $payloadHash): array
    {
        $txHash = $this->signAndSend($this->strip0x($this->account), $this->strip0x($payloadHash), $this->gasLimit);
        return ['tx_hash' => $txHash, 'explorer' => $this->explorerTx($txHash)];
    }

    /** Deploy the ZakatLedger contract; returns the creation tx hash. */
    public function deployContract(): array
    {
        $path = database_path('data/ZakatLedger.json');
        if (! is_file($path)) {
            throw new \RuntimeException('Contract artifact not found. Run: node contracts/compile.cjs');
        }
        $artifact = json_decode(file_get_contents($path), true);
        $bytecode = $this->strip0x($artifact['bytecode']);
        $gas      = $this->estimateGas(['from' => $this->account, 'data' => '0x' . $bytecode]) ?: 800000;
        $txHash   = $this->signAndSend('', $bytecode, $gas);
        return ['tx_hash' => $txHash, 'explorer' => $this->explorerTx($txHash)];
    }

    /**
     * Call ZakatLedger.recordTransaction(string,uint256,string,bytes32).
     * Etherscan can decode both the input data and the emitted event.
     */
    public function recordTransaction(string $transactionId, string $amountMinor, string $currency, string $recordHash): array
    {
        $contract = $this->contractAddress();
        if (! $contract) {
            throw new \RuntimeException('ZakatLedger contract address is not set. Deploy it first.');
        }
        $data   = $this->encodeRecordTransaction($transactionId, $amountMinor, $currency, $recordHash);
        $gas    = $this->estimateGas(['from' => $this->account, 'to' => $contract, 'data' => $data]) ?: 150000;
        $txHash = $this->signAndSend($this->strip0x($contract), $this->strip0x($data), $gas);
        return ['tx_hash' => $txHash, 'explorer' => $this->explorerTx($txHash), 'data' => $data];
    }

    /** ABI-encode recordTransaction(string,uint256,string,bytes32). */
    public function encodeRecordTransaction(string $txId, string $amountMinor, string $currency, string $recordHash): string
    {
        $selector = substr(Keccak::hash('recordTransaction(string,uint256,string,bytes32)', 256), 0, 8);

        $txIdEnc = $this->abiString($txId);
        $curEnc  = $this->abiString($currency);

        $head0 = 4 * 32;                                   // offset of first dynamic arg (txId)
        $head2 = $head0 + intdiv(strlen($txIdEnc), 2);     // offset of second dynamic arg (currency)

        $data = $selector
            . $this->abiUint((string) $head0)              // slot0: offset → txId
            . $this->abiUint($amountMinor)                 // slot1: amount (uint256)
            . $this->abiUint((string) $head2)              // slot2: offset → currency
            . str_pad($this->strip0x($recordHash), 64, '0', STR_PAD_RIGHT) // slot3: bytes32
            . $txIdEnc                                      // tail: txId
            . $curEnc;                                      // tail: currency

        return '0x' . $data;
    }

    private function abiUint(string $dec): string
    {
        return str_pad(gmp_strval(gmp_init($dec, 10), 16), 64, '0', STR_PAD_LEFT);
    }

    private function abiString(string $s): string
    {
        $hex    = bin2hex($s);
        $lenHex = str_pad(dechex(strlen($s)), 64, '0', STR_PAD_LEFT);
        if ($hex === '') {
            return $lenHex;
        }
        $padded = str_pad($hex, (int) (ceil(strlen($hex) / 64) * 64), '0', STR_PAD_RIGHT);
        return $lenHex . $padded;
    }

    /**
     * Record + (if enabled) broadcast an anchor for a domain model.
     * When a contract is deployed, business fields (amount, currency, id, hash)
     * are written via recordTransaction() so Etherscan can decode them.
     *
     * @param float|null $amount Major-unit amount (e.g. 5000.50 BDT).
     */
    public function anchorModel(Model $model, string $reference, array $payload, ?float $amount = null, string $currency = 'BDT'): BlockchainAnchor
    {
        $hash        = $this->hashPayload($payload);
        $amountMinor = $amount === null ? null : (int) round($amount * 100); // → smallest unit (paisa)
        $contract    = $this->contractAddress();

        $anchor = new BlockchainAnchor([
            'reference'        => $reference,
            'amount_minor'     => $amountMinor,
            'currency'         => $currency,
            'payload_hash'     => $hash,
            'payload'          => $payload,
            'network'          => $this->network(),
            'from_address'     => $this->account,
            'contract_address' => $contract,
            'method'           => $contract ? 'recordTransaction' : 'anchor',
            'status'           => 'pending',
        ]);
        $anchor->anchorable()->associate($model);

        if (! $this->isEnabled()) {
            $anchor->status = 'skipped';
            $anchor->error  = 'Blockchain disabled';
            $anchor->save();
            return $anchor;
        }

        try {
            $result = $contract
                ? $this->recordTransaction($reference, (string) ($amountMinor ?? 0), $currency, $hash)
                : $this->sendAnchor($hash);
            $anchor->fill(['tx_hash' => $result['tx_hash'], 'status' => 'sent', 'explorer_url' => $result['explorer']]);
        } catch (\Throwable $e) {
            $anchor->status = 'failed';
            $anchor->error  = $e->getMessage();
        }
        $anchor->save();

        return $anchor;
    }

    // ── Internals ────────────────────────────────────────────────────────────

    private function rpc(string $method, array $params = [])
    {
        $response = Http::timeout(25)->acceptJson()->post($this->rpc, [
            'jsonrpc' => '2.0', 'method' => $method, 'params' => $params, 'id' => 1,
        ]);

        $json = $response->json();
        if (isset($json['error'])) {
            throw new \RuntimeException($method . ' failed: ' . ($json['error']['message'] ?? 'RPC error'));
        }
        return $json['result'] ?? null;
    }

    /** Strip a leading 0x/0X prefix without touching legitimate leading zeros. */
    private function strip0x(string $hex): string
    {
        return preg_replace('/^0x/i', '', trim($hex)) ?? $hex;
    }

    /** Precise hex→decimal (bcmath) for wei values that overflow PHP ints. */
    private function hexToDec(string $hex): string
    {
        $hex = ltrim($this->strip0x($hex), '0') ?: '0';
        $dec = '0';
        foreach (str_split($hex) as $char) {
            $dec = bcadd(bcmul($dec, '16'), (string) hexdec($char));
        }
        return $dec;
    }

    private function weiToEth(string $wei): string
    {
        return rtrim(rtrim(bcdiv($wei, bcpow('10', '18'), 6), '0'), '.') ?: '0';
    }
}
