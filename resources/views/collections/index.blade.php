@extends('layouts.app')
@section('title', 'Collections')
@section('breadcrumb')<li class="breadcrumb-item active">Collections</li>@endsection
@section('content')
<div class="page-header">
    <div><h2>Collections</h2><p class="page-subtitle">তহবিল সংগ্রহ</p></div>
    <a href="{{ route('collections.create') }}" class="btn btn-czm-primary"><i class="bi bi-plus-circle me-1"></i>New Collection</a>
</div>
<div class="glass-card">
    <div class="card-header">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Search receipt no..." value="{{ request('search') }}" style="max-width:250px;">
            <select name="fund_type" class="form-select" style="max-width:160px;" onchange="this.form.submit()">
                <option value="">All Funds</option>
                <option value="zakat" {{ request('fund_type') == 'zakat' ? 'selected' : '' }}>Zakat</option>
                <option value="sadaqah" {{ request('fund_type') == 'sadaqah' ? 'selected' : '' }}>Sadaqah</option>
                <option value="fitrah" {{ request('fund_type') == 'fitrah' ? 'selected' : '' }}>Fitrah</option>
            </select>
            <button class="btn btn-czm-outline"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="czm-table">
            <thead><tr><th>Receipt #</th><th>Donor</th><th>Fund</th><th>Channel</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            @forelse($collections as $c)
                <tr>
                    <td class="fw-semibold"><a href="{{ route('collections.show', $c) }}">{{ $c->receipt_no }}</a></td>
                    <td>{{ $c->is_anonymous ? 'Anonymous' : $c->donor?->display_name }}</td>
                    <td><span class="badge-status active">{{ ucfirst($c->fund_type) }}</span></td>
                    <td>{{ ucfirst($c->source_channel) }}</td>
                    <td class="fw-bold">৳{{ number_format($c->amount) }}</td>
                    <td><span class="badge-status {{ $c->status }}">{{ ucfirst($c->status) }}</span></td>
                    <td>{{ $c->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="7"><div class="empty-state"><i class="bi bi-cash-stack d-block"></i><h5>No collections found</h5></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $collections->links() }}</div>
@endsection
