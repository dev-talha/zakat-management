@extends('layouts.app')
@section('title', $case->case_no)
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('cases.index') }}">Cases</a></li><li class="breadcrumb-item active">{{ $case->case_no }}</li>@endsection
@section('content')
<div class="page-header">
    <div><h2>{{ $case->case_no }}</h2><p class="page-subtitle">{{ ucfirst($case->case_type) }} • <span class="badge-status {{ $case->priority === 'urgent' ? 'rejected' : 'active' }}">{{ ucfirst($case->priority) }}</span></p></div>
    <div class="d-flex gap-2">
        <a href="{{ route('cases.edit', $case) }}" class="btn btn-czm-outline"><i class="bi bi-pencil me-1"></i>Edit</a>
        @if($case->stage !== 'closed' && $case->stage !== 'rejected')
        <form method="POST" action="{{ route('cases.advance', $case) }}">@csrf<button class="btn btn-czm-primary">Advance Stage <i class="bi bi-arrow-right-circle ms-1"></i></button></form>
        @endif
    </div>
</div>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="glass-card mb-3">
            <div class="card-body">
                <h5 class="mb-3">Status: <span class="badge-status pending">{{ str_replace('_',' ',$case->stage) }}</span></h5>
                <div class="mb-2"><strong class="text-muted small">Beneficiary:</strong><br><a href="{{ route('beneficiaries.show', $case->beneficiary_id) }}">{{ $case->beneficiary?->primary_person_name }}</a></div>
                <div class="mb-2"><strong class="text-muted small">Requested:</strong><br>৳{{ number_format($case->requested_amount) }}</div>
                <div class="mb-2"><strong class="text-muted small">Approved:</strong><br>৳{{ number_format($case->approved_amount ?? 0) }}</div>
                <div><strong class="text-muted small">Assigned To:</strong><br>{{ $case->agent?->user?->name ?? 'Unassigned' }}</div>
            </div>
        </div>
        <div class="glass-card">
            <div class="card-header"><h6>Workflow Stages</h6></div>
            <div class="card-body">
                @php $stages = ['assessment', 'field_verification', 'supervisor_review', 'shariah_review', 'finance_review', 'approved', 'disbursement', 'follow_up', 'closed']; @endphp
                <ul class="list-unstyled mb-0 position-relative">
                    @foreach($stages as $i => $stg)
                        @php $isPast = array_search($case->stage, $stages) > $i; $isCurr = $case->stage === $stg; @endphp
                        <li class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:24px;height:24px;background:{{ $isPast ? 'var(--czm-primary)' : ($isCurr ? 'var(--czm-gold)' : 'var(--czm-bg-tertiary)') }};color:{{ $isPast||$isCurr ? '#fff' : 'var(--czm-text-muted)' }};"><i class="bi {{ $isPast ? 'bi-check' : ($isCurr ? 'bi-dot' : 'bi-circle') }}"></i></div>
                            <span style="color:{{ $isPast||$isCurr ? 'var(--czm-text-primary)' : 'var(--czm-text-muted)' }};font-weight:{{ $isCurr ? 'bold' : 'normal' }}">{{ ucfirst(str_replace('_',' ',$stg)) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="glass-card mb-3">
            <div class="card-header"><h6>Details</h6></div>
            <div class="card-body">
                <p>{{ $case->description ?: 'No description provided.' }}</p>
            </div>
        </div>
        <div class="glass-card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Follow-Ups</h6>
                @if(in_array($case->stage, ['disbursement', 'follow_up', 'closed']))
                <a href="{{ route('followups.create', ['case_id' => $case->id]) }}" class="btn btn-sm btn-czm-primary"><i class="bi bi-plus-circle me-1"></i>Log Follow-Up</a>
                @endif
            </div>
            <div class="card-body p-0">
                <table class="czm-table"><thead><tr><th>Date</th><th>Agent</th><th>Impact</th><th>Utilized Properly</th></tr></thead>
                <tbody>
                @forelse(\App\Models\FollowUp::where('case_id', $case->id)->get() as $f)
                    <tr>
                        <td>{{ $f->follow_up_date->format('d M Y') }}</td>
                        <td>{{ $f->agent?->name }}</td>
                        <td>{{ $f->impact_rating }}/5</td>
                        <td>{!! $f->funds_utilized_properly ? '<span class="badge-status active">Yes</span>' : '<span class="badge-status rejected">No</span>' !!}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No follow-ups recorded yet</td></tr>
                @endforelse
                </tbody></table>
            </div>
        </div>
        <div class="glass-card mb-3">
            <div class="card-header"><h6>Field Visits</h6></div>
            <div class="card-body p-0">
                <table class="czm-table"><thead><tr><th>Date</th><th>Agent</th><th>Risk</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($case->visits as $v)
                    <tr><td>{{ $v->visit_at->format('d M Y') }}</td><td>{{ $v->agent?->user?->name }}</td><td>{!! $v->risk_flag ? '<span class="badge-status rejected">High</span>' : '<span class="badge-status active">Low</span>' !!}</td><td>{{ ucfirst($v->supervisor_status) }}</td></tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No field visits recorded</td></tr>
                @endforelse
                </tbody></table>
            </div>
        </div>
    </div>
</div>
@endsection
