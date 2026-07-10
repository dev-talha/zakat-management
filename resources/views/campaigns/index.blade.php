@extends('layouts.app')
@section('title', 'Campaigns')
@section('breadcrumb')<li class="breadcrumb-item active">Campaigns</li>@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Campaigns & Funds Appeals</h2>
        <p class="page-subtitle">ক্যাম্পেইন ও তহবিল সংগ্রহ কার্যক্রম</p>
    </div>
    <a href="{{ route('campaigns.create') }}" class="btn btn-czm-primary"><i class="bi bi-plus-circle me-1"></i>Create Campaign</a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card card-green">
            <div class="stat-icon green"><i class="bi bi-megaphone-fill"></i></div>
            <div class="stat-value">{{ $campaigns->total() }}</div>
            <div class="stat-label">Total Campaigns</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card-purple">
            <div class="stat-icon purple"><i class="bi bi-play-circle-fill"></i></div>
            <div class="stat-value">{{ \App\Models\Campaign::where('status', 'active')->count() }}</div>
            <div class="stat-label">Active Campaigns</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card-gold">
            <div class="stat-icon gold"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value">৳{{ number_format(\App\Models\Campaign::sum('collected_amount'), 2) }}</div>
            <div class="stat-label">Total Raised</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card-blue">
            <div class="stat-icon blue"><i class="bi bi-bullseye"></i></div>
            <div class="stat-value">৳{{ number_format(\App\Models\Campaign::sum('target_amount'), 2) }}</div>
            <div class="stat-label">Overall Target</div>
        </div>
    </div>
</div>

<div class="glass-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h5 class="text-white mb-0"><i class="bi bi-list-stars me-2 text-primary"></i>Campaign List</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="czm-table">
                <thead>
                    <tr>
                        <th>Campaign</th>
                        <th>Fund Type</th>
                        <th>Target</th>
                        <th>Collected</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Starts / Ends</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                    @php
                        $percentage = $campaign->target_amount > 0 ? min(100, round(($campaign->collected_amount / $campaign->target_amount) * 100)) : 0;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-icon bg-secondary text-white rounded me-3 p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                    <i class="bi bi-megaphone"></i>
                                </div>
                                <div>
                                    <span class="d-block fw-semibold">{{ $campaign->name }}</span>
                                    <small class="text-muted">{{ $campaign->name_bn ?: 'Bangla label missing' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary text-capitalize">{{ $campaign->fund_type }}</span>
                        </td>
                        <td class="fw-semibold">৳{{ number_format($campaign->target_amount, 2) }}</td>
                        <td class="text-success fw-semibold">৳{{ number_format($campaign->collected_amount, 2) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="min-width: 120px;">
                                <div class="progress flex-grow-1" style="height: 6px; background-color: var(--czm-border);">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="fw-bold">{{ $percentage }}%</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge-status {{ $campaign->status }}">{{ ucfirst($campaign->status) }}</span>
                        </td>
                        <td>
                            <small class="d-block text-white">{{ $campaign->starts_at ? \Carbon\Carbon::parse($campaign->starts_at)->format('Y-m-d') : 'N/A' }}</small>
                            <small class="text-muted d-block">{{ $campaign->ends_at ? \Carbon\Carbon::parse($campaign->ends_at)->format('Y-m-d') : 'No Limit' }}</small>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-sm btn-czm-outline" title="View Details"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-sm btn-czm-outline" title="Edit Campaign"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this campaign?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-czm-outline text-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-megaphone-fill d-block text-muted"></i>
                                <h5>No campaigns found</h5>
                                <p class="text-muted">তহবিল সংগ্রহের জন্য কোনো সক্রিয় বা খসড়া ক্যাম্পেইন পাওয়া যায়নি।</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">
    {{ $campaigns->links() }}
</div>
@endsection
