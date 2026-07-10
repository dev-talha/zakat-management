<?php
namespace App\Http\Controllers;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index() { $branches = Branch::withCount('users', 'mosques')->paginate(20); return view('branches.index', compact('branches')); }
    public function create() { return view('branches.create'); }
    public function store(Request $request) { $request->validate(['code' => 'required|unique:branches', 'name' => 'required']); Branch::create($request->all()); return redirect()->route('branches.index')->with('success', 'Branch created.'); }
    public function show(Branch $branch) { return view('branches.show', compact('branch')); }
    public function edit(Branch $branch) { return view('branches.edit', compact('branch')); }
    public function update(Request $request, Branch $branch) { $branch->update($request->all()); return back()->with('success', 'Updated.'); }
    public function destroy(Branch $branch) { $branch->delete(); return redirect()->route('branches.index')->with('success', 'Deleted.'); }
}
