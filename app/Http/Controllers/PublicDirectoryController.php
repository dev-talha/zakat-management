<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class PublicDirectoryController extends Controller
{
    /**
     * Display a listing of verified organizations.
     */
    public function organizations(Request $request)
    {
        // Get verified organizations
        $organizations = Organization::where('status', 'verified')
            ->orderBy('total_collected_via_referral', 'desc')
            ->paginate(12);

        return view('public.directory.organizations', compact('organizations'));
    }

    /**
     * Display a listing of verified volunteers.
     */
    public function volunteers(Request $request)
    {
        // Get active volunteers
        // In the database, the status enum for volunteers is usually 'active'
        $volunteers = Volunteer::whereIn('status', ['active', 'verified'])
            ->orderBy('total_collected_via_referral', 'desc')
            ->paginate(12);

        return view('public.directory.volunteers', compact('volunteers'));
    }
}
