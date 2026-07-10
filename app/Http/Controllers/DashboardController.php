<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Beneficiary;
use App\Models\CaseRecord;
use App\Models\Fund;
use App\Models\Campaign;
use App\Models\Donor;
use App\Models\Distribution;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Redirect non-staff users to their role-specific dashboards
        if ($user->hasRole('donor') || $user->user_type === 'donor') {
            return redirect()->route('donor.dashboard');
        }
        if ($user->hasRole('volunteer') || $user->user_type === 'volunteer') {
            return redirect()->route('volunteer.dashboard');
        }
        if ($user->hasRole('organization') || $user->user_type === 'org_admin') {
            return redirect()->route('organization.dashboard');
        }
        if ($user->hasRole('beneficiary') || $user->user_type === 'beneficiary') {
            return redirect()->route('beneficiary.dashboard');
        }

        // Stats
        $totalCollections = Collection::where('status', 'validated')->sum('amount');
        $totalDistributions = Distribution::whereIn('status', ['disbursed', 'acknowledged'])->sum('approved_amount');
        $totalBeneficiaries = Beneficiary::count();
        $totalDonors = Donor::count();
        $pendingCases = CaseRecord::whereNotIn('stage', ['closed', 'rejected'])->count();
        $activeCampaigns = Campaign::where('status', 'active')->count();

        // Fund balances
        $funds = Fund::all()->map(function ($fund) {
            $fund->balance = $fund->balance;
            return $fund;
        });

        // Recent collections
        $recentCollections = Collection::with('donor')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent cases
        $recentCases = CaseRecord::with('beneficiary')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly collection data for chart (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyData[] = [
                'label' => $date->format('M Y'),
                'collections' => Collection::where('status', 'validated')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('amount'),
                'distributions' => Distribution::whereIn('status', ['disbursed', 'acknowledged'])
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('approved_amount'),
            ];
        }

        // Fund type breakdown
        $fundBreakdown = Collection::where('status', 'validated')
            ->selectRaw('fund_type, SUM(amount) as total')
            ->groupBy('fund_type')
            ->pluck('total', 'fund_type');

        return view('dashboard', compact(
            'totalCollections', 'totalDistributions', 'totalBeneficiaries',
            'totalDonors', 'pendingCases', 'activeCampaigns', 'funds',
            'recentCollections', 'recentCases', 'monthlyData', 'fundBreakdown'
        ));
    }
}
