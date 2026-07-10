@extends('layouts.app')
@section('title', $fund->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('funds.index') }}">Funds</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($fund->name, 25) }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $fund->name }} Ledger</h2>
        <p class="page-subtitle">{{ $fund->name_bn ?: 'ফান্ড খতিয়ান খাতা' }}</p>
    </div>
    <a href="{{ route('funds.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back to Funds</a>
</div>

<div class="row g-4 mb-4">
    <!-- Left column: Fund details card -->
    <div class="col-lg-4">
        <div class="glass-card h-100">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Fund Vault Details</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4 pb-3 border-bottom border-secondary">
                    <span class="text-muted small d-block">Available balance</span>
                    <h3 class="text-success display-6 fw-bold">৳{{ number_format($fund->balance, 2) }}</h3>
                </div>

                <div class="mb-4">
                    <label class="text-muted d-block small font-monospace text-uppercase">Description</label>
                    <p class="text-secondary" style="font-size:0.9rem;">{{ $fund->description ?: 'No description provided.' }}</p>
                </div>

                <ul class="list-group list-group-flush bg-transparent border-0">
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Fund Code:</span>
                        <span class="text-white fw-semibold">#{{ $fund->code }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Category Type:</span>
                        <span class="text-white text-capitalize fw-semibold">{{ $fund->type }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Restricted Fund:</span>
                        @if($fund->restricted_flag)
                            <span class="badge bg-danger">Yes</span>
                        @else
                            <span class="badge bg-success">No</span>
                        @endif
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Branch Scoped:</span>
                        @if($fund->branch_scoped_flag)
                            <span class="text-warning fw-semibold">Yes</span>
                        @else
                            <span class="text-muted">No</span>
                        @endif
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Status:</span>
                        <span class="badge-status {{ $fund->status === 'active' ? 'active' : 'inactive' }}">{{ ucfirst($fund->status) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right column: Fund ledgers list table -->
    <div class="col-lg-8">
        <div class="glass-card">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-journal-check me-2 text-primary"></i>Ledger Entries</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="czm-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Entry Type</th>
                                <th>Ref</th>
                                <th>Narration</th>
                                <th>Debit (Out)</th>
                                <th>Credit (In)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ledgers as $ledger)
                            <tr>
                                <td>
                                    <small class="d-block text-white fw-semibold">{{ $ledger->effective_at ? \Carbon\Carbon::parse($ledger->effective_at)->format('Y-m-d') : $ledger->created_at->format('Y-m-d') }}</small>
                                    <small class="text-muted">{{ $ledger->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge-status @if($ledger->entry_type === 'collection') active @elseif($ledger->entry_type === 'distribution') rejected @else pending @endif text-capitalize">
                                        {{ $ledger->entry_type }}
                                    </span>
                                </td>
                                <td>
                                    @if($ledger->ref_type && $ledger->ref_id)
                                        <small class="d-block font-monospace text-muted">{{ class_basename($ledger->ref_type) }} #{{ $ledger->ref_id }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-secondary small d-block" style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $ledger->narration }}">
                                        {{ $ledger->narration ?: 'No narration' }}
                                    </span>
                                </td>
                                <td class="text-danger fw-bold">
                                    {{ $ledger->debit > 0 ? '৳' . number_format($ledger->debit, 2) : '-' }}
                                </td>
                                <td class="text-success fw-bold">
                                    {{ $ledger->credit > 0 ? '৳' . number_format($ledger->credit, 2) : '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state py-4">
                                        <i class="bi bi-journal text-muted d-block" style="font-size:2rem;"></i>
                                        <h6>No ledger entries found</h6>
                                        <p class="text-muted small">এই তহবিলে এখনও কোনো আদান-প্রদান নথিবদ্ধ হয়নি।</p>
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
            {{ $ledgers->links() }}
        </div>
    </div>
</div>
@endsection
