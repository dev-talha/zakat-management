<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use App\Models\BlockchainAnchor;
use App\Services\BlockchainService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(BlockchainService $chain)
    {
        $settings = Setting::all()->groupBy('group_code');
        $blockchainStatus = $chain->status();
        return view('settings.index', compact('settings', 'blockchainStatus'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            $parts = explode('__', $key, 2);
            if (count($parts) !== 2) continue;

            // Never overwrite the stored private key with a blank submission
            // (the field is rendered masked/empty for security).
            if ($parts[0] === 'blockchain' && $parts[1] === 'private_key' && trim((string) $value) === '') {
                continue;
            }

            Setting::setValue($parts[0], $parts[1], $value);
        }
        return back()->with('success', 'Settings updated.');
    }

    /** Broadcast a real test anchor transaction from the admin panel. */
    public function blockchainTestSend(BlockchainService $chain)
    {
        if (! $chain->canSend()) {
            return back()->with('bc_error', 'Private key is not set. Enter it above, Save, then try again.');
        }

        try {
            $reference = 'ADMIN-TEST-' . now()->format('YmdHis');
            $payload = [
                'type'      => 'admin_test',
                'reference' => $reference,
                'amount'    => '100.50',
                'currency'  => 'BDT',
                'at'        => now()->toIso8601String(),
                'by'        => auth()->id(),
            ];
            $hash     = $chain->hashPayload($payload);
            $contract = $chain->contractAddress();

            // Use the smart contract (decodable) when deployed; else raw anchor.
            $result = $contract
                ? $chain->recordTransaction($reference, '10050', 'BDT', $hash) // 100.50 BDT → 10050 paisa
                : $chain->sendAnchor($hash);

            BlockchainAnchor::create([
                'reference'        => $reference,
                'amount_minor'     => 10050,
                'currency'         => 'BDT',
                'payload_hash'     => $hash,
                'payload'          => $payload,
                'network'          => $chain->network(),
                'from_address'     => $chain->account(),
                'contract_address' => $contract,
                'method'           => $contract ? 'recordTransaction' : 'anchor',
                'tx_hash'          => $result['tx_hash'],
                'status'           => 'sent',
                'explorer_url'     => $result['explorer'],
            ]);

            return back()
                ->with('bc_success', 'Test transaction broadcast to the network!')
                ->with('bc_tx', $result['tx_hash'])
                ->with('bc_explorer', $result['explorer']);
        } catch (\Throwable $e) {
            return back()->with('bc_error', 'Broadcast failed: ' . $e->getMessage());
        }
    }

    /** Deploy the ZakatLedger smart contract and store its address. */
    public function blockchainDeploy(BlockchainService $chain)
    {
        if (! $chain->canSend()) {
            return back()->with('bc_error', 'Set the private key first, then deploy.');
        }
        try {
            $result = $chain->deployContract();
            // Poll briefly for the contract address.
            for ($i = 0; $i < 12; $i++) {
                sleep(3);
                $receipt = $chain->receipt($result['tx_hash']);
                if ($receipt && ! empty($receipt['contractAddress'])) {
                    Setting::setValue('blockchain', 'contract_address', $receipt['contractAddress']);
                    return back()
                        ->with('bc_success', 'ZakatLedger contract deployed at ' . $receipt['contractAddress'])
                        ->with('bc_tx', $result['tx_hash'])
                        ->with('bc_explorer', $result['explorer']);
                }
            }
            return back()->with('bc_error', 'Deploy tx sent (' . $result['tx_hash'] . ') but not mined yet — set the address once confirmed.');
        } catch (\Throwable $e) {
            return back()->with('bc_error', 'Deploy failed: ' . $e->getMessage());
        }
    }
}
