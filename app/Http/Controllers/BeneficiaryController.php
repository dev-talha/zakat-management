<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\BeneficiaryHousehold;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Beneficiary::with('household')->latest();
        if ($s = $request->get('search')) {
            $query->where('primary_person_name', 'like', "%{$s}%")
                  ->orWhere('application_no', 'like', "%{$s}%")
                  ->orWhere('mobile', 'like', "%{$s}%");
        }
        if ($status = $request->get('status')) $query->where('status', $status);
        if ($cat = $request->get('category')) $query->where('zakat_category', $cat);
        $beneficiaries = $query->paginate(20);
        return view('beneficiaries.index', compact('beneficiaries'));
    }

    public function create() { return view('beneficiaries.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'primary_person_name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'district' => 'required|string',
            'zakat_category' => ['nullable', \Illuminate\Validation\Rule::in(Beneficiary::zakatCategoryKeys())],
        ]);

        $beneficiary = Beneficiary::create(array_merge(
            $request->only('primary_person_name', 'primary_person_name_bn', 'gender', 'dob',
                'identity_type', 'identity_no', 'mobile', 'monthly_income', 'zakat_category'),
            ['application_no' => Beneficiary::generateApplicationNo(), 'status' => 'pending']
        ));

        BeneficiaryHousehold::create(array_merge(
            $request->only('address', 'division', 'district', 'upazila', 'ward', 'housing_type'),
            ['beneficiary_id' => $beneficiary->id]
        ));

        return redirect()->route('beneficiaries.index')->with('success', 'Beneficiary application submitted.');
    }

    public function show(Beneficiary $beneficiary)
    {
        $beneficiary->load('household.members', 'documents', 'cases');
        return view('beneficiaries.show', compact('beneficiary'));
    }

    public function edit(Beneficiary $beneficiary)
    {
        $beneficiary->load('household');
        return view('beneficiaries.edit', compact('beneficiary'));
    }

    public function update(Request $request, Beneficiary $beneficiary)
    {
        $request->validate([
            'zakat_category' => ['nullable', \Illuminate\Validation\Rule::in(Beneficiary::zakatCategoryKeys())],
        ]);
        $beneficiary->update($request->only(
            'primary_person_name', 'gender', 'dob', 'identity_type', 'identity_no',
            'mobile', 'monthly_income', 'zakat_category', 'status', 'rejection_reason'
        ));
        if ($beneficiary->household) {
            $beneficiary->household->update($request->only('address', 'division', 'district', 'upazila', 'ward', 'housing_type'));
        }
        return redirect()->route('beneficiaries.show', $beneficiary)->with('success', 'Updated.');
    }

    public function destroy(Beneficiary $beneficiary)
    {
        $beneficiary->delete();
        return redirect()->route('beneficiaries.index')->with('success', 'Archived.');
    }
}
