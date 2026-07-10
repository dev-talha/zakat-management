<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PublicVolunteerController extends Controller
{
    public function create()
    {
        // Load active/approved organizations for selection
        $organizations = Organization::whereIn('status', ['active', 'verified'])
            ->select('id', 'name_en', 'name_bn', 'type', 'district', 'org_code')
            ->orderBy('name_en')
            ->get();

        return view('public.volunteer.register', compact('organizations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'mobile'          => 'required|string|max:20',
            'nid_no'          => 'required|string|max:30',
            'organization_id' => 'required|exists:organizations,id',
            'division'        => 'required|string',
            'district'        => 'required|string',
            'upazila'         => 'nullable|string',
            'union'           => 'nullable|string',
            'district_id'     => 'required|exists:geographic_areas,id',
            'upazila_id'      => 'nullable|exists:geographic_areas,id',
            'union_id'        => 'nullable|exists:geographic_areas,id',
            'password'        => 'required|string|min:8|confirmed',
        ]);

        // Most specific selected area becomes the volunteer's primary area.
        $primaryAreaId = $request->union_id ?: $request->upazila_id ?: $request->district_id;

        // Create user account
        $user = User::create([
            'name'      => $request->name,
            'name_bn'   => $request->name_bn,
            'email'     => $request->email,
            'mobile'    => $request->mobile,
            'password'  => Hash::make($request->password),
            'user_type' => 'volunteer',
            'status'    => 'active',
        ]);

        $user->assignRole('volunteer');

        // Create volunteer profile with organization link and referral code
        Volunteer::create([
            'volunteer_code'   => Volunteer::generateCode(),
            'referral_code'    => Volunteer::generateReferralCode(),
            'user_id'          => $user->id,
            'organization_id'  => $request->organization_id,
            'nid_no'           => $request->nid_no,
            'name_en'          => $request->name,
            'name_bn'          => $request->name_bn ?? $request->name,
            'mobile'           => $request->mobile,
            'occupation'       => $request->occupation,
            'division'         => $request->division,
            'district'         => $request->district,
            'upazila'          => $request->upazila,
            'union_name'       => $request->union,
            'primary_area_id'  => $primaryAreaId,
            'address_bn'       => $request->address,
            'status'           => 'pending',   // Requires admin approval
            'coverage_level'   => $request->coverage_level ?? 'union',
        ]);

        Auth::login($user);

        return redirect()->route('volunteer.dashboard')
            ->with('success', 'Your volunteer application has been submitted! We will review it within 3 business days. আপনার স্বেচ্ছাসেবী আবেদন জমা হয়েছে!');
    }

    public function dashboard()
    {
        $user      = Auth::user();
        $volunteer = Volunteer::with('organization')->where('user_id', $user->id)->first();

        // Monthly collection stats via referral
        $referralStats = null;
        if ($volunteer && $volunteer->referral_code) {
            $referralStats = \App\Models\Collection::where('referral_code', $volunteer->referral_code)
                ->where('payment_status', 'paid')
                ->selectRaw('COUNT(*) as donor_count, SUM(amount) as total_amount')
                ->first();
        }

        // Leaderboard rank for this volunteer
        $leaderboardRank = null;
        if ($volunteer) {
            $leaderboardRank = Volunteer::where('total_collected_via_referral', '>', $volunteer->total_collected_via_referral)->count() + 1;
        }

        return view('public.volunteer.dashboard', compact('user', 'volunteer', 'referralStats', 'leaderboardRank'));
    }
}
