<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicDonorController extends Controller
{
    public function create()
    {
        $totalDonors = \App\Models\Donor::count();
        $zakatDistributed = \App\Models\Collection::where('payment_status', 'paid')->sum('amount') ?? 0;
        return view('public.donor.register', compact('totalDonors', 'zakatDistributed'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'mobile'         => 'required|string|max:20',
            'password'       => 'required|string|min:8|confirmed',
            'donor_type'     => 'required|in:individual,corporate',
        ]);

        // Anonymity is a preference (flag), not a donor type.
        $anonymous = (bool) $request->anonymous_default;

        // Create user + donor profile (+ address) atomically so a failure
        // never leaves an orphaned user without a donor record.
        $user = DB::transaction(function () use ($request, $anonymous) {
            $user = User::create([
                'name'      => $request->name,
                'name_bn'   => $request->name_bn,
                'email'     => $request->email,
                'mobile'    => $request->mobile,
                'password'  => Hash::make($request->password),
                'user_type' => 'donor',
                'status'    => 'active',
            ]);
            $user->assignRole('donor');

            $donor = Donor::create([
                'user_id'          => $user->id,
                'donor_type'       => $request->donor_type,
                'display_name'     => $request->name,
                'anonymous_default'=> $anonymous,
                'kyc_status'       => 'pending',
            ]);

            if ($request->filled('division') || $request->filled('district')) {
                \App\Models\DonorAddress::create([
                    'donor_id'     => $donor->id,
                    'division'     => $request->division,
                    'district'     => $request->district,
                    'upazila'      => $request->upazila,
                    'union_name'   => $request->union,
                    'address_line' => $request->address,
                ]);
            }

            return $user;
        });

        // Log in the donor
        Auth::login($user);

        return redirect()->route('donor.dashboard')
            ->with('success', 'Welcome! Your donor account has been created successfully. জাযাকাল্লাহু খাইরান!');
    }

    public function dashboard()
    {
        $user  = Auth::user();
        $donor = $user->donor;
        
        $collections = [];
        $totalDonated = 0;
        $donationCount = 0;
        
        if ($donor) {
            $collections = \App\Models\Collection::where('donor_id', $donor->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            $totalDonated = \App\Models\Collection::where('donor_id', $donor->id)->where('payment_status', 'paid')->sum('amount');
            $donationCount = \App\Models\Collection::where('donor_id', $donor->id)->where('payment_status', 'paid')->count();
        }

        return view('public.donor.dashboard', compact('user', 'donor', 'collections', 'totalDonated', 'donationCount'));
    }
}
