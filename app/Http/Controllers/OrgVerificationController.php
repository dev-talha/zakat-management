<?php

namespace App\Http\Controllers;

use App\Models\CaseNote;
use App\Models\CaseRecord;
use App\Models\Organization;
use App\Models\ZakatVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

/**
 * Organization-admin final verification. An org admin can only act on
 * verifications submitted by volunteers registered under their organization.
 * Approving here finalizes the application.
 */
class OrgVerificationController extends Controller
{
    /** Resolve the organization owned by the current user, or 403. */
    private function organization(): Organization
    {
        $org = Organization::where('created_by', Auth::id())->first();
        abort_unless($org, 403, 'No organization is linked to your account.');
        return $org;
    }

    private function authorizeOrg(ZakatVerification $verification, Organization $org): void
    {
        abort_unless((int) $verification->organization_id === (int) $org->id, 403,
            'This verification belongs to another organization.');
    }

    public function index()
    {
        $org = $this->organization();

        $pending = ZakatVerification::with(['beneficiary', 'volunteer.user', 'caseRecord', 'verifiedArea'])
            ->where('organization_id', $org->id)
            ->where('verifier_type', 'volunteer')
            ->where('status', 'submitted')
            ->orderByDesc('updated_at')
            ->get();

        $history = ZakatVerification::with(['beneficiary', 'volunteer.user', 'reviewer'])
            ->where('organization_id', $org->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->orderByDesc('reviewed_at')
            ->limit(30)->get();

        return view('public.organization.verifications.index', compact('pending', 'history', 'org'));
    }

    public function show(ZakatVerification $verification)
    {
        $org = $this->organization();
        $this->authorizeOrg($verification, $org);

        $verification->load(['beneficiary.household', 'beneficiary.geoArea', 'volunteer.user', 'caseRecord', 'reviewer']);
        $case = $verification->caseRecord;
        $activities = $case
            ? Activity::where('subject_type', CaseRecord::class)->where('subject_id', $case->id)->with('causer')->latest()->get()
            : collect();

        return view('public.organization.verifications.show', compact('verification', 'case', 'activities', 'org'));
    }

    public function finalize(Request $request, ZakatVerification $verification)
    {
        $org = $this->organization();
        $this->authorizeOrg($verification, $org);
        abort_unless($verification->status === 'submitted', 422, 'This verification has already been finalized.');

        $data = $request->validate([
            'decision'        => 'required|in:approve,reject',
            'approved_amount' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string|max:2000',
        ]);

        $case = $verification->caseRecord;

        if ($data['decision'] === 'approve') {
            $verification->update(['status' => 'approved', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
            $final = $data['approved_amount'] ?? $verification->recommended_amount ?? optional($case)->requested_amount;
            if ($case) {
                $case->update(['stage' => 'approved', 'approved_amount' => $final]);
                $case->beneficiary?->update(['status' => 'approved']);
            }
            $desc = 'Org-admin finalized: APPROVED' . ($final ? ' (৳' . number_format((float) $final) . ')' : '');
        } else {
            $verification->update(['status' => 'rejected', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
            if ($case) {
                $case->update(['stage' => 'rejected']);
                $case->beneficiary?->update(['status' => 'rejected', 'rejection_reason' => $data['notes'] ?? 'Rejected at final verification']);
            }
            $desc = 'Org-admin finalized: REJECTED';
        }

        if ($case && ! empty($data['notes'])) {
            CaseNote::create([
                'case_id' => $case->id, 'author_id' => Auth::id(),
                'note_type' => 'decision', 'body' => $data['notes'],
            ]);
        }

        if ($case) {
            activity('verification')->causedBy(Auth::user())->performedOn($case)
                ->withProperties(['role' => 'org_admin', 'decision' => $data['decision'], 'approved_amount' => $data['approved_amount'] ?? null])
                ->log($desc);
        }

        return redirect()->route('organization.verifications.index')
            ->with('success', 'Final verification recorded: ' . strtoupper($data['decision']) . '.');
    }
}
