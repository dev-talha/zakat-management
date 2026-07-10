@extends('layouts.app')
@section('title', $beneficiary->application_no)
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('beneficiaries.index') }}">Beneficiaries</a></li><li class="breadcrumb-item active">{{ $beneficiary->application_no }}</li>@endsection
@section('content')
<div class="page-header">
    <div><h2>{{ $beneficiary->primary_person_name }}</h2><p class="page-subtitle">{{ $beneficiary->application_no }}</p></div>
    <div class="d-flex gap-2">
        <a href="{{ route('beneficiaries.edit', $beneficiary) }}" class="btn btn-czm-outline"><i class="bi bi-pencil me-1"></i>Edit</a>
        @if($beneficiary->status === 'pending' || $beneficiary->status === 'under_review')
        <form method="POST" action="{{ route('beneficiaries.update', $beneficiary) }}">@csrf @method('PUT')
            <input type="hidden" name="status" value="verified">
            <button class="btn btn-czm-primary"><i class="bi bi-check2-circle me-1"></i>Verify</button>
        </form>
        @endif
    </div>
</div>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="glass-card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div style="font-size:3.5rem;color:var(--czm-primary);"><i class="bi bi-person-circle"></i></div>
                    <h5>{{ $beneficiary->primary_person_name }}</h5>
                    <span class="badge-status {{ $beneficiary->status }}">{{ ucfirst(str_replace('_',' ',$beneficiary->status)) }}</span>
                </div>
                <hr style="border-color:var(--czm-border);">
                <div class="small">
                    <div class="mb-2"><strong class="text-muted">Gender:</strong> {{ ucfirst($beneficiary->gender ?? 'N/A') }}</div>
                    <div class="mb-2"><strong class="text-muted">Mobile:</strong> {{ $beneficiary->mobile ?? 'N/A' }}</div>
                    <div class="mb-2"><strong class="text-muted">ID:</strong> {{ $beneficiary->identity_no ?? 'N/A' }} ({{ $beneficiary->identity_type }})</div>
                    <div class="mb-2"><strong class="text-muted">Income:</strong> ৳{{ number_format($beneficiary->monthly_income) }}/month</div>
                    <div class="mb-2"><strong class="text-muted">Category:</strong> {{ $beneficiary->zakat_category_label }}</div>
                    <div class="mb-2"><strong class="text-muted">Vulnerability Score:</strong> <span class="fw-bold" style="color:var(--czm-gold);">{{ $beneficiary->vulnerability_score }}</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        @if($beneficiary->household)
        <div class="glass-card mb-3">
            <div class="card-header"><h6><i class="bi bi-house me-2"></i>Household</h6></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6"><strong class="text-muted small">District:</strong><br>{{ $beneficiary->household->district }}</div>
                    <div class="col-md-6"><strong class="text-muted small">Upazila:</strong><br>{{ $beneficiary->household->upazila ?? 'N/A' }}</div>
                    <div class="col-md-6 mt-2"><strong class="text-muted small">Housing:</strong><br>{{ ucfirst($beneficiary->household->housing_type ?? 'N/A') }}</div>
                    <div class="col-md-6 mt-2"><strong class="text-muted small">Members:</strong><br>{{ $beneficiary->household->members->count() }}</div>
                    <div class="col-12 mt-2"><strong class="text-muted small">Address:</strong><br>{{ $beneficiary->household->address ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
        @endif
        <div class="glass-card">
            <div class="card-header"><h6><i class="bi bi-folder2-open me-2"></i>Cases</h6></div>
            <div class="card-body p-0">
                <table class="czm-table"><thead><tr><th>Case #</th><th>Type</th><th>Stage</th><th>Amount</th></tr></thead>
                <tbody>
                @forelse($beneficiary->cases as $c)
                    <tr><td><a href="{{ route('cases.show', $c) }}">{{ $c->case_no }}</a></td><td>{{ ucfirst($c->case_type) }}</td><td><span class="badge-status pending">{{ str_replace('_',' ',$c->stage) }}</span></td><td>৳{{ number_format($c->requested_amount) }}</td></tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No cases</td></tr>
                @endforelse
                </tbody></table>
            </div>
        </div>
    </div>
</div>
@endsection
