@extends('layouts.app')

@section('title', 'Blockchain Ledger')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="h3 mb-1"><i class="bi bi-link-45deg me-2 text-primary"></i>Blockchain Ledger (On-chain Anchors)</h2>
            <p class="text-muted mb-0">Immutable proof of Zakat transactions anchored to Ethereum ({{ config('blockchain.network') }}).</p>
        </div>
        <form method="POST" action="{{ route('blockchain.ledger.sync') }}">
            @csrf
            <button class="btn btn-outline-primary"><i class="bi bi-arrow-repeat me-1"></i>Sync on-chain status</button>
        </form>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    {{-- Network status --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><div class="glass-card p-3"><div class="text-muted small">Total Anchors</div><div class="h4 mb-0">{{ number_format($stats['total']) }}</div></div></div>
        <div class="col-6 col-md-3"><div class="glass-card p-3"><div class="text-muted small">Confirmed</div><div class="h4 mb-0 text-success">{{ number_format($stats['confirmed']) }}</div></div></div>
        <div class="col-6 col-md-3"><div class="glass-card p-3"><div class="text-muted small">Sent / Pending</div><div class="h4 mb-0 text-warning">{{ number_format($stats['sent']) }}</div></div></div>
        <div class="col-6 col-md-3"><div class="glass-card p-3"><div class="text-muted small">Failed</div><div class="h4 mb-0 text-danger">{{ number_format($stats['failed']) }}</div></div></div>
    </div>

    @if(($status['ok'] ?? false))
    <div class="alert alert-light border d-flex flex-wrap gap-4 small">
        <span><strong>Network:</strong> {{ $status['chain_id'] }} {{ $status['is_sepolia'] ? '(Sepolia)' : '' }}</span>
        <span><strong>Block:</strong> {{ number_format($status['block']) }}</span>
        <span><strong>Account:</strong> <code>{{ $status['account'] }}</code></span>
        <span><strong>Balance:</strong> {{ $status['balance_eth'] }} ETH</span>
        @if(!empty($status['contract']))
        <span><strong>Contract:</strong> <a href="{{ rtrim(config('blockchain.explorer'),'/') }}/address/{{ $status['contract'] }}" target="_blank"><code>{{ \Illuminate\Support\Str::limit($status['contract'], 14) }}</code></a></span>
        @endif
        <span class="badge bg-{{ $status['enabled'] ? 'success' : 'warning text-dark' }}">Anchoring {{ $status['enabled'] ? 'ON' : 'OFF' }}</span>
    </div>
    @endif

    <div class="glass-card shadow">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Txn / Invoice ID</th><th>Type</th><th>Amount</th><th>Method</th><th>Verified</th><th>Block</th><th>When</th><th class="text-end">Etherscan</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($anchors as $a)
                    @php
                        $type = $a->anchorable_type ? class_basename($a->anchorable_type) : '—';
                        $badge = ['confirmed'=>'success','sent'=>'warning text-dark','pending'=>'secondary','failed'=>'danger','skipped'=>'light text-dark'][$a->status] ?? 'secondary';
                        $url = $a->explorer_url ?: ($a->tx_hash ? rtrim(config('blockchain.explorer'),'/').'/tx/'.$a->tx_hash : null);
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $a->reference ?? '—' }}
                            <div class="text-muted" style="font-size:0.72rem;"><code>{{ \Illuminate\Support\Str::limit($a->payload_hash, 14) }}</code></div>
                        </td>
                        <td>{{ $type }}</td>
                        <td>{{ $a->amount_major ? $a->amount_major . ' ' . $a->currency : '—' }}</td>
                        <td><span class="badge bg-light text-dark" style="font-size:0.68rem;">{{ $a->method }}</span></td>
                        <td>
                            @if($a->status === 'confirmed')
                                <span class="badge bg-success"><i class="bi bi-patch-check-fill"></i> Verified</span>
                            @else
                                <span class="badge bg-{{ $badge }} text-capitalize">{{ $a->status }}</span>
                            @endif
                        </td>
                        <td>{{ $a->block_number ? number_format($a->block_number) : '—' }}</td>
                        <td class="small text-muted">{{ $a->created_at->diffForHumans() }}</td>
                        <td class="text-end">
                            @if($url)
                            <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-up-right"></i> View</a>
                            @elseif($a->status === 'skipped')
                            <span class="text-muted small">not sent</span>
                            @else
                            <span class="text-danger small">{{ \Illuminate\Support\Str::limit($a->error, 30) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">
                        <div style="font-size:2rem;">🔗</div>
                        No anchors yet. Enable anchoring in Settings → Blockchain, then donations/disbursements will appear here.
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $anchors->links() }}</div>
</div>
@endsection
