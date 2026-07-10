@extends('layouts.app')
@section('title', 'Reports & Analytics')
@section('breadcrumb')<li class="breadcrumb-item active">Reports</li>@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>System Reports & Analytics</h2>
        <p class="page-subtitle">প্রতিবেদন ও বিশ্লেষণ কেন্দ্র</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Collections Report Card -->
    <div class="col-md-6">
        <div class="glass-card h-100 p-4 d-flex flex-column justify-content-between">
            <div>
                <div class="stat-icon green mb-3"><i class="bi bi-graph-up-arrow"></i></div>
                <h4 class="text-white mb-2">Collections Reports</h4>
                <p class="text-secondary small mb-4">
                    Analyze all incoming donations, Zakat, Sadaqah, and emergency relief funds. View breakdown by categories, timeframes, and channels.
                </p>
            </div>
            <div>
                <a href="{{ route('reports.collections') }}" class="btn btn-czm-primary w-100"><i class="bi bi-search me-1"></i>View Collections Analysis</a>
            </div>
        </div>
    </div>

    <!-- Distributions Report Card -->
    <div class="col-md-6">
        <div class="glass-card h-100 p-4 d-flex flex-column justify-content-between">
            <div>
                <div class="stat-icon purple mb-3"><i class="bi bi-graph-down-arrow"></i></div>
                <h4 class="text-white mb-2">Distributions Reports</h4>
                <p class="text-secondary small mb-4">
                    Analyze all outgoing aid, disbursements, livelihood distributions, and food packages. Track payouts through mobile financial services or cash.
                </p>
            </div>
            <div>
                <a href="{{ route('reports.distributions') }}" class="btn btn-czm-primary w-100"><i class="bi bi-search me-1"></i>View Distributions Analysis</a>
            </div>
        </div>
    </div>
</div>

<!-- Premium Live Visualization Card -->
<div class="glass-card">
    <div class="card-header">
        <h5 class="text-white mb-0"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Collections vs. Distributions Performance</h5>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="perfChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('perfChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Collections (আদায়কৃত)',
                        data: [120000, 190000, 300000, 500000, 200000, 300000, 250000, 400000, 350000, 280000, 320000, 450000],
                        backgroundColor: 'rgba(16, 185, 129, 0.6)',
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderRadius: 6
                    },
                    {
                        label: 'Distributions (বিতরণকৃত)',
                        data: [80000, 150000, 220000, 450000, 180000, 250000, 200000, 320000, 300000, 240000, 280000, 400000],
                        backgroundColor: 'rgba(139, 92, 246, 0.6)',
                        borderColor: '#8b5cf6',
                        borderWidth: 2,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#94a3b8', font: { family: 'Inter' } }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8' }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });
    });
</script>
@endpush
