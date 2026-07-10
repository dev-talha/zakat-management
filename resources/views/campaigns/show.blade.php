@extends('layouts.app')
@section('title', $campaign->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('campaigns.index') }}">Campaigns</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($campaign->name, 25) }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $campaign->name }}</h2>
        <p class="page-subtitle">{{ $campaign->name_bn ?: 'ক্যাম্পেইন বিবরণ' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('campaigns.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-czm-primary"><i class="bi bi-pencil me-1"></i>Edit Campaign</a>
    </div>
</div>

@php
    $percentage = $campaign->target_amount > 0 ? min(100, round(($campaign->collected_amount / $campaign->target_amount) * 100)) : 0;
@endphp

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <!-- Campaign description details -->
        <div class="glass-card mb-4">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-info-square me-2 text-primary"></i>Campaign Overview</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <span class="text-muted d-block text-uppercase font-monospace" style="font-size: 0.75rem;">Fund Category</span>
                        <span class="text-white fw-bold fs-5 text-capitalize">{{ $campaign->fund_type }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block text-uppercase font-monospace" style="font-size: 0.75rem;">Current Status</span>
                        <span class="badge-status {{ $campaign->status }} fs-6">{{ ucfirst($campaign->status) }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-white border-bottom border-secondary pb-2">English Description</h6>
                    <p class="text-secondary" style="white-space: pre-wrap;">{{ $campaign->description ?: 'No English description provided.' }}</p>
                </div>

                <div>
                    <h6 class="text-white border-bottom border-secondary pb-2">বাংলা বিবরণ</h6>
                    <p class="text-secondary" style="white-space: pre-wrap;">{{ $campaign->description_bn ?: 'কোনো বাংলা বিবরণ দেওয়া হয়নি।' }}</p>
                </div>
            </div>
        </div>

        <!-- Collections History loaded via relation -->
        <div class="glass-card">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-cash-coin me-2 text-success"></i>Recent Contributions</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="czm-table">
                        <thead>
                            <tr>
                                <th>Receipt No</th>
                                <th>Donor</th>
                                <th>Channel</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Received At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaign->collections as $collection)
                            <tr>
                                <td class="font-monospace fw-semibold">#{{ $collection->receipt_no }}</td>
                                <td>
                                    @if($collection->is_anonymous)
                                        <span class="text-muted"><i>Anonymous Donor</i></span>
                                    @else
                                        {{ $collection->donor?->display_name ?: 'Walk-in / General' }}
                                    @endif
                                </td>
                                <td>
                                    <span class="text-capitalize text-secondary">{{ $collection->source_channel }}</span>
                                </td>
                                <td class="text-success fw-bold">৳{{ number_format($collection->amount, 2) }}</td>
                                <td>
                                    <span class="badge-status {{ $collection->status }}">{{ ucfirst($collection->status) }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $collection->created_at->format('Y-m-d H:i') }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state py-4">
                                        <i class="bi bi-wallet2 d-block text-muted" style="font-size:2rem;"></i>
                                        <h6>No contributions yet</h6>
                                        <p class="text-muted small">এই ক্যাম্পেইনের আওতায় কোনো সাহায্য সংগ্রহ করা হয়নি।</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaign target stats sidebar -->
    <div class="col-lg-4">
        <div class="glass-card mb-4">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Funding Progress</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="display-5 fw-extrabold text-success mb-2">৳{{ number_format($campaign->collected_amount, 2) }}</div>
                    <span class="text-muted d-block small">Collected out of <b>৳{{ number_format($campaign->target_amount, 2) }}</b> target</span>
                </div>

                <div class="progress mb-3" style="height: 12px; background-color: var(--czm-border);">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="d-flex justify-content-between text-white fw-bold mb-4">
                    <span>{{ $percentage }}% Complete</span>
                    <span class="text-muted">৳{{ number_format(max(0, $campaign->target_amount - $campaign->collected_amount), 2) }} Remaining</span>
                </div>

                <ul class="list-group list-group-flush bg-transparent border-0">
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Campaign ID:</span>
                        <span class="text-white fw-semibold">#{{ $campaign->id }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Slug Identifier:</span>
                        <span class="text-white font-monospace" style="font-size:0.75rem;">{{ $campaign->slug }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Starts At:</span>
                        <span class="text-white">{{ $campaign->starts_at ? \Carbon\Carbon::parse($campaign->starts_at)->format('F d, Y') : 'Immediate' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Ends At:</span>
                        <span class="text-white">{{ $campaign->ends_at ? \Carbon\Carbon::parse($campaign->ends_at)->format('F d, Y') : 'Ongoing' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
