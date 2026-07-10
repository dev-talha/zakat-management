@extends('layouts.app')
@section('title', 'Distributions Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Distributions</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Disbursed Aid & Distributions Analysis</h2>
        <p class="page-subtitle">বিতরণকৃত যাকাত ও জীবিকায়ন অনুদান আদায় বিশ্লেষণ</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back to Reports</a>
        <a href="{{ route('reports.export', 'distributions') }}" class="btn btn-czm-primary"><i class="bi bi-download me-1"></i>Export Data</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Totals summary -->
    <div class="col-md-6">
        <div class="glass-card p-4">
            <div class="stat-icon purple mb-3"><i class="bi bi-send-check-fill"></i></div>
            <h5 class="text-secondary small">Total Disbursed Aid</h5>
            <h3 class="text-primary fw-bold display-6">৳{{ number_format($data->sum('total'), 2) }}</h3>
            <span class="text-muted small">Disbursement transaction count: <b>{{ $data->sum('count') }}</b> beneficiaries</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="glass-card p-4">
            <div class="stat-icon gold mb-3"><i class="bi bi-person-check-fill"></i></div>
            <h5 class="text-secondary small">Dominant Channel Mode</h5>
            @php
                $topMode = $data->sortByDesc('total')->first();
            @endphp
            <h3 class="text-white fw-bold text-capitalize">{{ $topMode ? $topMode->distribution_type : 'None' }}</h3>
            <span class="text-muted small">Disbursing <b>৳{{ $topMode ? number_format($topMode->total, 2) : 0 }}</b> in total assistance</span>
        </div>
    </div>
</div>

<!-- Distribution Table -->
<div class="glass-card mb-4">
    <div class="card-header">
        <h5 class="text-white mb-0"><i class="bi bi-send-check me-2 text-primary"></i>Disbursements Summary by Channel / Category</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="czm-table">
                <thead>
                    <tr>
                        <th>Distribution Type</th>
                        <th>Beneficiaries Reached</th>
                        <th>Total Disbursed</th>
                        <th>Percentage share</th>
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
                            <i class="bi bi-chevron-right me-2 text-primary"></i>
                            {{ str_replace('_', ' ', $row->distribution_type) }}
                        </td>
                        <td>{{ $row->count }}</td>
                        <td class="text-primary fw-bold">৳{{ number_format($row->total, 2) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; background-color: var(--czm-border);">
                                    <div class="progress-bar bg-primary" style="width: {{ $share }}%"></div>
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
                                <h6>No disbursement logs registered as settled</h6>
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
        <h5 class="text-white mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Visual Distributions Breakdown</h5>
    </div>
    <div class="card-body">
        <div class="chart-container" style="height: 250px;">
            <canvas id="distributionsPieChart"></canvas>
        </div>
    </div>
</div>
@endif
@endsection

@if($data->count() > 0)
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('distributionsPieChart').getContext('2d');
        const chartData = {
            labels: {!! json_encode($data->pluck('distribution_type')->map(fn($t) => ucfirst(str_replace('_', ' ', $t)))) !!},
            datasets: [{
                data: {!! json_encode($data->pluck('total')) !!},
                backgroundColor: [
                    'rgba(139, 92, 246, 0.7)',
                    'rgba(16, 185, 129, 0.7)',
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
