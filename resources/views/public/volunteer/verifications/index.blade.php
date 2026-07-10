@extends('layouts.public')

@section('title', 'My Union Verifications | ইউনিয়ন যাচাই')

@push('styles')
<style>
    .vf-hero { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); padding: 32px 0; color: #fff; }
    .vf-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:0; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.04); }
    .vf-row { display:flex; align-items:center; gap:14px; padding:16px 20px; border-bottom:1px solid #f3f4f6; }
    .vf-row:last-child { border-bottom:0; }
    .vf-badge { font-size:0.72rem; font-weight:700; padding:3px 10px; border-radius:20px; }
    .vf-empty { text-align:center; padding:50px 20px; color:#9ca3af; }
</style>
@endpush

@php
    $stageColors = [
        'assessment'=>['#eff6ff','#1d4ed8'], 'field_verification'=>['#fffbeb','#b45309'],
        'supervisor_review'=>['#f5f3ff','#7c3aed'], 'approved'=>['#f0fdf4','#059669'],
        'rejected'=>['#fef2f2','#dc2626'], 'disbursement'=>['#ecfeff','#0891b2'],
    ];
@endphp

@section('content')
<div class="vf-hero">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div style="font-size:0.8rem;opacity:0.8;"><a href="{{ route('volunteer.dashboard') }}" style="color:#fff;text-decoration:none;"><i class="bi bi-arrow-left"></i> Dashboard</a></div>
                <h2 style="font-size:1.3rem;font-weight:700;margin:6px 0 0;"><i class="bi bi-clipboard-check me-2"></i>Zakat Applications — My Union</h2>
                <div style="font-size:0.85rem;opacity:0.85;margin-top:4px;">
                    <i class="bi bi-geo-alt-fill me-1"></i>{{ optional($vol->primaryArea)->name_en ?? $vol->union_name ?? 'Your union' }}
                    · Volunteer: {{ $vol->name_en }} ({{ $vol->volunteer_code }})
                </div>
            </div>
            <span class="vf-badge" style="background:rgba(255,255,255,0.2);color:#fff;">{{ $cases->total() }} application(s)</span>
        </div>
    </div>
</div>

<div style="background:#f8faf9;padding:32px 0;min-height:60vh;">
    <div class="container">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="vf-card">
            @forelse($cases as $case)
                @php $sc = $stageColors[$case->stage] ?? ['#f3f4f6','#374151']; @endphp
                <div class="vf-row">
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;color:#111827;">{{ $case->beneficiary->primary_person_name }}
                            <span style="font-weight:500;color:#9ca3af;font-size:0.8rem;">· {{ $case->case_no }}</span>
                        </div>
                        <div style="font-size:0.8rem;color:#6b7280;margin-top:2px;">
                            <span class="text-capitalize">{{ str_replace('_',' ',$case->case_type) }}</span>
                            · Zakat: {{ ucfirst($case->beneficiary->zakat_category ?? '-') }}
                            · Requested: ৳{{ number_format($case->requested_amount ?? 0) }}
                        </div>
                        @if($case->assignedVolunteer)
                        <div style="font-size:0.75rem;color:#9ca3af;margin-top:3px;"><i class="bi bi-person-check"></i> Working: {{ $case->assignedVolunteer->name_en }}</div>
                        @endif
                    </div>
                    <span class="vf-badge text-capitalize" style="background:{{ $sc[0] }};color:{{ $sc[1] }};">{{ str_replace('_',' ',$case->stage) }}</span>
                    <a href="{{ route('volunteer.verifications.show', $case) }}" class="btn btn-sm" style="background:#f59e0b;color:#fff;font-weight:600;border:none;flex-shrink:0;">Review</a>
                </div>
            @empty
                <div class="vf-empty">
                    <div style="font-size:2.5rem;">📭</div>
                    <h6 style="font-weight:700;color:#111827;margin-top:8px;">No applications in your union yet</h6>
                    <p style="font-size:0.85rem;">New Zakat applications from your union will appear here for verification.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-3">{{ $cases->links() }}</div>
    </div>
</div>
@endsection
