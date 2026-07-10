<?php
namespace App\Http\Controllers;
use App\Models\Distribution;
use App\Models\Fund;
use App\Models\Beneficiary;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function index(Request $request)
    {
        $distributions = Distribution::with('beneficiary', 'fund')->latest()->paginate(20);
        return view('distributions.index', compact('distributions'));
    }
    public function create()
    {
        $beneficiaries = Beneficiary::whereIn('status', ['approved', 'verified'])->get();
        $funds = Fund::where('status', 'active')->get();
        return view('distributions.create', compact('beneficiaries', 'funds'));
    }
    public function store(Request $request)
    {
        $request->validate(['beneficiary_id' => 'required', 'fund_id' => 'required', 'approved_amount' => 'required|numeric|min:1']);
        Distribution::create($request->only('beneficiary_id', 'case_id', 'fund_id', 'category_code', 'approved_amount', 'distribution_type'));
        return redirect()->route('distributions.index')->with('success', 'Distribution created.');
    }
    public function show(Distribution $distribution) { $distribution->load('beneficiary', 'fund', 'caseRecord'); return view('distributions.show', compact('distribution')); }
    public function edit(Distribution $distribution) { return view('distributions.edit', compact('distribution')); }
    public function update(Request $request, Distribution $distribution) { $distribution->update($request->only('status', 'approved_amount')); return back()->with('success', 'Updated.'); }
    public function destroy(Distribution $distribution) { $distribution->delete(); return redirect()->route('distributions.index')->with('success', 'Deleted.'); }
}
