@extends('layouts.public')

@section('title', 'Track Application Status | আবেদনের অবস্থা জানুন — CZM Bangladesh')
@section('meta_description', 'Track your CZM beneficiary application status online using your application code or mobile number with OTP.')

@push('styles')
<style>
    .track-hero {
        background: linear-gradient(135deg, #0a4f2e 0%, #065f46 50%, #0d6e3f 100%);
        padding: 80px 0 60px;
        color: white;
        text-align: center;
        position: relative;
    }
    .track-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        padding: 30px;
        margin-top: -40px;
        position: relative;
        z-index: 10;
    }
    .nav-tabs-custom {
        display: flex;
        border-bottom: 2px solid #f3f4f6;
        margin-bottom: 30px;
        gap: 15px;
    }
    .nav-tab-btn {
        background: none;
        border: none;
        padding: 12px 20px;
        font-weight: 700;
        color: #6b7280;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        font-size: 0.95rem;
    }
    .nav-tab-btn.active {
        color: #0d6e3f;
        border-bottom-color: #0d6e3f;
    }
    .form-group-custom {
        margin-bottom: 20px;
    }
    .form-group-custom label {
        display: block;
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.875rem;
    }
    .form-control-custom {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #d1d5db;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    .form-control-custom:focus {
        outline: none;
        border-color: #0d6e3f;
        box-shadow: 0 0 0 3px rgba(13, 110, 63, 0.15);
    }
    .btn-track {
        background: #0d6e3f;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 700;
        width: 100%;
        transition: all 0.3s;
        cursor: pointer;
    }
    .btn-track:hover {
        background: #0a5a34;
        box-shadow: 0 4px 12px rgba(13, 110, 63, 0.2);
    }
    .timeline-container {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #f3f4f6;
    }
    .timeline-title-area {
        font-weight: 800;
        color: #111827;
        margin-bottom: 20px;
        font-size: 1.1rem;
    }
    .track-timeline {
        position: relative;
        padding-left: 35px;
        margin-top: 20px;
    }
    .track-timeline::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: #e5e7eb;
    }
    .timeline-step {
        position: relative;
        margin-bottom: 25px;
    }
    .timeline-step:last-child {
        margin-bottom: 0;
    }
    .timeline-step-icon {
        position: absolute;
        left: -35px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        z-index: 2;
    }
    .timeline-step.completed .timeline-step-icon {
        background: #10b981;
        color: white;
    }
    .timeline-step.active .timeline-step-icon {
        background: #3b82f6;
        color: white;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }
    .timeline-step.rejected .timeline-step-icon {
        background: #ef4444;
        color: white;
    }
    .timeline-step-content {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 18px;
    }
    .timeline-step-title {
        font-weight: 700;
        color: #111827;
        font-size: 0.95rem;
    }
    .timeline-step-desc {
        font-size: 0.82rem;
        color: #6b7280;
        margin-top: 4px;
    }
    .timeline-step-date {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 6px;
    }
    .alert-custom {
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.875rem;
        margin-bottom: 20px;
        display: none;
    }
    .alert-custom-error {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fee2e2;
    }
    .alert-custom-success {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #dcfce7;
    }
    .app-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    .app-card:hover {
        border-color: #0d6e3f;
        box-shadow: 0 4px 12px rgba(13,110,63,0.05);
    }
</style>
@endpush

@section('content')
<section class="track-hero">
    <div class="container">
        <h1 id="trackTitle" style="font-weight:900;">Track Application Status</h1>
        <p id="trackSub" class="mx-auto" style="max-width:600px;opacity:0.85;font-size:0.95rem;margin-top:10px;">Check your CZM application stage using your unique code or mobile number with OTP.</p>
    </div>
</section>

