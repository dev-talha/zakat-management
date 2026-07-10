<?php
namespace App\Http\Controllers;
use App\Models\ZakatCalculation;
use App\Models\Setting;
use Illuminate\Http\Request;

class ZakatCalculatorController extends Controller
{
    public function index()
    {
        $nisabGold = Setting::getValue('zakat', 'nisab_gold_grams', 87.48);
        $nisabSilver = Setting::getValue('zakat', 'nisab_silver_grams', 612.36);
        $categories = Setting::getValue('zakat', 'zakat_categories', []);
        return view('calculator.index', compact('nisabGold', 'nisabSilver', 'categories'));
    }

    public function calculate(Request $request)
    {
        $request->validate(['cash' => 'nullable|numeric|min:0', 'bank' => 'nullable|numeric|min:0']);

        $assets = [
            'cash' => (float) $request->input('cash', 0),
            'bank' => (float) $request->input('bank', 0),
            'gold_value' => (float) $request->input('gold_value', 0),
            'silver_value' => (float) $request->input('silver_value', 0),
            'shares' => (float) $request->input('shares', 0),
            'trade_inventory' => (float) $request->input('trade_inventory', 0),
            'receivables' => (float) $request->input('receivables', 0),
            'other_assets' => (float) $request->input('other_assets', 0),
        ];

        $liabilities = [
            'debts' => (float) $request->input('debts', 0),
            'expenses_due' => (float) $request->input('expenses_due', 0),
        ];

        $totalAssets = array_sum($assets);
        $totalLiabilities = array_sum($liabilities);
        $netZakatable = max(0, $totalAssets - $totalLiabilities);

        // Get nisab value (simplified — in production fetch live gold/silver prices)
        $nisabBasis = $request->input('nisab_basis', 'silver');
        $nisabValue = $nisabBasis === 'gold'
            ? Setting::getValue('zakat', 'nisab_gold_grams', 87.48) * (float) $request->input('gold_price_per_gram', 8000)
            : Setting::getValue('zakat', 'nisab_silver_grams', 612.36) * (float) $request->input('silver_price_per_gram', 120);

        $isEligible = $netZakatable >= $nisabValue;
        $rate = Setting::getValue('zakat', 'default_rate', 0.025);
        $zakatDue = $isEligible ? round($netZakatable * $rate, 2) : 0;

        // Save calculation
        $calc = ZakatCalculation::create([
            'user_id' => auth()->id(),
            'donor_id' => auth()->user()->donor?->id,
            'rule_pack' => 'standard',
            'nisab_basis' => $nisabBasis,
            'nisab_value' => $nisabValue,
            'asset_snapshot_json' => $assets,
            'liability_snapshot_json' => $liabilities,
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'net_zakatable' => $netZakatable,
            'zakat_due' => $zakatDue,
            'zakat_rate' => $rate,
            'is_eligible' => $isEligible,
        ]);

        return back()->with('result', [
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'net_zakatable' => $netZakatable,
            'nisab_value' => $nisabValue,
            'is_eligible' => $isEligible,
            'zakat_due' => $zakatDue,
            'rate' => $rate * 100,
        ]);
    }
}
