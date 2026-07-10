<?php

namespace App\Console\Commands;

use App\Services\BlockchainService;
use Illuminate\Console\Command;

class BlockchainTest extends Command
{
    protected $signature = 'blockchain:test {--send : Also send a real anchor transaction (requires private key + gas)}';

    protected $description = 'Check Ethereum (Sepolia) connectivity and optionally send a test anchor transaction';

    public function handle(BlockchainService $chain): int
    {
        $this->info('── Blockchain configuration ──');
        $this->line('Network   : ' . config('blockchain.network'));
        $this->line('RPC URL   : ' . config('blockchain.rpc_url'));
        $this->line('Account   : ' . $chain->account());
        $this->line('Enabled   : ' . (config('blockchain.enabled') ? 'yes' : 'no'));
        $this->line('Can send  : ' . ($chain->canSend() ? 'yes (private key set)' : 'no (no private key)'));

        $this->newLine();
        $this->info('── Live network reads ──');
        try {
            $chainId = $chain->networkChainId();
            $this->line('Chain ID    : ' . $chainId . ($chainId === 11155111 ? ' (Sepolia ✓)' : ''));
            $this->line('Block #     : ' . number_format($chain->blockNumber()));
            $this->line('Balance     : ' . $chain->balanceEth() . ' ETH');
            $this->line('Nonce       : ' . $chain->nonce());
            $this->line('Explorer    : ' . $chain->explorerAddress());
        } catch (\Throwable $e) {
            $this->error('Read failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        if (! $this->option('send')) {
            $this->newLine();
            $this->comment('Read-only check OK. Re-run with --send to broadcast a test anchor.');
            return self::SUCCESS;
        }

        if (! $chain->canSend()) {
            $this->error('Cannot send: set BLOCKCHAIN_PRIVATE_KEY in .env (Sepolia test key) first.');
            return self::FAILURE;
        }

        $this->newLine();
        $this->info('── Sending test anchor transaction ──');

        $payload = [
            'type'      => 'test_anchor',
            'app'       => 'central-zakat-management',
            'reference' => 'TEST-' . now()->format('YmdHis'),
        ];
        $hash = $chain->hashPayload($payload);
        $this->line('Payload hash: ' . $hash);

        try {
            $result = $chain->sendAnchor($hash);
        } catch (\Throwable $e) {
            $this->error('Send failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info('Broadcasted! Tx hash: ' . $result['tx_hash']);
        $this->line('Etherscan   : ' . $result['explorer']);

        $this->newLine();
        $this->line('Waiting for confirmation (up to ~60s)…');
        for ($i = 0; $i < 20; $i++) {
            sleep(3);
            $receipt = $chain->receipt($result['tx_hash']);
            if ($receipt) {
                $block  = hexdec($receipt['blockNumber'] ?? '0x0');
                $status = ($receipt['status'] ?? '0x0') === '0x1' ? 'SUCCESS ✓' : 'FAILED';
                $this->info("Confirmed in block " . number_format($block) . " — status: {$status}");
                return self::SUCCESS;
            }
        }

        $this->comment('Not yet mined — check the Etherscan link above shortly.');
        return self::SUCCESS;
    }
}