<div class="container" style="padding-bottom:80px;">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="track-card">
                
                {{-- Tabs --}}
                <div class="nav-tabs-custom">
                    <button id="tabCodeTitle" class="nav-tab-btn active" onclick="switchTab('code')">Application Code</button>
                    <button id="tabMobileTitle" class="nav-tab-btn" onclick="switchTab('mobile')">Mobile Number</button>
                </div>

                {{-- Messages --}}
                @if(session('error'))
                    <div class="alert alert-danger" style="border-radius:10px; margin-bottom: 20px;">
                        {{ session('error') }}
                    </div>
                @endif
                <div id="alertMessage" class="alert-custom"></div>

                {{-- Tab 1: Code Tracking --}}
                <div id="paneCode">
                    <form action="{{ route('public.track.code') }}" method="POST" id="formCodeSearch">
                        @csrf
                        <div class="form-group-custom">
                            <label id="labelCode" for="application_no">Enter Application Code</label>
                            <input type="text" name="application_no" id="application_no" class="form-control-custom" placeholder="e.g. BEN-2026-000002" required value="{{ $application_no ?? '' }}">
                        </div>
                        <button type="submit" id="btnSearch" class="btn-track">Track Status</button>
                    </form>

                    {{-- Search Result Timeline --}}
                    @if(isset($searchResult))
                        <div class="timeline-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span style="font-size:0.82rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Application Info</span>
                                <span class="badge" style="background:#0d6e3f;color:white;font-size:0.75rem;padding:6px 12px;border-radius:20px;">{{ strtoupper($searchResult['zakat_category']) }}</span>
                            </div>
                            <div style="background:#f9fafb;border-radius:10px;padding:12px 16px;font-size:0.875rem;margin-bottom:20px;">
                                <div style="display:flex;justify-content:between;margin-bottom:6px;">
                                    <span style="color:#6b7280;" class="lblCode">Code:</span>
                                    <strong style="margin-left:auto;color:#111827;">{{ $searchResult['application_no'] }}</strong>
                                </div>
                                <div style="display:flex;justify-content:between;">
                                    <span style="color:#6b7280;" class="lblName">Applicant:</span>
                                    <strong style="margin-left:auto;color:#111827;">{{ $searchResult['primary_person_name'] }}</strong>
                                </div>
                            </div>

                            <div class="timeline-title-area" id="statusTimelineTitle">Timeline & Progress</div>
                            
                            <div class="track-timeline">
                                {{-- Step 1: Submitted --}}
                                <div class="timeline-step completed">
                                    <div class="timeline-step-icon"><i class="bi bi-check-lg"></i></div>
                                    <div class="timeline-step-content">
                                        <div class="timeline-step-title stepTitleSubmitted">Submitted</div>
                                        <div class="timeline-step-desc stepDescSubmitted">Application successfully received.</div>
                                        <div class="timeline-step-date">{{ $searchResult['created_at'] }}</div>
                                    </div>
                                </div>

                                {{-- Step 2: Verification --}}
                                @php
                                    $step2Active = in_array($searchResult['stage'], ['assessment', 'field_verification', 'supervisor_review']);
                                    $step2Completed = !in_array($searchResult['stage'], ['assessment', 'field_verification', 'supervisor_review']) && $searchResult['stage'] !== 'rejected';
                                    $isRejected = $searchResult['stage'] === 'rejected' || $searchResult['status'] === 'rejected';
                                @endphp
                                <div class="timeline-step {{ $step2Active ? 'active' : ($step2Completed ? 'completed' : '') }}">
                                    <div class="timeline-step-icon">
                                        @if($step2Completed)
                                            <i class="bi bi-check-lg"></i>
                                        @else
                                            <i class="bi bi-search"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-step-content">
                                        <div class="timeline-step-title stepTitleVerify">Verification & Review</div>
                                        <div class="timeline-step-desc stepDescVerify">Field visit and eligibility score assessment.</div>
                                        @if($step2Active)
                                            <div class="timeline-step-date stepDateActive">In progress</div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Step 3: Board Approval --}}
                                @php
                                    $step3Active = in_array($searchResult['stage'], ['shariah_review', 'finance_review', 'approved']);
                                    $step3Completed = in_array($searchResult['stage'], ['disbursement', 'follow_up', 'closed']);
                                @endphp
                                <div class="timeline-step {{ $step3Active ? 'active' : ($step3Completed ? 'completed' : '') }}">
                                    <div class="timeline-step-icon">
                                        @if($step3Completed)
                                            <i class="bi bi-check-lg"></i>
                                        @else
                                            <i class="bi bi-shield-check"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-step-content">
                                        <div class="timeline-step-title stepTitleApproval">Board Approval</div>
                                        <div class="timeline-step-desc stepDescApproval">Final evaluation and fund allocation approval.</div>
                                        @if($step3Active)
                                            <div class="timeline-step-date stepDateActive">In progress</div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Step 4: Disbursement --}}
                                @php
                                    $step4Active = in_array($searchResult['stage'], ['disbursement', 'follow_up']);
                                    $step4Completed = $searchResult['stage'] === 'closed';
                                @endphp
                                <div class="timeline-step {{ $step4Active ? 'active' : ($step4Completed ? 'completed' : '') }} {{ $isRejected ? 'rejected' : '' }}">
                                    <div class="timeline-step-icon">
                                        @if($isRejected)
                                            <i class="bi bi-x-lg"></i>
                                        @elseif($step4Completed)
                                            <i class="bi bi-check-lg"></i>
                                        @else
                                            <i class="bi bi-cash"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-step-content">
                                        @if($isRejected)
                                            <div class="timeline-step-title text-danger stepTitleRejected">Application Rejected</div>
                                            <div class="timeline-step-desc stepDescRejected">The application does not meet CZM Zakat criteria.</div>
                                        @else
                                            <div class="timeline-step-title stepTitleDisburse">Disbursement</div>
                                            <div class="timeline-step-desc stepDescDisburse">Transfer of funds to registered mobile banking account.</div>
                                            @if($step4Active)
                                                <div class="timeline-step-date stepDateDisbursing">Processing payout</div>
                                            @elseif($step4Completed)
                                                <div class="timeline-step-date stepDateDisbursed">Completed</div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Tab 2: Mobile Tracking --}}
                <div id="paneMobile" style="display:none;">
                    
                    {{-- OTP Step 1: Send OTP --}}
                    <div id="otpStep1">
                        <div class="form-group-custom">
                            <label id="labelMobile" for="mobile">Enter Mobile Number</label>
                            <input type="text" id="mobile" class="form-control-custom" placeholder="e.g. 01712345678">
                        </div>
                        <button type="button" id="btnSendOtp" class="btn-track" onclick="requestOtp()">Send OTP Code</button>
                    </div>

                    {{-- OTP Step 2: Verify OTP --}}
                    <div id="otpStep2" style="display:none;">
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px 16px;font-size:0.82rem;color:#1e3a8a;margin-bottom:20px;" id="otpNotice">
                            OTP sent to your number. Enter default OTP <strong>1234</strong> for testing.
                        </div>
                        <div class="form-group-custom">
                            <label id="labelOtp" for="otp">Enter 4-Digit OTP</label>
                            <input type="text" id="otp" class="form-control-custom" placeholder="Enter OTP code" maxlength="6">
                        </div>
                        <button type="button" id="btnVerifyOtp" class="btn-track" onclick="verifyOtp()">Verify & Track</button>
                        <button type="button" class="btn btn-link w-100 text-muted mt-2 btnBackMobile" style="font-size:0.82rem;" onclick="showOtpStep1()">Back / Change Mobile</button>
                    </div>

                    {{-- Mobile Verification Results --}}
                    @if(isset($mobileResults))
                        <div class="timeline-container" style="display:block;">
                            <div class="timeline-title-area mb-3 d-flex align-items-center justify-content-between">
                                <span id="mobileResultsTitle">Your Applications</span>
                                <span class="badge bg-secondary" style="font-size:0.8rem;">{{ $mobile }}</span>
                            </div>

                            @if(count($mobileResults) > 0)
                                @foreach($mobileResults as $app)
                                    <div class="app-card">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 style="font-weight:700;margin:0;color:#111827;">{{ $app['application_no'] }}</h6>
                                                <span style="font-size:0.75rem;color:#6b7280;">{{ ucfirst($app['zakat_category']) }} • Submitted: {{ $app['created_at'] }}</span>
                                            </div>
                                            <span class="badge" style="background:{{ $app['status'] === 'approved' ? '#10b981' : ($app['status'] === 'rejected' ? '#ef4444' : '#f59e0b') }}; color:white; font-size:0.7rem; padding:4px 8px; border-radius:10px;">
                                                {{ strtoupper($app['status']) }}
                                            </span>
                                        </div>
                                        
                                        <div style="margin-top:12px; font-size:0.82rem; padding:8px 12px; background:white; border:1px solid #f3f4f6; border-radius:6px; color:#374151;">
                                            <strong>Stage:</strong> 
                                            @if($app['stage'] === 'rejected')
                                                <span class="text-danger font-weight-bold">Rejected</span>
                                            @elseif($app['stage'] === 'closed')
                                                <span class="text-success font-weight-bold">Disbursed (Closed)</span>
                                            @elseif($app['stage'] === 'disbursement')
                                                <span class="text-success font-weight-bold">Disbursement in Progress</span>
                                            @elseif(in_array($app['stage'], ['shariah_review', 'finance_review', 'approved']))
                                                <span class="text-primary font-weight-bold">Approved, Awaiting Payout</span>
                                            @else
                                                <span class="text-warning font-weight-bold">Under Review / Verification</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div style="text-align:center;padding:30px;color:#9ca3af;" id="noAppFoundText">
                                    No applications found matching this mobile number.
                                </div>
                            @endif
                            <a href="{{ route('public.track') }}" class="btn btn-outline-secondary w-100 mt-3 btnExitMobile">Exit Status View</a>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeTab = 'code';

    // Auto-activate mobile tab if results or session data is active
    @if(isset($mobileResults))
        activeTab = 'mobile';
        document.getElementById('tabCodeTitle').classList.remove('active');
        document.getElementById('tabMobileTitle').classList.add('active');
        document.getElementById('paneCode').style.display = 'none';
        document.getElementById('paneMobile').style.display = 'block';
        document.getElementById('otpStep1').style.display = 'none';
        document.getElementById('otpStep2').style.display = 'none';
    @endif

    function switchTab(tab) {
        activeTab = tab;
        document.getElementById('tabCodeTitle').classList.toggle('active', tab === 'code');
        document.getElementById('tabMobileTitle').classList.toggle('active', tab === 'mobile');
        
        document.getElementById('paneCode').style.display = tab === 'code' ? 'block' : 'none';
        document.getElementById('paneMobile').style.display = tab === 'mobile' ? 'block' : 'none';
        
        // Hide messages
        hideAlert();
    }

    function showAlert(msg, type = 'error') {
        const alertBox = document.getElementById('alertMessage');
        alertBox.textContent = msg;
        alertBox.className = 'alert-custom ' + (type === 'error' ? 'alert-custom-error' : 'alert-custom-success');
        alertBox.style.display = 'block';
    }

    function hideAlert() {
        document.getElementById('alertMessage').style.display = 'none';
    }

    function requestOtp() {
        const mobile = document.getElementById('mobile').value.trim();
        if (!mobile) {
            showAlert(currentLang === 'en' ? 'Please enter your mobile number.' : 'অনুগ্রহ করে আপনার মোবাইল নম্বরটি লিখুন।');
            return;
        }

        const btn = document.getElementById('btnSendOtp');
        btn.disabled = true;
        btn.textContent = currentLang === 'en' ? 'Sending...' : 'পাঠানো হচ্ছে...';

        fetch("{{ route('public.otp.request') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ mobile: mobile })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            btn.disabled = false;
            btn.textContent = currentLang === 'en' ? 'Send OTP Code' : 'ওটিপি কোড পাঠান';

            if (res.status === 200) {
                showAlert(res.body.message, 'success');
                document.getElementById('otpStep1').style.display = 'none';
                document.getElementById('otpStep2').style.display = 'block';
            } else {
                showAlert(res.body.message || (currentLang === 'en' ? 'Error sending OTP.' : 'ওটিপি পাঠাতে সমস্যা হয়েছে।'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.textContent = currentLang === 'en' ? 'Send OTP Code' : 'ওটিপি কোড পাঠান';
            showAlert(currentLang === 'en' ? 'Network error. Please try again.' : 'নেটওয়ার্ক সমস্যা। আবার চেষ্টা করুন।');
        });
    }

    function verifyOtp() {
        const mobile = document.getElementById('mobile').value.trim();
        const otp = document.getElementById('otp').value.trim();
        
        if (!otp) {
            showAlert(currentLang === 'en' ? 'Please enter the OTP code.' : 'অনুগ্রহ করে ওটিপি কোডটি লিখুন।');
            return;
        }

        const btn = document.getElementById('btnVerifyOtp');
        btn.disabled = true;
        btn.textContent = currentLang === 'en' ? 'Verifying...' : 'যাচাই করা হচ্ছে...';

        fetch("{{ route('public.otp.verify') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ mobile: mobile, otp: otp })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            btn.disabled = false;
            btn.textContent = currentLang === 'en' ? 'Verify & Track' : 'ওটিপি যাচাই করুন';

            if (res.status === 200) {
                // Redirect to results page
                window.location.href = res.body.redirect;
            } else {
                showAlert(res.body.message || (currentLang === 'en' ? 'OTP verification failed.' : 'ওটিপি যাচাইকরণ ব্যর্থ হয়েছে।'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.textContent = currentLang === 'en' ? 'Verify & Track' : 'ওটিপি যাচাই করুন';
            showAlert(currentLang === 'en' ? 'Network error. Please try again.' : 'নেটওয়ার্ক সমস্যা। আবার চেষ্টা করুন।');
        });
    }

    function showOtpStep1() {
        hideAlert();
        document.getElementById('otpStep1').style.display = 'block';
        document.getElementById('otpStep2').style.display = 'none';
        document.getElementById('otp').value = '';
    }

    // Bilateral Translations implementation
    const pageTranslations = {
        trackTitle: { en: 'Track Application Status', bn: 'আবেদনের অবস্থা জানুন' },
        trackSub: { en: 'Check your CZM application stage using your unique code or mobile number with OTP.', bn: 'ইউনিক কোড অথবা ওটিপি সহ মোবাইল নম্বর ব্যবহার করে আপনার সিজেডএম আবেদনের বর্তমান অবস্থা দেখুন।' },
        tabCodeTitle: { en: 'Application Code', bn: 'আবেদন কোড' },
        tabMobileTitle: { en: 'Mobile Number', bn: 'মোবাইল নম্বর' },
        labelCode: { en: 'Enter Application Code', bn: 'আবেদন কোড লিখুন' },
        btnSearch: { en: 'Track Status', bn: 'অবস্থা অনুসন্ধান করুন' },
        labelMobile: { en: 'Enter Mobile Number', bn: 'মোবাইল নম্বর লিখুন' },
        btnSendOtp: { en: 'Send OTP Code', bn: 'ওটিপি কোড পাঠান' },
        labelOtp: { en: 'Enter 4-Digit OTP', bn: '৪-ডিজিটের ওটিপি লিখুন' },
        btnVerifyOtp: { en: 'Verify & Track', bn: 'ওটিপি যাচাই করুন' },
        mobileResultsTitle: { en: 'Your Applications', bn: 'আপনার আবেদনসমূহ' }
    };

    function applyPageTranslations(lang) {
        currentLang = lang;
        // Text strings
        Object.keys(pageTranslations).forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = pageTranslations[id][lang];
        });

        // Placeholders and inputs
        const codeInput = document.getElementById('application_no');
        if (codeInput) {
            codeInput.placeholder = lang === 'en' ? 'e.g. BEN-2026-000002' : 'যেমন: BEN-2026-000002';
        }
        const mobileInput = document.getElementById('mobile');
        if (mobileInput) {
            mobileInput.placeholder = lang === 'en' ? 'e.g. 01712345678' : 'যেমন: ০১৭১২৩৪৫৬৭৮';
        }
        const otpInput = document.getElementById('otp');
        if (otpInput) {
            otpInput.placeholder = lang === 'en' ? 'Enter OTP code' : 'ওটিপি কোডটি লিখুন';
        }

        // Inline labels and titles on results
        const lblCodes = document.querySelectorAll('.lblCode');
        lblCodes.forEach(el => el.textContent = lang === 'en' ? 'Code:' : 'কোড:');

        const lblNames = document.querySelectorAll('.lblName');
        lblNames.forEach(el => el.textContent = lang === 'en' ? 'Applicant:' : 'আবেদনকারী:');

        const statusTimelineTitles = document.querySelectorAll('#statusTimelineTitle');
        statusTimelineTitles.forEach(el => el.textContent = lang === 'en' ? 'Timeline & Progress' : 'আবেদনের ধাপ ও অগ্রগতি');

        // Timeline Step Titles & Descs
        const stepTitlesSubmitted = document.querySelectorAll('.stepTitleSubmitted');
        stepTitlesSubmitted.forEach(el => el.textContent = lang === 'en' ? 'Submitted' : 'দাখিলকৃত');
        const stepDescsSubmitted = document.querySelectorAll('.stepDescSubmitted');
        stepDescsSubmitted.forEach(el => el.textContent = lang === 'en' ? 'Application successfully received.' : 'আবেদন সফলভাবে গ্রহণ করা হয়েছে।');

        const stepTitlesVerify = document.querySelectorAll('.stepTitleVerify');
        stepTitlesVerify.forEach(el => el.textContent = lang === 'en' ? 'Verification & Review' : 'যাচাই ও মূল্যায়ন');
        const stepDescsVerify = document.querySelectorAll('.stepDescVerify');
        stepDescsVerify.forEach(el => el.textContent = lang === 'en' ? 'Field visit and eligibility score assessment.' : 'মাঠ পর্যায়ের তথ্য যাচাই ও যোগ্যতা মূল্যায়ন।');

        const stepTitlesApproval = document.querySelectorAll('.stepTitleApproval');
        stepTitlesApproval.forEach(el => el.textContent = lang === 'en' ? 'Board Approval' : 'বোর্ড অনুমোদন');
        const stepDescsApproval = document.querySelectorAll('.stepDescApproval');
        stepDescsApproval.forEach(el => el.textContent = lang === 'en' ? 'Final evaluation and fund allocation approval.' : 'চূড়ান্ত মূল্যায়ন এবং অর্থ বরাদ্দ অনুমোদন।');

        const stepTitlesDisburse = document.querySelectorAll('.stepTitleDisburse');
        stepTitlesDisburse.forEach(el => el.textContent = lang === 'en' ? 'Disbursement' : 'অর্থ বিতরণ');
        const stepDescsDisburse = document.querySelectorAll('.stepDescDisburse');
        stepDescsDisburse.forEach(el => el.textContent = lang === 'en' ? 'Transfer of funds to registered mobile banking account.' : 'নিবন্ধিত মোবাইল ব্যাংকিং অ্যাকাউন্টে অর্থ স্থানান্তর।');

        const stepTitlesRejected = document.querySelectorAll('.stepTitleRejected');
        stepTitlesRejected.forEach(el => el.textContent = lang === 'en' ? 'Application Rejected' : 'আবেদনটি নামঞ্জুর করা হয়েছে');
        const stepDescsRejected = document.querySelectorAll('.stepDescRejected');
        stepDescsRejected.forEach(el => el.textContent = lang === 'en' ? 'The application does not meet CZM Zakat criteria.' : 'আবেদনটি সিজেডএম যাকাত প্রদানের মাপকাঠি পূরণ করেনি।');

        const stepDatesActive = document.querySelectorAll('.stepDateActive');
        stepDatesActive.forEach(el => el.textContent = lang === 'en' ? 'In progress' : 'চলমান');

        const stepDatesDisbursing = document.querySelectorAll('.stepDateDisbursing');
        stepDatesDisbursing.forEach(el => el.textContent = lang === 'en' ? 'Processing payout' : 'পেমেন্ট প্রক্রিয়াধীন');

        const stepDatesDisbursed = document.querySelectorAll('.stepDateDisbursed');
        stepDatesDisbursed.forEach(el => el.textContent = lang === 'en' ? 'Completed' : 'সম্পন্ন হয়েছে');

        const btnBackMobiles = document.querySelectorAll('.btnBackMobile');
        btnBackMobiles.forEach(el => el.textContent = lang === 'en' ? 'Back / Change Mobile' : 'ফিরে যান / মোবাইল নম্বর পরিবর্তন করুন');

        const btnExitMobiles = document.querySelectorAll('.btnExitMobile');
        btnExitMobiles.forEach(el => el.textContent = lang === 'en' ? 'Exit Status View' : 'স্ট্যাটাস ভিউ বন্ধ করুন');

        const noAppFoundTexts = document.querySelectorAll('#noAppFoundText');
        noAppFoundTexts.forEach(el => el.textContent = lang === 'en' ? 'No applications found matching this mobile number.' : 'এই মোবাইল নম্বরের সাথে মিলে এমন কোনো আবেদন পাওয়া যায়নি।');

        const otpNotices = document.querySelectorAll('#otpNotice');
        otpNotices.forEach(el => {
            el.innerHTML = lang === 'en' 
                ? 'OTP sent to your number. Enter default OTP <strong>1234</strong> for testing.' 
                : 'আপনার নম্বরে ওটিপি পাঠানো হয়েছে। টেস্ট করার জন্য ডিফল্ট ওটিপি <strong>1234</strong> ব্যবহার করুন।';
        });
    }
</script>
@endpush
