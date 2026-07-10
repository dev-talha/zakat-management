@extends('layouts.public')

@section('title', 'Verify Application | আবেদন যাচাই')

@push('styles')
<style>
    .vf-hero { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); padding: 26px 0; color:#fff; }
    .vf-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:22px; margin-bottom:20px; box-shadow:0 2px 12px rgba(0,0,0,0.04); }
    .vf-card h5 { font-weight:700; color:#111827; margin-bottom:16px; padding-bottom:10px; border-bottom:2px solid #f3f4f6; font-size:1rem; }
    .kv { font-size:0.85rem; padding:6px 0; display:flex; justify-content:space-between; gap:12px; border-bottom:1px dashed #f3f4f6; }
    .kv .k { color:#9ca3af; } .kv .v { font-weight:600; color:#111827; text-align:right; }
    .act { display:flex; gap:10px; padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:0.82rem; }
    .act .dot { width:8px;height:8px;border-radius:50%;background:#f59e0b;margin-top:6px;flex-shrink:0; }
    .pub-form-label { font-weight:600; font-size:0.82rem; color:#374151; margin-bottom:4px; }
    .pub-form-control { width:100%; border:1.5px solid #e5e7eb; border-radius:9px; padding:9px 12px; font-size:0.9rem; }
</style>
@endpush

@section('content')
@php $b = $case->beneficiary; $done = in_array($case->stage, ['supervisor_review','approved','rejected','disbursement']); @endphp
<div class="vf-hero">
    <div class="container">
        <div style="font-size:0.8rem;opacity:0.85;"><a href="{{ route('volunteer.verifications.index') }}" style="color:#fff;text-decoration:none;"><i class="bi bi-arrow-left"></i> Back to list</a></div>
        <h2 style="font-size:1.25rem;font-weight:700;margin:6px 0 0;">{{ $b->primary_person_name }} <span style="opacity:0.7;font-size:0.85rem;">· {{ $case->case_no }}</span></h2>
        <div style="font-size:0.82rem;opacity:0.85;margin-top:3px;"><i class="bi bi-geo-alt-fill me-1"></i>{{ optional($b->geoArea)->name_en ?? '-' }} · Stage: <span class="text-capitalize">{{ str_replace('_',' ',$case->stage) }}</span></div>
    </div>
</div>

<div style="background:#f8faf9;padding:30px 0;min-height:60vh;">
    <div class="container">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

        <div class="row g-4">
            <div class="col-lg-7">
                {{-- Applicant --}}
                <div class="vf-card">
                    <h5><i class="bi bi-person-vcard me-2" style="color:#f59e0b;"></i>Applicant Details</h5>
                    <div class="kv"><span class="k">Name</span><span class="v">{{ $b->primary_person_name }}</span></div>
                    <div class="kv"><span class="k">Mobile</span><span class="v">{{ $b->mobile }}</span></div>
                    <div class="kv"><span class="k">Zakat Category</span><span class="v text-capitalize">{{ $b->zakat_category }}</span></div>
                    <div class="kv"><span class="k">Monthly Income</span><span class="v">৳{{ number_format($b->monthly_income ?? 0) }}</span></div>
                    <div class="kv"><span class="k">Union / Area</span><span class="v">{{ optional($b->geoArea)->name_en ?? '-' }}</span></div>
                    <div class="kv"><span class="k">Address</span><span class="v">{{ optional($b->household)->address ?? '-' }}</span></div>
                    <div class="kv"><span class="k">Requested Amount</span><span class="v">৳{{ number_format($case->requested_amount ?? 0) }}</span></div>
                    @if($case->assignedVolunteer)
                    <div class="kv"><span class="k">Working Volunteer</span><span class="v">{{ $case->assignedVolunteer->name_en }}</span></div>
                    @endif
                </div>

                {{-- Verification form --}}
                <div class="vf-card">
                    <h5><i class="bi bi-clipboard-check me-2" style="color:#059669;"></i>Initial Verification</h5>
                    @if($verification && $verification->status !== 'submitted')
                        <div class="alert alert-info mb-0">This application was finalized ({{ ucfirst($verification->status) }}). No further edits.</div>
                    @else
                    <form method="POST" action="{{ route('volunteer.verifications.submit', $case) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="pub-form-label">Recommendation *</label>
                            <select name="recommendation" class="pub-form-control" required>
                                <option value="approve" @selected(optional($verification)->recommendation==='approve')>✅ Approve</option>
                                <option value="reduce_amount" @selected(optional($verification)->recommendation==='reduce_amount')>➖ Approve with reduced amount</option>
                                <option value="needs_more_info" @selected(optional($verification)->recommendation==='needs_more_info')>❓ Needs more info</option>
                                <option value="reject" @selected(optional($verification)->recommendation==='reject')>❌ Reject</option>
                            </select>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Requested Amount (৳)</label>
                                <input type="number" name="requested_amount" class="pub-form-control" value="{{ old('requested_amount', $case->requested_amount) }}" min="0" step="1">
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Recommended Amount (৳)</label>
                                <input type="number" name="recommended_amount" class="pub-form-control" value="{{ old('recommended_amount', optional($verification)->recommended_amount) }}" min="0" step="1">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="pub-form-label">Notes / পর্যবেক্ষণ</label>
                            <textarea name="notes_bn" class="pub-form-control" rows="3" placeholder="Field observations, household condition, etc.">{{ old('notes_bn', optional($verification)->notes_bn) }}</textarea>
                        </div>
                        <button type="submit" class="btn" style="background:#059669;color:#fff;font-weight:600;border:none;">
                            <i class="bi bi-send-check"></i> Submit for Final Review
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                {{-- Activity log --}}
                <div class="vf-card">
                    <h5><i class="bi bi-clock-history me-2" style="color:#7c3aed;"></i>Activity Log</h5>
                    @forelse($activities as $a)
                    <div class="act">
                        <div class="dot"></div>
                        <div>
                            <div style="color:#111827;">{{ $a->description }}</div>
                            <div style="color:#9ca3af;font-size:0.72rem;margin-top:2px;">
                                {{ optional($a->causer)->name ?? 'System' }} · {{ $a->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <p style="color:#9ca3af;font-size:0.85rem;">No activity yet.</p>
                    @endforelse
                </div>

                {{-- Notes --}}
                <div class="vf-card">
                    <h5><i class="bi bi-sticky me-2" style="color:#f59e0b;"></i>Notes</h5>
                    @forelse($case->notes->sortByDesc('created_at') as $n)
                    <div style="padding:8px 0;border-bottom:1px solid #f3f4f6;font-size:0.83rem;">
                        <div style="color:#111827;">{{ $n->body }}</div>
                        <div style="color:#9ca3af;font-size:0.72rem;">{{ optional($n->author)->name }} · {{ $n->created_at->diffForHumans() }} · <span class="text-capitalize">{{ $n->note_type }}</span></div>
                    </div>
                    @empty
                    <p style="color:#9ca3af;font-size:0.85rem;">No notes yet.</p>
                    @endforelse

                    <form method="POST" action="{{ route('volunteer.verifications.note', $case) }}" class="mt-3">
                        @csrf
                        <textarea name="body" class="pub-form-control" rows="2" placeholder="Add a note…" required></textarea>
                        <button type="submit" class="btn btn-sm mt-2" style="background:#f59e0b;color:#fff;font-weight:600;border:none;">Add Note</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
