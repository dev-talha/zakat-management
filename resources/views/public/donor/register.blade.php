@extends('layouts.public')

@section('title', 'Donor Registration | CZM Bangladesh')
@section('meta_description', 'Register as a Zakat donor on CZM Bangladesh. Calculate and pay your Zakat securely with full transparency.')

@push('styles')
<style>
    .reg-hero {
        background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 60%);
        padding: 60px 0 40px;
        border-bottom: 1px solid #e5e7eb;
    }
    .reg-hero h1 { font-size: clamp(1.8rem,3vw,2.4rem); font-weight: 800; color: #111827; }
    .reg-hero p  { color: #6b7280; font-size: 1rem; }
    .reg-steps {
        display: flex;
        gap: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 32px;
    }
    .reg-step {
        flex: 1;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-right: 1px solid #e5e7eb;
        position: relative;
    }
    .reg-step:last-child { border-right: none; }
    .reg-step.active { background: #f0fdf4; }
    .reg-step.active .step-n { background: #10b981; color: white; }
    .reg-step.done .step-n { background: #059669; color: white; }
    .reg-step .step-n {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #9ca3af;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem;
        flex-shrink: 0;
    }
    .reg-step .step-info .step-title { font-weight: 700; font-size: 0.85rem; color: #111827; }
    .reg-step .step-info .step-sub   { font-size: 0.75rem; color: #9ca3af; }
    .form-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 36px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .form-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        padding-bottom: 14px;
        border-bottom: 2px solid #f3f4f6;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-title i { color: #10b981; font-size: 1.1rem; }
    .benefit-card {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .benefit-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 14px;
        font-size: 0.875rem;
    }
    .benefit-item:last-child { margin-bottom: 0; }
    .benefit-item i { color: #10b981; flex-shrink: 0; margin-top: 2px; }
</style>
@endpush

@section('content')
<div class="reg-hero">
    <div class="container">
        <div class="d-flex align-items-center gap-2 mb-3" style="font-size:0.85rem;color:#9ca3af;">
            <a href="{{ url('/') }}" style="color:#10b981;text-decoration:none;">Home</a>
            <i class="bi bi-chevron-right"></i> Donor Registration
        </div>
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="section-badge mb-3"><i class="bi bi-heart-fill"></i> Register as Donor</div>
                <h1 class="mb-3">Pay Your Zakat Online<br><span style="color:#10b981;">Donor Registration</span></h1>
                <p>Join thousands of donors and fulfill your Zakat obligation with complete transparency and Shariah compliance.</p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <div class="d-flex align-items-center justify-content-lg-end gap-3 flex-wrap">
                    <div style="text-align:center;">
                        <div style="font-size:1.5rem;font-weight:800;color:#10b981;">{{ number_format($totalDonors ?? 24830) }}+</div>
                        <div style="font-size:0.78rem;color:#6b7280;">Active Donors</div>
                    </div>
                    <div style="width:1px;height:40px;background:#e5e7eb;"></div>
                    <div style="text-align:center;">
                        <div style="font-size:1.5rem;font-weight:800;color:#10b981;">৳{{ number_format($zakatDistributed ?? 124000000) }}</div>
                        <div style="font-size:0.78rem;color:#6b7280;">Distributed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="background:#f8faf9;padding:60px 0;min-height:70vh;">
    <div class="container">
        <div class="row g-5">
            {{-- Form --}}
            <div class="col-lg-8">
                {{-- Steps --}}
                <div class="reg-steps mb-4">
                    <div class="reg-step active">
                        <div class="step-n">1</div>
                        <div class="step-info">
                            <div class="step-title">Personal Info</div>
                            <div class="step-sub">Basic details</div>
                        </div>
                    </div>
                    <div class="reg-step">
                        <div class="step-n">2</div>
                        <div class="step-info">
                            <div class="step-title">Account Setup</div>
                            <div class="step-sub">Login credentials</div>
                        </div>
                    </div>
                    <div class="reg-step">
                        <div class="step-n">3</div>
                        <div class="step-info">
                            <div class="step-title">Verification</div>
                            <div class="step-sub">Identity & OTP</div>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                <div class="pub-alert pub-alert-error mb-4">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('donor.register.store') }}" id="donorRegForm">
                    @csrf

                    {{-- Personal Info --}}
                    <div class="form-section mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-person-fill"></i> Personal Information
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Full Name (English) *</label>
                                <input type="text" name="name" class="pub-form-control" value="{{ old('name') }}" placeholder="e.g. Mohammad Rahman" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Full Name (Bengali)</label>
                                <input type="text" name="name_bn" class="pub-form-control" value="{{ old('name_bn') }}" placeholder="e.g. Mohammed Rahman">
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Mobile Number *</label>
                                <div style="position:relative;">
                                    <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:0.875rem;">🇧🇩 +88</span>
                                    <input type="tel" name="mobile" class="pub-form-control" value="{{ old('mobile') }}" placeholder="01XXXXXXXXX" required style="padding-left:70px;">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">NID / Passport No.</label>
                                <input type="text" name="nid_no" class="pub-form-control" value="{{ old('nid_no') }}" placeholder="National ID or Passport">
                                <div class="pub-form-hint">Optional but recommended for identity verification</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Donor Type *</label>
                                <select name="donor_type" class="pub-form-control" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="individual" {{ old('donor_type') == 'individual' ? 'selected' : '' }}>Individual Person</option>
                                    <option value="corporate" {{ old('donor_type') == 'corporate' ? 'selected' : '' }}>Corporate / Company</option>
                                </select>
                                <div class="pub-form-hint">To donate without revealing your name, use “Keep my donations anonymous” below.</div>
                            </div>
                            <div class="col-12">
                                <x-location-picker />
                            </div>
                        </div>
                    </div>

                    {{-- Account Credentials --}}
                    <div class="form-section mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-lock-fill"></i> Account Credentials
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="pub-form-label">Email Address *</label>
                                <input type="email" name="email" class="pub-form-control" value="{{ old('email') }}" placeholder="your@email.com" required>
                                <div class="pub-form-hint">Your login email and receipt will be sent here</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Password *</label>
                                <div style="position:relative;">
                                    <input type="password" name="password" id="donorPass" class="pub-form-control" placeholder="Min. 8 characters" required style="padding-right:44px;">
                                    <button type="button" onclick="togglePass('donorPass')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#9ca3af;cursor:pointer;"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="pub-form-control" placeholder="Repeat password" required>
                            </div>
                        </div>
                    </div>

                    {{-- Zakat Preferences --}}
                    <div class="form-section mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-sliders"></i> Preferences (Optional)
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="pub-form-label">Preferred Payment Method</label>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach([['bkash','bKash','#e2136e'],['nagad','Nagad','#f97316'],['rocket','Rocket','#7c3aed'],['bank','Bank Transfer','#0d6e3f']] as $pm)
                                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.875rem;font-weight:600;transition:all 0.2s;" id="pmLabel_{{ $pm[0] }}"
                                        onmouseover="this.style.borderColor='{{ $pm[2] }}'" onmouseout="highlightChecked()">
                                        <input type="radio" name="preferred_payment" value="{{ $pm[0] }}" onchange="highlightChecked()" {{ old('preferred_payment') == $pm[0] ? 'checked' : '' }}>
                                        <span style="width:8px;height:8px;border-radius:50%;background:{{ $pm[2] }};display:inline-block;"></span>
                                        {{ $pm[1] }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12">
                                <div style="display:flex;align-items:flex-start;gap:10px;">
                                    <input type="checkbox" name="anonymous_default" id="anonCheck" value="1" style="margin-top:3px;accent-color:#10b981;" {{ old('anonymous_default') ? 'checked' : '' }}>
                                    <label for="anonCheck" style="font-size:0.875rem;color:#374151;cursor:pointer;">
                                        <strong>Keep my donations anonymous</strong><br>
                                        <span style="color:#9ca3af;font-size:0.8rem;">Your name will not be shown on public records or beneficiary receipts.</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Terms & Submit --}}
                    <div class="form-section">
                        <div class="mb-3" style="display:flex;align-items:flex-start;gap:10px;">
                            <input type="checkbox" id="terms" required style="margin-top:3px;accent-color:#10b981;">
                            <label for="terms" style="font-size:0.875rem;color:#374151;cursor:pointer;">
                                I agree to the <a href="#" style="color:#10b981;font-weight:600;">Terms of Service</a> and <a href="#" style="color:#10b981;font-weight:600;">Privacy Policy</a>. I confirm that the information provided is accurate and I am fulfilling my Zakat obligation in accordance with Islamic law.
                            </label>
                        </div>
                        <button type="submit" class="btn-pub-primary w-100" style="justify-content:center;padding:16px;font-size:1rem;">
                            <i class="bi bi-person-plus-fill"></i> Create Donor Account
                        </button>
                        <p style="text-align:center;font-size:0.875rem;color:#9ca3af;margin-top:16px;">
                            Already have an account? <a href="{{ url('/login') }}" style="color:#10b981;font-weight:600;">Sign in here</a>
                        </p>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="benefit-card">
                    <h5 style="font-weight:700;color:#166534;margin-bottom:16px;"><i class="bi bi-stars me-2"></i>Why Register?</h5>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i><div><strong>Shariah-compliant process</strong> — certified by our Islamic advisory board.</div></div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i><div><strong>Real-time tracking</strong> — see exactly when your Zakat is disbursed.</div></div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i><div><strong>Digital receipts</strong> — official documents for your records.</div></div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i><div><strong>Tax certificates</strong> — valid documentation for income tax purposes.</div></div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i><div><strong>Donor dashboard</strong> — manage all your donations in one place.</div></div>
                </div>

                <div class="pub-card">
                    <h6 style="font-weight:700;margin-bottom:16px;"><i class="bi bi-shield-lock me-2" style="color:#10b981;"></i>Security & Privacy</h6>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-2" style="font-size:0.82rem;color:#4b5563;">
                            <i class="bi bi-lock-fill" style="color:#10b981;"></i> 256-bit SSL Encryption
                        </div>
                        <div class="d-flex align-items-center gap-2" style="font-size:0.82rem;color:#4b5563;">
                            <i class="bi bi-eye-slash-fill" style="color:#10b981;"></i> Optional Anonymous Giving
                        </div>
                        <div class="d-flex align-items-center gap-2" style="font-size:0.82rem;color:#4b5563;">
                            <i class="bi bi-database-lock-fill" style="color:#10b981;"></i> PDPA Compliant Data Storage
                        </div>
                        <div class="d-flex align-items-center gap-2" style="font-size:0.82rem;color:#4b5563;">
                            <i class="bi bi-patch-check-fill" style="color:#10b981;"></i> Gov. of Bangladesh Registered
                        </div>
                    </div>
                </div>

                <div class="pub-card mt-4" style="background:linear-gradient(135deg,#0d6e3f,#10b981);border:none;">
                    <h6 style="color:white;font-weight:700;margin-bottom:8px;"><i class="bi bi-calculator me-2"></i>Quick Zakat Estimate</h6>
                    <p style="color:rgba(255,255,255,0.7);font-size:0.82rem;margin-bottom:16px;">If you have ৳5 lakh in savings, your Zakat due is approximately:</p>
                    <div style="font-size:2rem;font-weight:800;color:white;">৳ ১২,৫০০</div>
                    <div style="color:rgba(255,255,255,0.7);font-size:0.78rem;">2.5% of ৳5,00,000</div>
                    <a href="{{ url('/#calculator') }}" style="display:block;margin-top:16px;background:white;color:#0d6e3f;padding:8px 16px;border-radius:8px;font-weight:700;font-size:0.82rem;text-decoration:none;text-align:center;">Calculate Your Exact Amount →</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePass(id) {
        const inp = document.getElementById(id);
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }
    function highlightChecked() {
        document.querySelectorAll('[id^="pmLabel_"]').forEach(lbl => {
            const radio = lbl.querySelector('input[type="radio"]');
            lbl.style.borderColor = radio.checked ? '#10b981' : '#e5e7eb';
            lbl.style.background  = radio.checked ? '#f0fdf4' : 'white';
        });
    }

    function applyPageTranslations(lang) {}
</script>
@endpush
