@extends('layouts.public')

@section('title', 'Final Verifications | চূড়ান্ত যাচাই')

@push('styles')
<style>
    .vf-hero { background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); padding: 32px 0; color:#fff; }
    .vf-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:0; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.04); margin-bottom:24px; }
    .vf-card > .hd { padding:14px 20px; font-weight:700; color:#111827; border-bottom:2px solid #f3f4f6; }
    .vf-row { display:flex; align-items:center; gap:14px; padding:16px 20px; border-bottom:1px solid #f3f4f6; }
    .vf-row:last-child { border-bottom:0; }
    .vf-badge { font-size:0.72rem; font-weight:700; padding:3px 10px; border-radius:20px; }
    .vf-empty { text-align:center; padding:40px 20px; color:#9ca3af; }
</style>
@endpush

@section('content')
<div class="vf-hero">
    <div class="container">
        <div style="font-size:0.8rem;opacity:0.85;"><a href="{{ route('organization.dashboard') }}" style="color:#fff;text-decoration:none;"><i class="bi bi-arrow-left"></i> Dashboard</a></div>
        <h2 style="font-size:1.3rem;font-weight:700;margin:6px 0 0;"><i class="bi bi-patch-check me-2"></i>Final Verification Queue</h2>
        <div style="font-size:0.85rem;opacity:0.85;margin-top:4px;"><i class="bi bi-building me-1"></i>{{ $org->name_en }}</div>
    </div>
</div>

<div style="background:#f8faf9;padding:32px 0;min-height:60vh;">
    <div class="container">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="vf-card">
            <div class="hd"><i class="bi bi-hourglass-split me-2" style="color:#7c3aed;"></i>Awaiting Final Review ({{ $pending->count() }})</div>
            @forelse($pending as $v)
            <div class="vf-row">
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:700;color:#111827;">{{ optional($v->beneficiary)->primary_person_name }}
                        <span style="font-weight:500;color:#9ca3af;font-size:0.8rem;">· {{ optional($v->caseRecord)->case_no }}</span>
                    </div>
                    <div style="font-size:0.8rem;color:#6b7280;margin-top:2px;">
                        Volunteer: {{ optional($v->volunteer)->name_en ?? '-' }}
                        · Rec: <b class="text-capitalize">{{ str_replace('_',' ',$v->recommendation) }}</b>
                        @if($v->recommended_amount) · ৳{{ number_format($v->recommended_amount) }} @endif
                        · {{ optional($v->verifiedArea)->name_en }}
                    </div>
                </div>
                <a href="{{ route('organization.verifications.show', $v) }}" class="btn btn-sm" style="background:#7c3aed;color:#fff;font-weight:600;border:none;flex-shrink:0;">Finalize</a>
            </div>
            @empty
            <div class="vf-empty"><div style="font-size:2.2rem;">✅</div><p style="font-size:0.85rem;margin-top:6px;">No applications awaiting final review.</p></div>
            @endforelse
        </div>

        <div class="vf-card">
            <div class="hd"><i class="bi bi-archive me-2" style="color:#059669;"></i>Recently Finalized</div>
            @forelse($history as $v)
            <div class="vf-row">
                <div style="flex:1;">
                    <div style="font-weight:700;color:#111827;">{{ optional($v->beneficiary)->primary_person_name }}</div>
                    <div style="font-size:0.78rem;color:#9ca3af;">by {{ optional($v->reviewer)->name ?? '-' }} · {{ optional($v->reviewed_at)->diffForHumans() }}</div>
                </div>
                <span class="vf-badge" style="background:{{ $v->status==='approved' ? '#f0fdf4' : '#fef2f2' }};color:{{ $v->status==='approved' ? '#059669' : '#dc2626' }};">{{ strtoupper($v->status) }}</span>
            </div>
            @empty
            <div class="vf-empty" style="padding:24px;"><p style="font-size:0.85rem;">Nothing finalized yet.</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection
