<?php

namespace App\Http\Controllers;

use App\Models\CaseRecord;
use App\Models\Beneficiary;
use App\Models\Agent;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $query = CaseRecord::with('beneficiary', 'agent.user')->latest();
        if ($s = $request->get('search')) $query->where('case_no', 'like', "%{$s}%");
        if ($stage = $request->get('stage')) $query->where('stage', $stage);
        if ($priority = $request->get('priority')) $query->where('priority', $priority);
        $cases = $query->paginate(20);
        return view('cases.index', compact('cases'));
    }

    public function create()
    {
        $beneficiaries = Beneficiary::where('status', '!=', 'blacklisted')->get();
        $agents = Agent::with('user')->where('onboarding_status', 'active')->get();
        return view('cases.create', compact('beneficiaries', 'agents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'case_type' => 'required',
            'priority' => 'required',
        ]);

        $case = CaseRecord::create(array_merge(
            $request->only('beneficiary_id', 'case_type', 'priority', 'description', 'requested_amount', 'assigned_agent_id'),
            ['case_no' => CaseRecord::generateCaseNo(), 'stage' => 'assessment', 'source' => 'admin']
        ));

        return redirect()->route('cases.show', $case)->with('success', "Case {$case->case_no} created.");
    }

    public function show(CaseRecord $case)
    {
        $case->load('beneficiary.household', 'agent.user', 'notes.author', 'visits.agent', 'distributions');
        return view('cases.show', compact('case'));
    }

    public function edit(CaseRecord $case) { return view('cases.edit', compact('case')); }

    public function update(Request $request, CaseRecord $case)
    {
        $case->update($request->only('case_type', 'priority', 'description', 'assigned_agent_id', 'approved_amount'));
        return redirect()->route('cases.show', $case)->with('success', 'Updated.');
    }

    public function advanceStage(Request $request, CaseRecord $case)
    {
        $stages = ['assessment', 'field_verification', 'supervisor_review', 'shariah_review', 'finance_review', 'approved', 'disbursement', 'follow_up', 'closed'];
        $current = array_search($case->stage, $stages);
        if ($current !== false && $current < count($stages) - 1) {
            $case->update(['stage' => $stages[$current + 1]]);
        }
        return redirect()->route('cases.show', $case)->with('success', "Advanced to {$case->stage}.");
    }

    public function destroy(CaseRecord $case)
    {
        $case->delete();
        return redirect()->route('cases.index')->with('success', 'Case archived.');
    }
}
