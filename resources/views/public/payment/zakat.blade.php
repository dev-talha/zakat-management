@extends('layouts.public')

@section('title', 'Pay Zakat | যাকাত প্রদান করুন')

@push('styles')
<style>
    .payment-hero { background: linear-gradient(135deg, #eff6ff 0%, #ffffff 60%); padding: 60px 0 40px; border-bottom: 1px solid #e5e7eb; }
    .payment-section { background: white; border: 1px solid #e5e7eb; border-radius: 20px; padding: 36px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); margin-bottom: 24px; }
    .gateway-card { border: 2px solid #e5e7eb; border-radius: 12px; padding: 16px; cursor: pointer; text-align: center; transition: all 0.2s; position: relative; }
    .gateway-card:hover { border-color: #3b82f6; background: #eff6ff; }
    .gateway-card.selected { border-color: #3b82f6; background: #eff6ff; }
    .gateway-card img { height: 40px; object-fit: contain; margin-bottom: 10px; }
    .gateway-card span { display: block; font-weight: 600; font-size: 0.875rem; color: #374151; }
    .gateway-card .check-icon { position: absolute; top: 10px; right: 10px; color: #3b82f6; font-size: 1.2rem; display: none; }
    .gateway-card.selected .check-icon { display: block; }
    .amount-input-group { position: relative; }
    .amount-input-group span { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); font-size: 1.5rem; font-weight: 700; color: #111827; }
    .amount-input-group input { font-size: 2rem; font-weight: 800; padding-left: 50px; height: 80px; text-align: center; color: #0d6e3f; }
    .quick-amount { padding: 8px 16px; border: 1.5px solid #e5e7eb; border-radius: 20px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .quick-amount:hover { border-color: #0d6e3f; color: #0d6e3f; background: #f0fdf4; }
</style>
@endpush

@section('content')
<div class="payment-hero">
    <div class="container text-center">
        <h1 style="font-weight:800;color:#111827;margin-bottom:12px;">Pay Zakat Online</h1>
        <p style="color:#6b7280;font-size:1.1rem;max-width:600px;margin:0 auto;">Secure, Shariah-compliant Zakat distribution to those who need it most.</p>
        
        @if($refCode && $refName)
        <div style="display:inline-flex;align-items:center;gap:10px;background:#fef3c7;border:1px solid #fde68a;padding:12px 20px;border-radius:30px;margin-top:20px;color:#92400e;font-size:0.9rem;font-weight:600;">
            <i class="bi bi-link-45deg fs-5"></i> Referred by: {{ $refName }} 
            <span class="badge bg-warning text-dark ms-2">{{ strtoupper($refType) }}</span>
        </div>
        @endif
    </div>
</div>

<div style="background:#f8faf9;padding:60px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                @if($errors->any())
                <div class="pub-alert pub-alert-error mb-4">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div><strong>Please fix:</strong><ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                </div>
                @endif

                <form method="POST" action="{{ route('payment.process') }}">
                    @csrf
                    <input type="hidden" name="referral_code" value="{{ $refCode }}">
                    <input type="hidden" name="referral_type" value="{{ $refType }}">

                    {{-- Amount --}}
                    <div class="payment-section">
                        <h4 style="font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:10px;"><i class="bi bi-wallet2 text-primary"></i> 1. Enter Amount</h4>
                        <div class="amount-input-group mb-4">
                            <span>৳</span>
                            <input type="number" name="amount" id="zakatAmount" class="form-control" value="{{ old('amount', $amount ?? '') }}" required min="100" placeholder="0">
                        </div>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @foreach([1000, 5000, 10000, 50000] as $amt)
                            <div class="quick-amount" onclick="document.getElementById('zakatAmount').value = {{ $amt }}">৳{{ number_format($amt) }}</div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="payment-section">
                        <h4 style="font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:10px;"><i class="bi bi-credit-card-2-front text-primary"></i> 2. Select Payment Method</h4>
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <div class="gateway-card selected" onclick="selectGateway('bkash', this)">
                                    <i class="bi bi-check-circle-fill check-icon"></i>
                                    <img src="{{ asset('bkash.svg') }}" alt="bKash" style="height:40px; margin-bottom:10px;">
                                    <span>bKash</span>
                                    <input type="radio" name="payment_gateway" value="bkash" style="display:none;" checked>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payer Info --}}
                    <div class="payment-section">
                        <h4 style="font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:10px;"><i class="bi bi-person text-primary"></i> 3. Your Details</h4>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-bold">Full Name *</label>
                                <input type="text" name="payer_name" class="form-control form-control-lg" required value="{{ old('payer_name', auth()->user()->name ?? '') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-bold">Mobile Number *</label>
                                <input type="tel" name="payer_mobile" class="form-control form-control-lg" required placeholder="01XXXXXXXXX" value="{{ old('payer_mobile', auth()->user()->mobile ?? '') }}">
                            </div>
                            <div class="col-12">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="anonymous" value="1" id="anonCheck">
                                    <label class="form-check-label" for="anonCheck">
                                        Make this donation anonymous (Hide name from public leaderboards/records)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" style="background:linear-gradient(135deg,#0d6e3f,#065f46);color:white;border:none;width:100%;padding:20px;border-radius:16px;font-weight:800;font-size:1.2rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;box-shadow:0 8px 24px rgba(13,110,63,0.3);">
                        <i class="bi bi-shield-lock-fill"></i> Proceed to Secure Payment
                    </button>
                    <p class="text-center mt-3 text-muted small"><i class="bi bi-lock-fill"></i> Your payment is secure and encrypted.</p>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectGateway(value, el) {
        document.querySelectorAll('.gateway-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input[type="radio"]').checked = true;
    }

    // Auto-select first if none selected
    if(!document.querySelector('input[name="payment_gateway"]:checked')) {
        const firstGateway = document.querySelector('.gateway-card');
        if(firstGateway) selectGateway('bkash', firstGateway);
    }
</script>
@endpush
