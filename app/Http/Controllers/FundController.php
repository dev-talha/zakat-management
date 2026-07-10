<?php
namespace App\Http\Controllers;
use App\Models\Fund;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function index()
    {
        $funds = Fund::all()->map(fn($f) => tap($f, fn($f) => $f->computed_balance = $f->balance));
        $totalBalance = $funds->sum('computed_balance');
        return view('funds.index', compact('funds', 'totalBalance'));
    }
    public function show(Fund $fund)
    {
        $ledgers = $fund->ledgers()->latest()->paginate(50);
        return view('funds.show', compact('fund', 'ledgers'));
    }
}
