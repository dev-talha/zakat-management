<?php
namespace App\Http\Controllers;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = Campaign::latest()->paginate(20);
        return view('campaigns.index', compact('campaigns'));
    }
    public function create() { return view('campaigns.create'); }
    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'fund_type' => 'required', 'target_amount' => 'required|numeric|min:0']);
        Campaign::create(array_merge($request->only('name', 'name_bn', 'description', 'fund_type', 'target_amount', 'starts_at', 'ends_at'), [
            'slug' => Str::slug($request->name) . '-' . Str::random(5), 'status' => 'draft',
        ]));
        return redirect()->route('campaigns.index')->with('success', 'Campaign created.');
    }
    public function show(Campaign $campaign) { $campaign->load('collections'); return view('campaigns.show', compact('campaign')); }
    public function edit(Campaign $campaign) { return view('campaigns.edit', compact('campaign')); }
    public function update(Request $request, Campaign $campaign) { $campaign->update($request->all()); return back()->with('success', 'Updated.'); }
    public function destroy(Campaign $campaign) { $campaign->delete(); return redirect()->route('campaigns.index')->with('success', 'Deleted.'); }
}
