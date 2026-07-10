<?php

namespace App\Http\Controllers;

use App\Models\CaseRecord;
use App\Models\FollowUp;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function index()
    {
        $followUps = FollowUp::with(['caseRecord.beneficiary', 'agent'])->latest()->paginate(20);
        return view('followups.index', compact('followUps'));
    }

    public function create(Request $request)
    {
        $caseId = $request->query('case_id');
        $case = CaseRecord::findOrFail($caseId);
        return view('followups.create', compact('case'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'case_id' => 'required|exists:cases,id',
            'follow_up_date' => 'required|date',
            'notes' => 'required|string',
            'impact_rating' => 'required|integer|min:1|max:5',
            'funds_utilized_properly' => 'required|boolean',
        ]);

        FollowUp::create([
            'case_id' => $request->case_id,
            'agent_id' => auth()->id(),
            'follow_up_date' => $request->follow_up_date,
            'notes' => $request->notes,
            'impact_rating' => $request->impact_rating,
            'funds_utilized_properly' => $request->funds_utilized_properly,
            'next_follow_up_date' => $request->next_follow_up_date,
        ]);

        return redirect()->route('cases.show', $request->case_id)->with('success', 'Follow-up report submitted successfully.');
    }
}
