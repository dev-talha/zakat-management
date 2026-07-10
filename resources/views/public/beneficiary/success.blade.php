@extends('layouts.public')

@section('title', 'Application Submitted | আবেদন জমা হয়েছে')

@section('content')
<div style="background:linear-gradient(135deg,#f0fdf4,#fffbeb);min-height:70vh;display:flex;align-items:center;padding:80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div style="background:white;border-radius:24px;padding:48px;box-shadow:0 8px 48px rgba(0,0,0,0.08);border:1px solid #e5e7eb;text-align:center;">
                    <div style="width:80px;height:80px;background:linear-gradient(135deg,#10b981,#059669);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:2rem;color:white;box-shadow:0 8px 24px rgba(16,185,129,0.3);">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <h2 style="font-size:1.6rem;font-weight:800;color:#111827;margin-bottom:8px;">Application Submitted!</h2>
                    <p style="font-size:1rem;color:#6b7280;margin-bottom:28px;">আবেদন সফলভাবে জমা হয়েছে</p>

                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:24px;margin-bottom:28px;">
                        <div style="font-size:0.82rem;color:#6b7280;margin-bottom:6px;">Application Number</div>
                        <div style="font-size:1.6rem;font-weight:800;color:#0d6e3f;letter-spacing:0.05em;">{{ $applicationNo }}</div>
                        <div style="font-size:0.78rem;color:#9ca3af;margin-top:4px;">Save this number to track your application</div>
                    </div>

                    <div style="text-align:left;margin-bottom:28px;">
                        <h6 style="font-weight:700;color:#111827;margin-bottom:12px;">What happens next?</h6>
                        @foreach([
                            ['bi-robot','AI Screening','Your application is being verified by our automated system.','#8b5cf6'],
                            ['bi-person-check','Field Verification','A volunteer will contact you within 3–5 business days.','#f59e0b'],
                            ['bi-shield-check','Shariah Review','Board reviews and approves your eligibility.','#3b82f6'],
                            ['bi-send-check','Disbursement','Funds sent directly to your mobile banking account.','#10b981'],
                        ] as $step)
                        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;">
                            <div style="width:36px;height:36px;background:{{ $step[3] }}15;border-radius:10px;display:flex;align-items:center;justify-content:center;color:{{ $step[3] }};flex-shrink:0;font-size:1rem;">
                                <i class="bi {{ $step[0] }}"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:0.875rem;color:#111827;">{{ $step[1] }}</div>
                                <div style="font-size:0.78rem;color:#9ca3af;">{{ $step[2] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:16px;margin-bottom:24px;font-size:0.82rem;color:#92400e;display:flex;align-items:flex-start;gap:8px;">
                        <i class="bi bi-info-circle-fill" style="flex-shrink:0;margin-top:2px;"></i>
                        <div>Save your Application Number <strong>{{ $applicationNo }}</strong> for tracking. You will receive an SMS update on your registered mobile number when your status changes.</div>
                    </div>

                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ url('/') }}" class="btn-pub-outline" style="padding:12px 24px;">
                            <i class="bi bi-house"></i> Back to Home
                        </a>
                        <a href="{{ url('/apply') }}" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:white;padding:12px 24px;border-radius:8px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                            <i class="bi bi-plus"></i> New Application
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
