<?php

namespace App\Http\Controllers;

use App\Models\BlockchainAnchor;
use App\Services\BlockchainService;

class BlockchainLedgerController extends Controller
{
    /** Admin: full on-chain anchor ledger + live status. */
    public function index(BlockchainService $chain)
    {
        $anchors = BlockchainAnchor::with('anchorable')->latest()->paginate(25);

        $stats = [
            'total'     => BlockchainAnchor::count(),
            'confirmed' => BlockchainAnchor::where('status', 'confirmed')->count(),
            'sent'      => BlockchainAnchor::whereIn('status', ['sent', 'pending'])->count(),
            'failed'    => BlockchainAnchor::where('status', 'failed')->count(),
        ];

        $status = $chain->status();

        return view('blockchain.ledger', compact('anchors', 'stats', 'status'));
    }

    /** Admin: refresh on-chain confirmation status for sent anchors. */
    public function sync(BlockchainService $chain)
    {
        BlockchainAnchor::whereIn('status', ['sent', 'pending'])
            ->whereNotNull('tx_hash')
            ->limit(50)->get()
            ->each(fn (BlockchainAnchor $a) => $chain->syncAnchor($a));

        return back()->with('success', 'On-chain statuses synced from the network.');
    }

    /** Public: transparency ledger (verifiable on Etherscan). */
    public function transparency()
    {
        $anchors = BlockchainAnchor::whereIn('status', ['sent', 'confirmed'])
            ->whereNotNull('tx_hash')
            ->latest()->paginate(20);

        $explorer = rtrim((string) config('blockchain.explorer'), '/');
        $account  = config('blockchain.account');

        return view('public.transparency', compact('anchors', 'explorer', 'account'));
    }
}
