<?php
namespace App\Http\Controllers;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index() { $complaints = Complaint::latest()->paginate(20); return view('complaints.index', compact('complaints')); }
    public function create() { return view('complaints.create'); }
    public function store(Request $request)
    {
        $request->validate(['description' => 'required']);
        $num = 'TKT-' . date('Y') . '-' . str_pad(Complaint::count() + 1, 6, '0', STR_PAD_LEFT);
        Complaint::create(array_merge($request->only('category', 'severity', 'description', 'complainant_name', 'complainant_contact'), ['ticket_no' => $num, 'channel' => 'web', 'sla_due_at' => now()->addDays(3)]));
        return redirect()->route('complaints.index')->with('success', 'Complaint submitted.');
    }
    public function show(Complaint $complaint) { return view('complaints.show', compact('complaint')); }
    public function edit(Complaint $complaint) { return view('complaints.edit', compact('complaint')); }
    public function update(Request $request, Complaint $complaint) { $complaint->update($request->only('status', 'resolution', 'assigned_to')); return back()->with('success', 'Updated.'); }
    public function destroy(Complaint $complaint) { $complaint->delete(); return redirect()->route('complaints.index')->with('success', 'Deleted.'); }
}
