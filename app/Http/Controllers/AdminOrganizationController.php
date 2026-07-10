<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class AdminOrganizationController extends Controller
{
    /**
     * Display a listing of the organizations.
     */
    public function index(Request $request)
    {
        $query = Organization::query();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('name_en', 'like', '%' . $request->search . '%')
                  ->orWhere('name_bn', 'like', '%' . $request->search . '%')
                  ->orWhere('org_code', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_mobile', 'like', '%' . $request->search . '%');
            });
        }

        $organizations = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.organizations.index', compact('organizations'));
    }

    /**
     * Display the specified organization.
     */
    public function show(Organization $organization)
    {
        $organization->load('volunteers');
        
        // Fetch activity logs for this organization
        $activities = \Spatie\Activitylog\Models\Activity::where('subject_type', Organization::class)
            ->where('subject_id', $organization->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Also get user activities if the organization has a linked user
        $userActivities = collect();
        if ($organization->created_by) {
            $userActivities = \Spatie\Activitylog\Models\Activity::where('subject_type', User::class)
                ->where('subject_id', $organization->created_by)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $allActivities = $activities->concat($userActivities)->sortByDesc('created_at');

        return view('admin.organizations.show', compact('organization', 'allActivities'));
    }

    /**
     * Update the status of the organization (Approve, Reject, Suspend)
     */
    public function updateStatus(Request $request, Organization $organization)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended,pending,verified'
        ]);

        $organization->status = $request->status;
        
        // When organization becomes active, generate org code if empty
        if (($organization->status === 'active' || $organization->status === 'verified') && empty($organization->org_code)) {
            $organization->org_code = 'ORG-' . str_pad($organization->id, 4, '0', STR_PAD_LEFT);
        }
        
        $organization->save();
        
        // Also update the linked user status to allow them to login or block them
        $user = User::find($organization->created_by);
        if ($user) {
            $user->status = ($request->status == 'verified' || $request->status == 'active') ? 'active' : $request->status;
            $user->save();
        }

        return redirect()->back()->with('success', 'Organization status updated to ' . ucfirst($request->status));
    }

    /**
     * Impersonate an organization account.
     */
    public function impersonate(Organization $organization)
    {
        if (!$organization->created_by) {
            return redirect()->back()->with('error', 'Organization does not have an associated user account to impersonate.');
        }

        $userToImpersonate = User::find($organization->created_by);
        
        if (!$userToImpersonate) {
            return redirect()->back()->with('error', 'User account not found.');
        }

        // Store current superadmin ID in session
        session()->put('impersonated_by', auth()->id());
        
        // Login as the target user
        auth()->login($userToImpersonate);

        return redirect()->route('organization.dashboard')->with('success', 'You are now impersonating ' . $organization->name_en);
    }
}
