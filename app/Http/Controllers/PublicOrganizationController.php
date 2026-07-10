<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PublicOrganizationController extends Controller
{
    public function create()
    {
        return view('public.organization.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en'              => 'required|string|max:255',
            'contact_person_name'  => 'required|string|max:255',
            'contact_mobile'       => 'required|string|max:20',
            'contact_email'        => 'required|email',
            'division'             => 'required|string',
            'district'             => 'required|string',
            'email'                => 'required|email|unique:users,email',
            'admin_name'           => 'required|string|max:255',
            'password'             => 'required|string|min:8|confirmed',
        ]);

        // Create admin user for the organization
        $user = User::create([
            'name'      => $request->admin_name,
            'email'     => $request->email,
            'mobile'    => $request->contact_mobile,
            'password'  => Hash::make($request->password),
            'user_type' => 'org_admin',
            'status'    => 'active',
        ]);

        $user->assignRole('organization');

        // Create organization profile
        Organization::create([
            'org_code'             => Organization::generateCode(),
            'referral_code'        => Organization::generateReferralCode(),
            'name_en'              => $request->name_en,
            'name_bn'              => $request->name_bn,
            'type'                 => $request->org_type ?? 'ngo',
            'registration_no'      => $request->registration_no,
            'trade_license_no'     => $request->trade_license_no,
            'ngo_registration_no'  => $request->ngo_registration_no,
            'contact_person_name'  => $request->contact_person_name,
            'contact_mobile'       => $request->contact_mobile,
            'contact_email'        => $request->contact_email,
            'website'              => $request->website,
            'description_bn'       => $request->description,
            'division'             => $request->division,
            'district'             => $request->district,
            'upazila'              => $request->upazila,
            'union_name'           => $request->union,
            'address'              => $request->address,
            'status'               => 'pending',  // Requires admin verification
            'created_by'           => $user->id,
        ]);

        Auth::login($user);

        return redirect()->route('organization.dashboard')
            ->with('success', 'Your organization has been registered! Our team will review your application within 5–7 business days.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $org = Organization::with('volunteers')->where('created_by', $user->id)->first();
        
        $leaderboardRank = null;
        if ($org) {
            $leaderboardRank = Organization::where('total_collected_via_referral', '>', $org->total_collected_via_referral)->count() + 1;
        }

        return view('public.organization.dashboard', compact('user', 'org', 'leaderboardRank'));
    }
}
