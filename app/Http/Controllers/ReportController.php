<?php
namespace App\Http\Controllers;
use App\Models\Collection;
use App\Models\Distribution;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() { return view('reports.index'); }
    public function collections(Request $request)
    {
        $query = Collection::where('status', 'validated');
        if ($request->from) $query->where('created_at', '>=', $request->from);
        if ($request->to) $query->where('created_at', '<=', $request->to);
        $data = $query->selectRaw('fund_type, COUNT(*) as count, SUM(amount) as total')->groupBy('fund_type')->get();
        return view('reports.collections', compact('data'));
    }
    public function distributions(Request $request)
    {
        $data = Distribution::whereIn('status', ['disbursed', 'acknowledged'])
            ->selectRaw('distribution_type, COUNT(*) as count, SUM(approved_amount) as total')->groupBy('distribution_type')->get();
        return view('reports.distributions', compact('data'));
    }
    public function export($type) { return back()->with('info', 'Export feature coming soon.'); }
}
