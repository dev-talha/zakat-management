@extends('layouts.public')

@section('title', 'Payment Successful | পেমেন্ট সফল')

@section('content')
<div style="background:linear-gradient(135deg,#f0fdf4,#fffbeb);min-height:70vh;display:flex;align-items:center;padding:80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div style="background:white;border-radius:24px;padding:48px;box-shadow:0 8px 48px rgba(0,0,0,0.08);border:1px solid #e5e7eb;text-align:center;">
                    <div style="width:80px;height:80px;background:linear-gradient(135deg,#10b981,#059669);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:2.5rem;color:white;box-shadow:0 8px 24px rgba(16,185,129,0.3);">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <h2 style="font-size:1.8rem;font-weight:800;color:#111827;margin-bottom:8px;">Jazakallah Khair!</h2>
                    <p style="font-size:1.1rem;color:#0d6e3f;margin-bottom:28px;">Your Zakat payment was successful.</p>

                    @if($collection)
                    <div style="background:#f8faf9;border:1px solid #e5e7eb;border-radius:16px;padding:24px;margin-bottom:28px;text-align:left;">
                        <h6 style="font-weight:700;color:#111827;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #e5e7eb;">Transaction Details</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Amount</span>
                            <strong style="color:#0d6e3f;font-size:1.1rem;">৳{{ number_format($collection->amount) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Receipt No</span>
                            <strong>{{ $collection->receipt_no }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Payment Method</span>
                            <span style="text-transform:uppercase;">{{ $collection->payment_gateway }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Transaction ID</span>
                            <span style="font-family:monospace;">{{ $collection->gateway_transaction_id }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Date & Time</span>
                            <span>{{ $collection->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">Transaction details not found.</div>
                    @endif

                    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:16px;margin-bottom:24px;font-size:0.875rem;color:#92400e;">
                        <i class="bi bi-envelope-fill me-2"></i> A confirmation receipt has been sent to your mobile/email.
                    </div>

                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ url('/') }}" class="btn-pub-outline" style="padding:14px 28px;">
                            <i class="bi bi-house"></i> Return Home
                        </a>
                        <a href="{{ route('donor.dashboard') }}" style="background:linear-gradient(135deg,#0d6e3f,#065f46);color:white;padding:14px 28px;border-radius:12px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
                            <i class="bi bi-person-fill"></i> Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
