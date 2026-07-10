<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class PublicReferralController extends Controller
{
    /**
     * Handle organization referral link: /r/{code}
     * Stores the referral in session and redirects to payment page.
     */
    public function orgLink(string $code)
    {
        $org = Organization::where('referral_code', $code)->first();

        if (!$org) {
            return redirect('/pay')->with('info', 'Referral link not found. You can still pay Zakat directly.');
        }

        session(['referral_code' => $code, 'referral_type' => 'org', 'referral_name' => $org->name_en]);

        return redirect('/pay?ref=' . $code . '&rtype=org');
    }

    /**
     * Handle volunteer referral link: /v/{code}
     * Stores the referral in session and redirects to payment page.
     */
    public function volunteerLink(string $code)
    {
        $vol = Volunteer::with('user')->where('referral_code', $code)->first();

        if (!$vol) {
            return redirect('/pay')->with('info', 'Referral link not found. You can still pay Zakat directly.');
        }

        session(['referral_code' => $code, 'referral_type' => 'volunteer', 'referral_name' => $vol->name_en]);

        return redirect('/pay?ref=' . $code . '&rtype=volunteer');
    }

    /**
     * Public leaderboard page — top orgs and volunteers by Zakat collection via referral.
     */
    public function leaderboard()
    {
        $topOrgs = Organization::where('total_collected_via_referral', '>', 0)
            ->orderByDesc('total_collected_via_referral')
            ->take(10)
            ->get();

        $topVolunteers = Volunteer::with('organization', 'user')
            ->where('total_collected_via_referral', '>', 0)
            ->orderByDesc('total_collected_via_referral')
            ->take(10)
            ->get();

        // Platform totals
        $totalCollected  = \App\Models\Collection::where('payment_status', 'paid')->sum('amount');
        $totalDonors     = \App\Models\Collection::where('payment_status', 'paid')->distinct('donor_id')->count('donor_id');
        $totalOrgs       = Organization::whereIn('status', ['active', 'verified'])->count();
        $totalVolunteers = Volunteer::where('status', 'active')->count();

        return view('public.leaderboard', compact(
            'topOrgs', 'topVolunteers', 'totalCollected', 'totalDonors', 'totalOrgs', 'totalVolunteers'
        ));
    }
}
