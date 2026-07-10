@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <p class="page-subtitle">কেন্দ্রীয় যাকাত ব্যবস্থাপনা ওভারভিউ</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('collections.create') }}" class="btn btn-czm-primary">
            <i class="bi bi-plus-circle me-1"></i>New Collection
        </a>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card card-green">
            <div class="stat-icon green"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value">৳{{ number_format($totalCollections) }}</div>
            <div class="stat-label">Total Collections</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-short"></i>Total Raised</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card card-purple">
            <div class="stat-icon purple"><i class="bi bi-send-check"></i></div>
            <div class="stat-value">৳{{ number_format($totalDistributions) }}</div>
            <div class="stat-label">Distributed</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-short"></i>Total Given</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card card-blue">
            <div class="stat-icon blue"><i class="bi bi-people"></i></div>
            <div class="stat-value">{{ number_format($totalDonors) }}</div>
            <div class="stat-label">Donors</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card card-gold">
            <div class="stat-icon gold"><i class="bi bi-person-hearts"></i></div>
            <div class="stat-value">{{ number_format($totalBeneficiaries) }}</div>
            <div class="stat-label">Beneficiaries</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card card-red">
            <div class="stat-icon red"><i class="bi bi-folder2-open"></i></div>
            <div class="stat-value">{{ $pendingCases }}</div>
            <div class="stat-label">Active Cases</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card card-green">
            <div class="stat-icon green"><i class="bi bi-megaphone"></i></div>
            <div class="stat-value">{{ $activeCampaigns }}</div>
            <div class="stat-label">Active Campaigns</div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="glass-card">
            <div class="card-header">
                <h6><i class="bi bi-graph-up me-2"></i>Collection vs Distribution (6 Months)</h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="glass-card">
            <div class="card-header">
                <h6><i class="bi bi-pie-chart me-2"></i>Fund Breakdown</h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="fundChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Fund Balances --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="glass-card">
            <div class="card-header">
                <h6><i class="bi bi-safe2 me-2"></i>Fund Balances</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($funds as $fund)
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="text-center p-3" style="background:var(--czm-bg-tertiary);border-radius:12px;">
                            <div class="fw-bold text-muted small text-uppercase">{{ $fund->name_bn ?? $fund->name }}</div>
                            <div class="fs-5 fw-bold mt-1" style="color:var(--czm-primary);">৳{{ number_format($fund->balance) }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tables Row --}}
<div class="row g-3">
    <div class="col-lg-6">
        <div class="glass-card">
            <div class="card-header">
                <h6><i class="bi bi-cash-coin me-2"></i>Recent Collections</h6>
                <a href="{{ route('collections.index') }}" class="btn btn-czm-outline btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="czm-table">
                        <thead>
                            <tr>
                                <th>Receipt #</th>
                                <th>Fund</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCollections as $col)
                            <tr>
                                <td class="fw-semibold">{{ $col->receipt_no }}</td>
                                <td><span class="badge-status active">{{ $col->fund_type }}</span></td>
                                <td class="fw-bold">৳{{ number_format($col->amount) }}</td>
                                <td><span class="badge-status {{ $col->status }}">{{ ucfirst($col->status) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No collections yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="glass-card">
            <div class="card-header">
                <h6><i class="bi bi-folder2-open me-2"></i>Recent Cases</h6>
                <a href="{{ route('cases.index') }}" class="btn btn-czm-outline btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="czm-table">
                        <thead>
                            <tr>
                                <th>Case #</th>
                                <th>Type</th>
                                <th>Stage</th>
                                <th>Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCases as $case)
                            <tr>
                                <td class="fw-semibold">{{ $case->case_no }}</td>
                                <td>{{ ucfirst($case->case_type) }}</td>
                                <td><span class="badge-status pending">{{ str_replace('_', ' ', $case->stage) }}</span></td>
                                <td><span class="badge-status {{ $case->priority === 'urgent' ? 'rejected' : ($case->priority === 'high' ? 'pending' : 'active') }}">{{ ucfirst($case->priority) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No cases yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyData = @json($monthlyData);
    const fundBreakdown = @json($fundBreakdown);

    // Trend Chart
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => d.label),
                datasets: [
                    {
                        label: 'Collections',
                        data: monthlyData.map(d => d.collections),
                        backgroundColor: 'rgba(16, 185, 129, 0.6)',
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderRadius: 8,
                    },
                    {
                        label: 'Distributions',
                        data: monthlyData.map(d => d.distributions),
                        backgroundColor: 'rgba(139, 92, 246, 0.6)',
                        borderColor: '#8b5cf6',
                        borderWidth: 2,
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Fund Doughnut
    const fundCtx = document.getElementById('fundChart');
    if (fundCtx && Object.keys(fundBreakdown).length > 0) {
        new Chart(fundCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(fundBreakdown).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                datasets: [{
                    data: Object.values(fundBreakdown),
                    backgroundColor: ['#10b981', '#8b5cf6', '#f59e0b', '#3b82f6', '#ef4444', '#06b6d4'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: { legend: { position: 'bottom', labels: { padding: 16 } } }
            }
        });
    }
});
</script>
@endpush
