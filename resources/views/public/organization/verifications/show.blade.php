@extends('layouts.public')

@section('title', 'Finalize Verification | চূড়ান্ত সিদ্ধান্ত')

@push('styles')
<style>
    .vf-hero { background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); padding: 26px 0; color:#fff; }
    .vf-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:22px; margin-bottom:20px; box-shadow:0 2px 12px rgba(0,0,0,0.04); }
    .vf-card h5 { font-weight:700; color:#111827; margin-bottom:16px; padding-bottom:10px; border-bottom:2px solid #f3f4f6; font-size:1rem; }
    .kv { font-size:0.85rem; padding:6px 0; display:flex; justify-content:space-between; gap:12px; border-bottom:1px dashed #f3f4f6; }
    .kv .k { color:#9ca3af; } .kv .v { font-weight:600; color:#111827; text-align:right; }
    .act { display:flex; gap:10px; padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:0.82rem; }
    .act .dot { width:8px;height:8px;border-radius:50%;background:#7c3aed;margin-top:6px;flex-shrink:0; }
    .pub-form-label { font-weight:600; font-size:0.82rem; color:#374151; margin-bottom:4px; }
    .pub-form-control { width:100%; border:1.5px solid #e5e7eb; border-radius:9px; padding:9px 12px; font-size:0.9rem; }
</style>
@endpush

@section('content')
@php $b = $verification->beneficiary; $final = $verification->status !== 'submitted'; @endphp
<div class="vf-hero">
    <div class="container">
        <div style="font-size:0.8rem;opacity:0.85;"><a href="{{ route('organization.verifications.index') }}" style="color:#fff;text-decoration:none;"><i class="bi bi-arrow-left"></i> Back to queue</a></div>
        <h2 style="font-size:1.25rem;font-weight:700;margin:6px 0 0;">{{ optional($b)->primary_person_name }} <span style="opacity:0.7;font-size:0.85rem;">· {{ optional($case)->case_no }}</span></h2>
        <div style="font-size:0.82rem;opacity:0.85;margin-top:3px;"><i class="bi bi-geo-alt-fill me-1"></i>{{ optional($verification->verifiedArea)->name_en ?? '-' }}</div>
    </div>
</div>

<div style="background:#f8faf9;padding:30px 0;min-height:60vh;">
    <div class="container">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="vf-card">
                    <h5><i class="bi bi-person-vcard me-2" style="color:#7c3aed;"></i>Applicant & Volunteer Recommendation</h5>
                    <div class="kv"><span class="k">Applicant</span><span class="v">{{ optional($b)->primary_person_name }}</span></div>
                    <div class="kv"><span class="k">Zakat Category</span><span class="v text-capitalize">{{ optional($b)->zakat_category }}</span></div>
                    <div class="kv"><span class="k">Monthly Income</span><span class="v">৳{{ number_format(optional($b)->monthly_income ?? 0) }}</span></div>
                    <div class="kv"><span class="k">Verified by Volunteer</span><span class="v">{{ optional($verification->volunteer)->name_en ?? '-' }}</span></div>
                    <div class="kv"><span class="k">Recommendation</span><span class="v text-capitalize">{{ str_replace('_',' ',$verification->recommendation) }}</span></div>
                    <div class="kv"><span class="k">Recommended Amount</span><span class="v">৳{{ number_format($verification->recommended_amount ?? 0) }}</span></div>
                    <div class="kv"><span class="k">Requested Amount</span><span class="v">৳{{ number_format(optional($case)->requested_amount ?? 0) }}</span></div>
                    @if($verification->notes_bn)
                    <div style="margin-top:10px;font-size:0.85rem;"><span style="color:#9ca3af;">Volunteer notes:</span><br>{{ $verification->notes_bn }}</div>
                    @endif
                </div>

                <div class="vf-card">
                    <h5><i class="bi bi-patch-check me-2" style="color:#059669;"></i>Final Decision</h5>
                    @if($final)
                        <div class="alert alert-{{ $verification->status==='approved' ? 'success' : 'danger' }} mb-0">
                            Finalized: <b>{{ strtoupper($verification->status) }}</b> by {{ optional($verification->reviewer)->name }}
                            @if(optional($case)->approved_amount) · Approved ৳{{ number_format($case->approved_amount) }} @endif
                        </div>
                    @else
                    <form method="POST" action="{{ route('organization.verifications.finalize', $verification) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="pub-form-label">Decision *</label>
                            <select name="decision" class="pub-form-control" required onchange="document.getElementById('amtWrap').style.display=this.value==='approve'?'block':'none'">
                                <option value="approve">✅ Approve (finalize application)</option>
                                <option value="reject">❌ Reject</option>
                            </select>
                        </div>
                        <div class="mb-3" id="amtWrap">
                            <label class="pub-form-label">Approved Amount (৳)</label>
                            <input type="number" name="approved_amount" class="pub-form-control" value="{{ $verification->recommended_amount ?? optional($case)->requested_amount }}" min="0" step="1">
                        </div>
                        <div class="mb-3">
                            <label class="pub-form-label">Decision Notes</label>
                            <textarea name="notes" class="pub-form-control" rows="3" placeholder="Reason / remarks for the record"></textarea>
                        </div>
                        <button type="submit" class="btn" style="background:#7c3aed;color:#fff;font-weight:600;border:none;"><i class="bi bi-check2-circle"></i> Record Final Decision</button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="vf-card">
                    <h5><i class="bi bi-clock-history me-2" style="color:#7c3aed;"></i>Activity Log</h5>
                    @forelse($activities as $a)
                    <div class="act">
                        <div class="dot"></div>
                        <div>
                            <div style="color:#111827;">{{ $a->description }}</div>
                            <div style="color:#9ca3af;font-size:0.72rem;margin-top:2px;">{{ optional($a->causer)->name ?? 'System' }} · {{ $a->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    <p style="color:#9ca3af;font-size:0.85rem;">No activity yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
