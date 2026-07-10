@extends('layouts.app')
@section('title', $donor->display_name)
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('donors.index') }}">Donors</a></li><li class="breadcrumb-item active">{{ $donor->display_name }}</li>@endsection

@section('content')
<div class="page-header">
    <div><h2>{{ $donor->display_name }}</h2><p class="page-subtitle">{{ $donor->user?->email }}</p></div>
    <a href="{{ route('donors.edit', $donor) }}" class="btn btn-czm-outline"><i class="bi bi-pencil me-1"></i>Edit</a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="glass-card">
            <div class="card-body text-center">
                <div style="font-size:4rem;color:var(--czm-primary);"><i class="bi bi-person-circle"></i></div>
                <h4 class="mt-2">{{ $donor->display_name }}</h4>
                <span class="badge-status active">{{ ucfirst($donor->donor_type) }}</span>
                <hr style="border-color:var(--czm-border);">
                <div class="text-start">
                    <div class="mb-2"><strong class="text-muted small">Email:</strong><br>{{ $donor->user?->email }}</div>
                    <div class="mb-2"><strong class="text-muted small">Mobile:</strong><br>{{ $donor->user?->mobile ?? 'N/A' }}</div>
                    <div class="mb-2"><strong class="text-muted small">KYC:</strong><br><span class="badge-status {{ $donor->kyc_status }}">{{ ucfirst($donor->kyc_status) }}</span></div>
                    <div><strong class="text-muted small">Member Since:</strong><br>{{ $donor->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="glass-card mb-3">
            <div class="card-header"><h6>Donation History</h6></div>
            <div class="card-body p-0">
                <table class="czm-table">
                    <thead><tr><th>Receipt</th><th>Fund</th><th>Amount</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($donor->collections as $col)
                        <tr>
                            <td>{{ $col->receipt_no }}</td><td>{{ ucfirst($col->fund_type) }}</td>
                            <td class="fw-bold">৳{{ number_format($col->amount) }}</td>
                            <td>{{ $col->created_at->format('d M Y') }}</td>
                            <td><span class="badge-status {{ $col->status }}">{{ ucfirst($col->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No donations yet</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
