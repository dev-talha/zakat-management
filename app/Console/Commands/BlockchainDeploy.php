<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\BlockchainService;
use Illuminate\Console\Command;

class BlockchainDeploy extends Command
{
    protected $signature = 'blockchain:deploy';

    protected $description = 'Deploy the ZakatLedger smart contract to the configured network and save its address';

    public function handle(BlockchainService $chain): int
    {
        if (! $chain->canSend()) {
            $this->error('No private key configured (BLOCKCHAIN_PRIVATE_KEY / Settings).');
            return self::FAILURE;
        }

        $this->info('Deploying ZakatLedger contract…');
        try {
            $result = $chain->deployContract();
        } catch (\Throwable $e) {
            $this->error('Deploy failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->line('Deploy tx: ' . $result['tx_hash']);
        $this->line('Etherscan: ' . $result['explorer']);
        $this->line('Waiting for the contract address…');

        for ($i = 0; $i < 30; $i++) {
            sleep(3);
            $receipt = $chain->receipt($result['tx_hash']);
            if ($receipt && ! empty($receipt['contractAddress'])) {
                $address = $receipt['contractAddress'];
                Setting::setValue('blockchain', 'contract_address', $address);
                $this->newLine();
                $this->info('✓ Contract deployed at: ' . $address);
                $this->line('Saved to Settings (blockchain.contract_address).');
                $this->line('Address: ' . rtrim((string) config('blockchain.explorer'), '/') . '/address/' . $address);
                return self::SUCCESS;
            }
        }

        $this->warn('Not mined yet. Once confirmed, set the contract address in Settings → Blockchain.');
        return self::SUCCESS;
    }
}
