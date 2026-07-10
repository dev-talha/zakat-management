<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Fund;
use App\Models\FundLedger;
use App\Models\Campaign;
use App\Models\Donor;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Collection::with('donor', 'campaign')->latest();
        if ($s = $request->get('search')) $query->where('receipt_no', 'like', "%{$s}%");
        if ($fund = $request->get('fund_type')) $query->where('fund_type', $fund);
        if ($status = $request->get('status')) $query->where('status', $status);
        $collections = $query->paginate(20);
        return view('collections.index', compact('collections'));
    }

    public function create()
    {
        $donors = Donor::with('user')->get();
        $campaigns = Campaign::where('status', 'active')->get();
        return view('collections.create', compact('donors', 'campaigns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fund_type' => 'required',
            'amount' => 'required|numeric|min:1',
            'source_channel' => 'required',
        ]);

        $collection = Collection::create(array_merge($request->only(
            'donor_id', 'campaign_id', 'fund_type', 'source_channel', 'amount',
            'donor_preference', 'is_anonymous', 'notes'
        ), [
            'receipt_no' => Collection::generateReceiptNo(),
            'currency' => 'BDT',
            'status' => $request->source_channel === 'cash' ? 'validated' : 'pending',
            'collected_by' => auth()->id(),
        ]));

        // Post to fund ledger for validated cash
        if ($collection->status === 'validated') {
            $fund = Fund::where('type', $collection->fund_type)->first();
            if ($fund) {
                FundLedger::create([
                    'fund_id' => $fund->id,
                    'entry_type' => 'collection',
                    'credit' => $collection->amount,
                    'ref_type' => 'collection',
                    'ref_id' => $collection->id,
                    'narration' => "Collection {$collection->receipt_no}",
                    'effective_at' => now(),
                    'created_by' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('collections.index')->with('success', "Collection {$collection->receipt_no} recorded.");
    }

    public function show(Collection $collection)
    {
        $collection->load('donor.user', 'campaign', 'payment');
        return view('collections.show', compact('collection'));
    }

    public function edit(Collection $collection) { return view('collections.edit', compact('collection')); }
    public function update(Request $request, Collection $collection)
    {
        $collection->update($request->only('status', 'notes'));
        return redirect()->route('collections.show', $collection)->with('success', 'Updated.');
    }
    public function destroy(Collection $collection)
    {
        $collection->delete();
        return redirect()->route('collections.index')->with('success', 'Deleted.');
    }
}
