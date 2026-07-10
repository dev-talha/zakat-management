<?php

namespace App\Http\Controllers;

use App\Models\CaseRecord;
use App\Models\CaseNote;
use App\Models\Volunteer;
use App\Models\ZakatVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

/**
 * Volunteer-facing initial verification. Every query is scoped to the
 * logged-in active volunteer's union (primary_area_id) so a volunteer can
 * neither see nor act on applications from any other union.
 */
class VolunteerVerificationController extends Controller
{
    /** Resolve the active volunteer for the current user, or 403. */
    private function volunteer(): Volunteer
    {
        $vol = Volunteer::where('user_id', Auth::id())->where('status', 'active')->first();
        abort_unless($vol, 403, 'Only active (verified) volunteers can access verifications.');
        abort_unless($vol->primary_area_id, 403, 'No coverage area is assigned to your volunteer profile.');
        return $vol;
    }

    /** Union isolation guard — the case must belong to the volunteer's union. */
    private function authorizeUnion(CaseRecord $case, Volunteer $vol): void
    {
        abort_unless(
            $case->beneficiary && (int) $case->beneficiary->geo_area_id === (int) $vol->primary_area_id,
            403,
            'This application belongs to another union.'
        );
    }

    public function index()
    {
        $vol = $this->volunteer();

        $cases = CaseRecord::with(['beneficiary.household', 'beneficiary.geoArea', 'assignedVolunteer.user'])
            ->whereHas('beneficiary', fn ($q) => $q->where('geo_area_id', $vol->primary_area_id))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('public.volunteer.verifications.index', compact('cases', 'vol'));
    }

    public function show(CaseRecord $case)
    {
        $vol = $this->volunteer();
        $this->authorizeUnion($case, $vol);

        // Claim: first union volunteer to open an unassigned case becomes the worker.
        if (! $case->assigned_volunteer_id) {
            $case->update(['assigned_volunteer_id' => $vol->id]);
        }

        $case->load(['beneficiary.household', 'beneficiary.geoArea', 'assignedVolunteer.user', 'notes.author']);
        $verification = ZakatVerification::where('case_id', $case->id)->latest()->first();
        $activities = $this->activityFor($case);

        return view('public.volunteer.verifications.show', compact('case', 'vol', 'verification', 'activities'));
    }

    public function submit(Request $request, CaseRecord $case)
    {
        $vol = $this->volunteer();
        $this->authorizeUnion($case, $vol);

        $data = $request->validate([
            'recommendation'     => 'required|in:approve,reject,reduce_amount,needs_more_info',
            'requested_amount'   => 'nullable|numeric|min:0',
            'recommended_amount' => 'nullable|numeric|min:0',
            'notes_bn'           => 'nullable|string|max:2000',
        ]);

        if ($request->filled('requested_amount')) {
            $case->requested_amount = $data['requested_amount'];
        }
        // A concrete recommendation moves the case to org-admin final review;
        // "needs more info" keeps it in the field-verification stage.
        $case->stage = in_array($data['recommendation'], ['approve', 'reject', 'reduce_amount'])
            ? 'supervisor_review'
            : 'field_verification';
        $case->assigned_volunteer_id = $vol->id;
        $case->save();

        $case->beneficiary->update(['status' => 'under_review']);

        ZakatVerification::updateOrCreate(
            ['case_id' => $case->id, 'volunteer_id' => $vol->id],
            [
                'beneficiary_id'   => $case->beneficiary_id,
                'verifier_id'      => Auth::id(),
                'verifier_type'    => 'volunteer',
                'organization_id'  => $vol->organization_id,
                'verified_area_id' => $case->beneficiary->geo_area_id,
                'is_within_authority' => true,
                'recommendation'   => $data['recommendation'],
                'recommended_amount' => $data['recommended_amount'] ?? null,
                'notes_bn'         => $data['notes_bn'] ?? null,
                'status'           => 'submitted',
                'visit_date'       => now(),
            ]
        );

        if (! empty($data['notes_bn'])) {
            CaseNote::create([
                'case_id' => $case->id, 'author_id' => Auth::id(),
                'note_type' => 'assessment', 'body' => $data['notes_bn'],
            ]);
        }

        $amountTxt = ($data['recommended_amount'] ?? null) ? ' (৳' . number_format((float) $data['recommended_amount']) . ')' : '';
        activity('verification')
            ->causedBy(Auth::user())
            ->performedOn($case)
            ->withProperties([
                'role' => 'volunteer',
                'recommendation' => $data['recommendation'],
                'recommended_amount' => $data['recommended_amount'] ?? null,
                'union' => optional($case->beneficiary->geoArea)->name_en,
            ])
            ->log('Volunteer ' . $vol->name_en . ' submitted initial verification: ' . strtoupper($data['recommendation']) . $amountTxt);

        return redirect()->route('volunteer.verifications.show', $case)
            ->with('success', 'Initial verification submitted. Awaiting organization admin final review.');
    }

    public function note(Request $request, CaseRecord $case)
    {
        $vol = $this->volunteer();
        $this->authorizeUnion($case, $vol);

        $data = $request->validate(['body' => 'required|string|max:2000']);
        CaseNote::create([
            'case_id' => $case->id, 'author_id' => Auth::id(),
            'note_type' => 'general', 'body' => $data['body'],
        ]);

        activity('verification')->causedBy(Auth::user())->performedOn($case)
            ->withProperties(['role' => 'volunteer'])
            ->log('Volunteer ' . $vol->name_en . ' added a note');

        return back()->with('success', 'Note added.');
    }

    private function activityFor(CaseRecord $case)
    {
        return Activity::where('subject_type', CaseRecord::class)
            ->where('subject_id', $case->id)
            ->with('causer')->latest()->get();
    }
}
