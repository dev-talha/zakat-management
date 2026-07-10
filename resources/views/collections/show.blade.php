@extends('layouts.app')
@section('title', 'Collection Details')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('collections.index') }}">Collections</a></li><li class="breadcrumb-item active">{{ $collection->receipt_no }}</li>@endsection
@section('content')
<div class="page-header">
    <div><h2>Receipt {{ $collection->receipt_no }}</h2></div>
    <button class="btn btn-czm-outline" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print</button>
</div>
<div class="glass-card" style="max-width:800px; margin: 0 auto;">
    <div class="card-body p-5">
        <div class="text-center mb-4 border-bottom border-secondary border-opacity-25 pb-4">
            <div class="brand-icon mx-auto mb-2"><i class="bi bi-moon-stars-fill"></i></div>
            <h3 class="fw-bold text-primary">Central Zakat Management</h3>
            <p class="text-muted mb-0">Official Donation Receipt</p>
        </div>
        
        <div class="row mb-5">
            <div class="col-sm-6">
                <h6 class="text-muted mb-1">Donor Details</h6>
                @if($collection->is_anonymous)
                    <strong>Anonymous Donor</strong>
                @else
                    <strong>{{ $collection->donor?->display_name ?? 'Guest' }}</strong><br>
                    @if($collection->donor?->user)
                        <small>{{ $collection->donor->user->email }}<br>{{ $collection->donor->user->mobile }}</small>
                    @endif
                @endif
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <h6 class="text-muted mb-1">Receipt Info</h6>
                <strong>No:</strong> {{ $collection->receipt_no }}<br>
                <strong>Date:</strong> {{ $collection->created_at->format('d M Y, h:i A') }}<br>
                <strong>Status:</strong> <span class="badge-status {{ $collection->status }}">{{ ucfirst($collection->status) }}</span>
            </div>
        </div>

        <table class="table table-bordered border-secondary border-opacity-25 mb-5">
            <thead class="bg-dark bg-opacity-10">
                <tr><th>Description</th><th class="text-end">Amount</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ ucfirst($collection->fund_type) }} Fund Contribution</strong>
                        @if($collection->campaign)<br><small class="text-muted">Campaign: {{ $collection->campaign->name }}</small>@endif
                        <br><small class="text-muted">Channel: {{ ucfirst(str_replace('_', ' ', $collection->source_channel)) }}</small>
                    </td>
                    <td class="text-end fs-5 fw-bold text-primary align-middle">৳{{ number_format($collection->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="text-center text-muted small border-top border-secondary border-opacity-25 pt-4">
            <p class="mb-1">May Allah accept your contribution and reward you abundantly.</p>
            <p class="mb-0">This is a computer-generated receipt.</p>
        </div>
    </div>
</div>
@endsection
