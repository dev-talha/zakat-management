@extends('layouts.app')
@section('title', 'Collections Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Collections</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Validated Collections Analysis</h2>
        <p class="page-subtitle">বৈধকৃত যাকাত ও অনুদান আদায় প্রতিবেদন</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back to Reports</a>
        <a href="{{ route('reports.export', 'collections') }}" class="btn btn-czm-primary"><i class="bi bi-download me-1"></i>Export Data</a>
    </div>
</div>

<!-- Filters -->
<div class="glass-card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">From Date (শুরুর তারিখ)</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">To Date (শেষের তারিখ)</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-czm-primary w-100"><i class="bi bi-filter me-1"></i>Apply Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Totals summary -->
    <div class="col-md-6">
        <div class="glass-card p-4">
            <div class="stat-icon green mb-3"><i class="bi bi-cash-stack"></i></div>
            <h5 class="text-secondary small">Total Validated Collection</h5>
            <h3 class="text-success fw-bold display-6">৳{{ number_format($data->sum('total'), 2) }}</h3>
            <span class="text-muted small">Validated transaction count: <b>{{ $data->sum('count') }}</b> entries</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="glass-card p-4">
            <div class="stat-icon purple mb-3"><i class="bi bi-pie-chart-fill"></i></div>
            <h5 class="text-secondary small">Most Active Fund Category</h5>
            @php
                $topFund = $data->sortByDesc('total')->first();
            @endphp
            <h3 class="text-white fw-bold text-capitalize">{{ $topFund ? $topFund->fund_type : 'None' }}</h3>
            <span class="text-muted small">Contributing <b>৳{{ $topFund ? number_format($topFund->total, 2) : 0 }}</b> of validated funds</span>
        </div>
    </div>
</div>

<!-- Collection Table -->
<div class="glass-card mb-4">
    <div class="card-header">
        <h5 class="text-white mb-0"><i class="bi bi-journal-album me-2 text-primary"></i>Collection Summary by Fund Categories</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="czm-table">
                <thead>
                    <tr>
                        <th>Fund Type</th>
                        <th>Transaction Count</th>
                        <th>Collected Total</th>
                        <th>Percentage Share</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotal = $data->sum('total');
                    @endphp
                    @forelse($data as $row)
                    @php
                        $share = $grandTotal > 0 ? round(($row->total / $grandTotal) * 100, 2) : 0;
                    @endphp
                    <tr>
                        <td class="fw-semibold text-capitalize text-white fs-6">
                            <i class="bi bi-circle-fill me-2" style="font-size:0.6rem; color: var(--czm-primary);"></i>
                            {{ $row->fund_type }}
                        </td>
                        <td>{{ $row->count }}</td>
                        <td class="text-success fw-bold">৳{{ number_format($row->total, 2) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; background-color: var(--czm-border);">
                                    <div class="progress-bar bg-success" style="width: {{ $share }}%"></div>
                                </div>
                                <small class="text-muted fw-bold">{{ $share }}%</small>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state py-4">
                                <i class="bi bi-wallet2 text-muted d-block" style="font-size:2rem;"></i>
                                <h6>No collections records found for selected period</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($data->count() > 0)
<!-- Pie Chart visualizing data shares -->
<div class="glass-card">
    <div class="card-header">
        <h5 class="text-white mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Percentage Share Visual Representation</h5>
    </div>
    <div class="card-body">
        <div class="chart-container" style="height: 250px;">
            <canvas id="collectionsPieChart"></canvas>
        </div>
    </div>
</div>
@endif
@endsection

@if($data->count() > 0)
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('collectionsPieChart').getContext('2d');
        const chartData = {
            labels: {!! json_encode($data->pluck('fund_type')->map(fn($t) => ucfirst($t))) !!},
            datasets: [{
                data: {!! json_encode($data->pluck('total')) !!},
                backgroundColor: [
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(139, 92, 246, 0.7)',
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(239, 68, 68, 0.7)',
                    'rgba(100, 116, 139, 0.7)'
                ],
                borderColor: 'var(--czm-bg-secondary)',
                borderWidth: 2
            }]
        };
        new Chart(ctx, {
            type: 'doughnut',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: '#94a3b8', font: { family: 'Inter' } }
                    }
                }
            }
        });
    });
</script>
@endpush
@endif
