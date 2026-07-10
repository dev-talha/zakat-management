@extends('layouts.app')
@section('title', 'Zakat Calculator')
@section('breadcrumb')<li class="breadcrumb-item active">Calculator</li>@endsection
@section('content')
<div class="page-header">
    <div><h2>Zakat Calculator</h2><p class="page-subtitle">যাকাত ক্যালকুলেটর (Zakat Al-Mal)</p></div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <form method="POST" action="{{ route('calculator.calculate') }}" id="calcForm">
            @csrf
            
            {{-- Nisab Config --}}
            <div class="glass-card mb-4">
                <div class="card-header bg-opacity-10 bg-primary">
                    <h6 class="mb-0 text-primary"><i class="bi bi-gear-fill me-2"></i>Nisab Configuration</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Nisab Basis</label>
                            <select name="nisab_basis" class="form-select form-select-sm">
                                <option value="silver">Silver (Standard)</option>
                                <option value="gold">Gold</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Gold Price/Gram (৳)</label>
                            <input type="number" name="gold_price_per_gram" class="form-control form-control-sm" value="8000" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Silver Price/Gram (৳)</label>
                            <input type="number" name="silver_price_per_gram" class="form-control form-control-sm" value="120" step="0.01">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Assets --}}
            <div class="glass-card mb-4 border-success border-opacity-25">
                <div class="card-header bg-success bg-opacity-10">
                    <h6 class="mb-0 text-success"><i class="bi bi-plus-circle me-2"></i>Zakatable Assets (সম্পদ)</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Cash at Home/Hand</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="cash" class="form-control asset-input" value="0" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cash in Bank Accounts</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="bank" class="form-control asset-input" value="0" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Value of Gold</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="gold_value" class="form-control asset-input" value="0" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Value of Silver</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="silver_value" class="form-control asset-input" value="0" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Investments & Shares</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="shares" class="form-control asset-input" value="0" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Business Inventory (Trade Goods)</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="trade_inventory" class="form-control asset-input" value="0" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Liabilities --}}
            <div class="glass-card mb-4 border-danger border-opacity-25">
                <div class="card-header bg-danger bg-opacity-10">
                    <h6 class="mb-0 text-danger"><i class="bi bi-dash-circle me-2"></i>Deductible Liabilities (দেনা)</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Outstanding Debts / Loans</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="debts" class="form-control liability-input" value="0" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Immediate Expenses Due</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="expenses_due" class="form-control liability-input" value="0" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-czm-primary w-100 py-3 fs-5 fw-bold shadow-lg">
                <i class="bi bi-calculator me-2"></i>Calculate Zakat
            </button>
        </form>
    </div>

    <div class="col-lg-5">
        @if(session('result'))
            @php $res = session('result'); @endphp
            <div class="glass-card border-{{ $res['is_eligible'] ? 'primary' : 'warning' }} border-2 shadow-lg sticky-top" style="top: 80px;">
                <div class="card-header text-center py-4 bg-{{ $res['is_eligible'] ? 'primary' : 'warning' }} bg-opacity-10">
                    <h4 class="mb-1 fw-bold text-{{ $res['is_eligible'] ? 'primary' : 'warning' }}">
                        {{ $res['is_eligible'] ? 'Zakat is Payable' : 'Zakat is Not Payable' }}
                    </h4>
                    <p class="mb-0 small text-muted">Based on your provided details</p>
                </div>
                <div class="card-body p-4">
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Assets:</span>
                        <span class="fw-semibold">৳{{ number_format($res['total_assets'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Liabilities:</span>
                        <span class="fw-semibold text-danger">- ৳{{ number_format($res['total_liabilities'], 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Net Zakatable Wealth:</span>
                        <span class="fw-bold fs-5">৳{{ number_format($res['net_zakatable'], 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4 small bg-light bg-opacity-10 p-2 rounded">
                        <span class="text-muted">Current Nisab Value:</span>
                        <span>৳{{ number_format($res['nisab_value'], 2) }}</span>
                    </div>

                    @if($res['is_eligible'])
                        <div class="text-center p-4 rounded-4" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(139, 92, 246, 0.1)); border: 1px solid rgba(16, 185, 129, 0.3);">
                            <p class="mb-1 text-muted fw-semibold">Your Zakat Due ({{ $res['rate'] }}%)</p>
                            <h2 class="mb-0 fw-bold" style="color: var(--czm-primary); font-size: 2.5rem;">
                                ৳{{ number_format($res['zakat_due'], 2) }}
                            </h2>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('collections.create') }}?amount={{ $res['zakat_due'] }}&fund_type=zakat" class="btn btn-czm-primary w-100">
                                <i class="bi bi-heart-fill me-2"></i>Pay Zakat Now
                            </a>
                        </div>
                    @else
                        <div class="text-center p-4 rounded-4 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                            <i class="bi bi-info-circle text-warning fs-1 d-block mb-2"></i>
                            <p class="mb-0 text-warning-emphasis fw-semibold">Your net wealth is below the Nisab threshold. You are not obligated to pay Zakat at this time.</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="glass-card h-100 d-flex flex-column align-items-center justify-content-center text-center p-5 text-muted sticky-top" style="top: 80px;">
                <i class="bi bi-calculator mb-3" style="font-size: 4rem; opacity: 0.2;"></i>
                <h5>Calculation Result</h5>
                <p class="small">Enter your assets and liabilities on the left and click calculate to see your Zakat obligation.</p>
            </div>
        @endif
    </div>
</div>
@endsection
