<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\User;
use Illuminate\Http\Request;

class AdminVolunteerController extends Controller
{
    /**
     * Display a listing of the volunteers.
     */
    public function index(Request $request)
    {
        $query = Volunteer::with('organization');

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('name_en', 'like', '%' . $request->search . '%')
                  ->orWhere('name_bn', 'like', '%' . $request->search . '%')
                  ->orWhere('volunteer_code', 'like', '%' . $request->search . '%')
                  ->orWhere('mobile', 'like', '%' . $request->search . '%');
            });
        }

        $volunteers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.volunteers.index', compact('volunteers'));
    }

    /**
     * Update the status of the volunteer (Approve, Reject, Suspend)
     */
    public function updateStatus(Request $request, Volunteer $volunteer)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended,pending'
        ]);

        $volunteer->status = $request->status;
        
        // Generate volunteer code if active and doesn't exist
        if ($volunteer->status === 'active' && empty($volunteer->volunteer_code)) {
            $volunteer->volunteer_code = 'VOL-' . str_pad($volunteer->id, 5, '0', STR_PAD_LEFT);
        }

        $volunteer->save();

        // Sync linked user account status
        $user = User::find($volunteer->user_id);
        if ($user) {
            $user->status = $volunteer->status;
            $user->save();
        }

        return redirect()->back()->with('success', 'Volunteer status updated to ' . ucfirst($request->status));
    }
    /**
     * Display a single volunteer and its activity logs.
     */
    public function show(Volunteer $volunteer)
    {
        $volunteer->load('organization', 'user');

        // Fetch activity logs for this volunteer
        $activities = \Spatie\Activitylog\Models\Activity::where('subject_type', Volunteer::class)
            ->where('subject_id', $volunteer->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Also get user activities if the volunteer has a user account
        $userActivities = collect();
        if ($volunteer->user_id) {
            $userActivities = \Spatie\Activitylog\Models\Activity::where('subject_type', User::class)
                ->where('subject_id', $volunteer->user_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $allActivities = $activities->concat($userActivities)->sortByDesc('created_at');

        return view('admin.volunteers.show', compact('volunteer', 'allActivities'));
    }
}
