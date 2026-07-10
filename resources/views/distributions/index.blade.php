@extends('layouts.app')
@section('title', 'Distributions')
@section('breadcrumb')<li class="breadcrumb-item active">Distributions</li>@endsection
@section('content')
<div class="page-header">
    <div><h2>Disbursements & Distributions</h2><p class="page-subtitle">যাকাত বিতরণ রেকর্ড</p></div>
    <a href="{{ route('distributions.create') }}" class="btn btn-czm-primary"><i class="bi bi-plus-circle me-1"></i>New Disbursement</a>
</div>
<div class="glass-card">
    <div class="card-body p-0">
        <table class="czm-table">
            <thead><tr><th>ID</th><th>Beneficiary</th><th>Fund</th><th>Type/Channel</th><th>Amount</th><th>Date</th></tr></thead>
            <tbody>
            @forelse($distributions as $d)
                <tr>
                    <td>#{{ $d->id }}</td>
                    <td>{{ $d->beneficiary?->primary_person_name }}</td>
                    <td><span class="badge-status active">{{ $d->fund?->name }}</span></td>
                    <td>{{ ucfirst(str_replace('_',' ',$d->distribution_type)) }}</td>
                    <td class="fw-bold">৳{{ number_format($d->approved_amount) }}</td>
                    <td>{{ $d->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="empty-state"><i class="bi bi-send-check d-block"></i><h5>No distributions found</h5></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $distributions->links() }}</div>
@endsection
