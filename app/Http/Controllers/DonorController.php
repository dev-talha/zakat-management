<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $query = Donor::with('user')->latest();
        if ($s = $request->get('search')) {
            $query->where('display_name', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($q) => $q->where('email', 'like', "%{$s}%")->orWhere('mobile', 'like', "%{$s}%"));
        }
        if ($type = $request->get('type')) $query->where('donor_type', $type);
        if ($kyc = $request->get('kyc')) $query->where('kyc_status', $kyc);
        $donors = $query->paginate(20);
        return view('donors.index', compact('donors'));
    }

    public function create()
    {
        return view('donors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'nullable|string|max:20',
            'donor_type' => 'required|in:individual,corporate,mosque,branch,institutional',
            'display_name' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => bcrypt('password'),
            'user_type' => 'donor',
            'status' => 'active',
        ]);
        $user->assignRole('donor');

        Donor::create([
            'user_id' => $user->id,
            'donor_type' => $request->donor_type,
            'display_name' => $request->display_name,
            'legal_name' => $request->legal_name,
            'anonymous_default' => $request->boolean('anonymous_default'),
        ]);

        return redirect()->route('donors.index')->with('success', 'Donor created successfully.');
    }

    public function show(Donor $donor)
    {
        $donor->load('user', 'collections', 'zakatCalculations');
        return view('donors.show', compact('donor'));
    }

    public function edit(Donor $donor)
    {
        $donor->load('user');
        return view('donors.edit', compact('donor'));
    }

    public function update(Request $request, Donor $donor)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'donor_type' => 'required',
        ]);

        $donor->update($request->only('display_name', 'legal_name', 'donor_type', 'tax_id', 'anonymous_default'));
        if ($request->has('name')) $donor->user->update(['name' => $request->name, 'mobile' => $request->mobile]);

        return redirect()->route('donors.show', $donor)->with('success', 'Donor updated.');
    }

    public function destroy(Donor $donor)
    {
        $donor->delete();
        return redirect()->route('donors.index')->with('success', 'Donor archived.');
    }
}
