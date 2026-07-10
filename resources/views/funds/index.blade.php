@extends('layouts.app')
@section('title', 'Fund Ledger')
@section('breadcrumb')<li class="breadcrumb-item active">Funds</li>@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Shariah Fund Ledgers</h2>
        <p class="page-subtitle">শরীয়াহ ফান্ড ও সাধারণ হিসাব খাতা</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card card-green">
            <div class="stat-icon green"><i class="bi bi-safe2-fill"></i></div>
            <div class="stat-value">৳{{ number_format($totalBalance, 2) }}</div>
            <div class="stat-label">Total Vault Balance</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card card-purple">
            <div class="stat-icon purple"><i class="bi bi-wallet2"></i></div>
            <div class="stat-value">{{ $funds->count() }}</div>
            <div class="stat-label">Active Funds</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card card-gold">
            <div class="stat-icon gold"><i class="bi bi-shuffle"></i></div>
            <div class="stat-value">৳{{ number_format($funds->where('type', 'zakat')->sum('computed_balance'), 2) }}</div>
            <div class="stat-label">Zakat Fund Balance</div>
        </div>
    </div>
</div>

<div class="glass-card">
    <div class="card-header">
        <h5 class="text-white mb-0"><i class="bi bi-list-columns-reverse me-2 text-primary"></i>Fund Vaults</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="czm-table">
                <thead>
                    <tr>
                        <th>Fund Code</th>
                        <th>Fund Name</th>
                        <th>Category Type</th>
                        <th>Restricted Flag</th>
                        <th>Branch Scoped</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($funds as $fund)
                    <tr>
                        <td class="font-monospace fw-bold">#{{ $fund->code }}</td>
                        <td>
                            <span class="d-block fw-semibold text-white">{{ $fund->name }}</span>
                            <small class="text-muted">{{ $fund->name_bn ?: 'Bangla label missing' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary text-capitalize">{{ $fund->type }}</span>
                        </td>
                        <td>
                            @if($fund->restricted_flag)
                                <span class="badge bg-danger">Restricted (সীমিত)</span>
                            @else
                                <span class="badge bg-success">Unrestricted (উন্মুক্ত)</span>
                            @endif
                        </td>
                        <td>
                            @if($fund->branch_scoped_flag)
                                <span class="text-warning fw-semibold">Branch Scoped</span>
                            @else
                                <span class="text-muted">National Scope</span>
                            @endif
                        </td>
                        <td class="fs-5 fw-bold text-success">৳{{ number_format($fund->computed_balance, 2) }}</td>
                        <td>
                            <span class="badge-status {{ $fund->status === 'active' ? 'active' : 'inactive' }}">
                                {{ ucfirst($fund->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('funds.show', $fund) }}" class="btn btn-sm btn-czm-primary"><i class="bi bi-journal-text me-1"></i>Ledger View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state py-5">
                                <i class="bi bi-wallet2 text-muted d-block" style="font-size:3rem;"></i>
                                <h5>No funds available</h5>
                                <p class="text-muted">কোনো শরীয়াহ বা সাধারণ তহবিল হিসাব পাওয়া যায়নি।</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
