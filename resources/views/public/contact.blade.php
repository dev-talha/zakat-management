@extends('layouts.public')

@section('title', 'Contact Us | CZM Bangladesh')
@section('meta_description', 'Contact the CZM Bangladesh support team. Reach us by phone, email, or our online form. We respond within 24 hours.')

@push('styles')
<style>
    /* ── Hero ── */
    .contact-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #1e3a8a 100%);
        padding: 100px 0 80px; color: white;
        position: relative; overflow: hidden;
    }
    .contact-hero::before {
        content: '';
        position: absolute; top: -80px; right: -80px;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
    }
    .contact-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.9); font-size: 0.82rem; font-weight: 600;
        padding: 6px 18px; border-radius: 100px; margin-bottom: 24px;
    }
    .contact-hero h1 {
        font-size: clamp(2.2rem, 5vw, 3.5rem); font-weight: 900;
        line-height: 1.15; margin-bottom: 20px; color: white;
    }
    .contact-hero h1 span { color: #93c5fd; }
    .contact-hero p { color: rgba(255,255,255,0.72); font-size: 1.05rem; max-width: 520px; line-height: 1.75; }

    /* ── Quick Channels ── */
    .channel-card {
        background: white; border-radius: 20px; padding: 32px 28px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        text-align: center; height: 100%;
        transition: all 0.3s;
        position: relative; overflow: hidden;
    }
    .channel-card::before {
        content: '';
        position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
        border-radius: 0 0 20px 20px;
    }
    .channel-card:hover { transform: translateY(-6px); box-shadow: 0 16px 48px rgba(0,0,0,0.1); }
    .channel-icon {
        width: 72px; height: 72px; border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; margin: 0 auto 20px;
        transition: transform 0.3s;
    }
    .channel-card:hover .channel-icon { transform: scale(1.1) rotate(-5deg); }
    .channel-card h4 { font-size: 1.1rem; font-weight: 700; color: #111827; margin-bottom: 8px; }
    .channel-card p  { font-size: 0.875rem; color: #6b7280; line-height: 1.65; margin-bottom: 16px; }
    .channel-value   { font-size: 1.3rem; font-weight: 800; display: block; margin-bottom: 4px; }
    .channel-hint    { font-size: 0.78rem; color: #9ca3af; }
    .channel-green::before  { background: linear-gradient(90deg, #10b981, #059669); }
    .channel-blue::before   { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
    .channel-amber::before  { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .channel-purple::before { background: linear-gradient(90deg, #8b5cf6, #6d28d9); }

    /* ── Form Section ── */
    .form-section-bg { background: #f8faf9; padding: 96px 0; }
    .contact-form-card {
        background: white; border-radius: 24px; padding: 48px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 8px 48px rgba(0,0,0,0.08);
    }
    .form-section-head { font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
    .form-section-head i { color: #10b981; }
    .char-count { font-size: 0.78rem; color: #9ca3af; text-align: right; margin-top: 4px; }

    /* Sidebar info */
    .info-card {
        background: white; border-radius: 20px; padding: 28px;
        border: 1px solid #e5e7eb; margin-bottom: 20px;
    }
    .info-card h5 { font-weight: 700; color: #111827; margin-bottom: 16px; }
    .info-row {
        display: flex; align-items: flex-start; gap: 16px;
        margin-bottom: 16px;
    }
    .info-row:last-child { margin-bottom: 0; }
    .info-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .info-row strong { display: block; font-size: 0.875rem; font-weight: 700; color: #111827; margin-bottom: 2px; }
    .info-row span   { font-size: 0.82rem; color: #6b7280; line-height: 1.5; }

    /* ── Map Placeholder ── */
    .map-box {
        background: linear-gradient(135deg, #f0fdf4, #eff6ff);
        border: 1px solid #e5e7eb; border-radius: 16px;
        min-height: 180px; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        color: #6b7280; font-size: 0.875rem; padding: 24px;
        text-align: center;
    }

    /* ── Office Hours ── */
    .hours-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
    }
    .hours-row:last-child { border-bottom: none; }
    .hours-row .day { font-weight: 600; color: #374151; }
    .hours-row .time { color: #6b7280; }
    .hours-row .badge-open { background: #f0fdf4; color: #10b981; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
    .hours-row .badge-closed { background: #fef2f2; color: #dc2626; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; }

    /* ── Social ── */
    .social-btn {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; text-decoration: none;
        transition: all 0.2s;
    }
    .social-btn:hover { transform: translateY(-3px); }
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<section class="contact-hero">
    <div class="container" style="position:relative;z-index:2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="contact-badge"><i class="bi bi-telephone-fill"></i> Contact & Support</div>
                <h1>We're Here<br><span>to Help You</span></h1>
                <p>Our dedicated support team is available 6 days a week to assist donors, beneficiaries, volunteers, and organizations. We respond to all inquiries within 24 hours.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <div style="display:flex;align-items:center;gap:8px;color:rgba(255,255,255,0.8);font-size:0.875rem;">
                        <i class="bi bi-check-circle-fill" style="color:#60a5fa;"></i> Bangla & English support
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;color:rgba(255,255,255,0.8);font-size:0.875rem;">
                        <i class="bi bi-check-circle-fill" style="color:#60a5fa;"></i> 24-hour response time
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;color:rgba(255,255,255,0.8);font-size:0.875rem;">
                        <i class="bi bi-check-circle-fill" style="color:#60a5fa;"></i> Free helpline 16789
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div style="position:relative; width:100%; max-width:360px;">
                    <div style="width:100%; height:300px; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:24px; position:relative; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                        
                        <!-- Floating chat bubbles -->
                        <div style="position:absolute; top:40px; left:30px; background:rgba(96, 165, 250, 0.15); border:1px solid rgba(96, 165, 250, 0.3); padding:12px 20px; border-radius:20px; border-bottom-left-radius:4px; color:#93c5fd; font-size:1.5rem; animation: float 6s ease-in-out infinite;">
                            <i class="bi bi-chat-dots-fill"></i>
                        </div>
                        
                        <div style="position:absolute; bottom:50px; right:40px; background:rgba(16, 185, 129, 0.15); border:1px solid rgba(16, 185, 129, 0.3); padding:16px; border-radius:20px; border-bottom-right-radius:4px; color:#6ee7b7; font-size:1.8rem; animation: float 7s ease-in-out infinite reverse;">
                            <i class="bi bi-envelope-open-fill"></i>
                        </div>
                        
                        <div style="position:absolute; top:70px; right:50px; background:rgba(244, 114, 182, 0.15); border:1px solid rgba(244, 114, 182, 0.3); width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#f9a8d4; font-size:1.5rem; animation: pulse 4s infinite;">
                            <i class="bi bi-telephone-inbound-fill"></i>
                        </div>

                        <!-- Center Support Agent icon -->
                        <div style="width:120px; height:120px; border-radius:50%; background:linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02)); border:2px solid rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:3.5rem; color:white; box-shadow:0 10px 40px rgba(0,0,0,0.2), inset 0 0 20px rgba(255,255,255,0.05); position:relative; z-index:2;">
                            <i class="bi bi-headset"></i>
                        </div>
                        
                        <!-- Decorative rings -->
                        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:180px; height:180px; border-radius:50%; border:1px dashed rgba(255,255,255,0.1); z-index:1;"></div>
                        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:250px; height:250px; border-radius:50%; border:1px dashed rgba(255,255,255,0.05); z-index:1;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── QUICK CHANNELS ── --}}
<div style="background:white;padding:80px 0;border-bottom:1px solid #f3f4f6;">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge"><i class="bi bi-lightning-charge-fill"></i> Quick Contact</div>
            <h2 class="section-title">Choose Your Channel</h2>
        </div>
        <div class="row g-4">
            <div class="col-sm-6 col-lg-3">
                <div class="channel-card channel-green">
                    <div class="channel-icon" style="background:#f0fdf4;color:#10b981;">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <h4>Helpline</h4>
                    <p>Speak to a support agent directly. Available in Bangla and English.</p>
                    <a href="tel:16789" class="channel-value" style="color:#10b981;text-decoration:none;">16789</a>
                    <div class="channel-hint">Free call · Sat–Thu 9AM–5PM</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="channel-card channel-blue">
                    <div class="channel-icon" style="background:#eff6ff;color:#3b82f6;">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <h4>Email Support</h4>
                    <p>Detailed queries, document submissions, and formal correspondence.</p>
                    <a href="mailto:info@czm.gov.bd" class="channel-value" style="color:#3b82f6;font-size:1rem;text-decoration:none;">info@czm.gov.bd</a>
                    <div class="channel-hint">Response within 24 hours</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="channel-card channel-amber">
                    <div class="channel-icon" style="background:#fffbeb;color:#f59e0b;">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <h4>WhatsApp</h4>
                    <p>Quick messages, status inquiries, and document sharing via WhatsApp.</p>
                    <a href="#" class="channel-value" style="color:#f59e0b;text-decoration:none;">+880 1700-000000</a>
                    <div class="channel-hint">Mon–Fri 10AM–4PM</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="channel-card channel-purple">
                    <div class="channel-icon" style="background:#f5f3ff;color:#8b5cf6;">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h4>Visit Us</h4>
                    <p>Head office appointments available with prior scheduling.</p>
                    <span class="channel-value" style="color:#8b5cf6;font-size:0.95rem;">National Zakat Center</span>
                    <div class="channel-hint">Dhaka-1200 · By appointment</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── FORM + SIDEBAR ── --}}
<section class="form-section-bg">
    <div class="container">
        <div class="row g-5">

            {{-- Contact Form --}}
            <div class="col-lg-7">
                <div class="contact-form-card">
                    <div class="section-badge mb-4"><i class="bi bi-send-fill"></i> Online Message</div>
                    <h2 class="section-title" style="font-size:1.8rem;">Send Us a Message</h2>
                    <p style="color:#6b7280;margin-bottom:32px;">We read every message personally and will respond with a detailed reply within one working day.</p>

                    @if(session('success'))
                    <div class="pub-alert pub-alert-success mb-4">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    @endif

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

                    <form method="POST" action="{{ route('public.contact.store') }}">
                        @csrf
                        <div class="form-section-head"><i class="bi bi-person-fill"></i> Your Information</div>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Full Name *</label>
                                <input type="text" name="name" class="pub-form-control" value="{{ old('name') }}" placeholder="e.g. Mohammad Rahman" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Mobile Number *</label>
                                <div style="position:relative;">
                                    <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:0.82rem;">🇧🇩 +88</span>
                                    <input type="tel" name="mobile" class="pub-form-control" value="{{ old('mobile') }}" placeholder="01XXXXXXXXX" required style="padding-left:68px;">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label">Email Address</label>
                                <input type="email" name="email" class="pub-form-control" value="{{ old('email') }}" placeholder="your@email.com">
                                <div class="pub-form-hint">Optional — we'll reply here if provided, otherwise via SMS</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Your Role</label>
                                <select name="role" class="pub-form-control">
                                    <option value="">-- Select --</option>
                                    <option value="donor" {{ old('role')=='donor'?'selected':'' }}>Donor</option>
                                    <option value="beneficiary" {{ old('role')=='beneficiary'?'selected':'' }}>Beneficiary / Applicant</option>
                                    <option value="volunteer" {{ old('role')=='volunteer'?'selected':'' }}>Volunteer</option>
                                    <option value="organization" {{ old('role')=='organization'?'selected':'' }}>Organization</option>
                                    <option value="media" {{ old('role')=='media'?'selected':'' }}>Media / Press</option>
                                    <option value="other" {{ old('role')=='other'?'selected':'' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">District</label>
                                <select name="district" class="pub-form-control">
                                    <option value="">-- All Districts --</option>
                                    @foreach(['Dhaka','Chittagong','Sylhet','Rajshahi','Khulna','Barisal','Rangpur','Mymensingh','Comilla','Cox\'s Bazar'] as $d)
                                    <option value="{{ $d }}" {{ old('district')==$d?'selected':'' }}>{{ $d }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-section-head"><i class="bi bi-chat-text-fill"></i> Your Message</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="pub-form-label">Subject *</label>
                                <select name="subject" class="pub-form-control" required>
                                    <option value="">-- Select Topic --</option>
                                    <option value="donor_help" {{ old('subject')=='donor_help'?'selected':'' }}>Donor Registration Help</option>
                                    <option value="payment" {{ old('subject')=='payment'?'selected':'' }}>Payment / Transaction Issue</option>
                                    <option value="beneficiary" {{ old('subject')=='beneficiary'?'selected':'' }}>Beneficiary Application Status</option>
                                    <option value="volunteer" {{ old('subject')=='volunteer'?'selected':'' }}>Volunteer Onboarding</option>
                                    <option value="org" {{ old('subject')=='org'?'selected':'' }}>Organization Partnership</option>
                                    <option value="shariah" {{ old('subject')=='shariah'?'selected':'' }}>Shariah / Zakat Query</option>
                                    <option value="technical" {{ old('subject')=='technical'?'selected':'' }}>Technical Support</option>
                                    <option value="media" {{ old('subject')=='media'?'selected':'' }}>Media Inquiry</option>
                                    <option value="other" {{ old('subject')=='other'?'selected':'' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label">Message *</label>
                                <textarea name="message" id="contactMsg" class="pub-form-control" rows="5" required style="resize:vertical;" maxlength="2000" placeholder="Please describe your question or issue in detail. The more context you provide, the faster we can help." oninput="updateCount()">{{ old('message') }}</textarea>
                                <div class="char-count"><span id="charCount">0</span> / 2000 characters</div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-pub-primary w-100" style="justify-content:center;padding:16px;font-size:1rem;">
                                    <i class="bi bi-send-fill"></i> Send Message
                                </button>
                                <p style="text-align:center;font-size:0.8rem;color:#9ca3af;margin-top:12px;">
                                    <i class="bi bi-lock-fill" style="color:#10b981;"></i>
                                    Your information is protected by 256-bit SSL encryption
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-5">

                {{-- Contact Info --}}
                <div class="info-card">
                    <h5><i class="bi bi-geo-alt-fill me-2" style="color:#10b981;"></i> Head Office</h5>
                    <div class="info-row">
                        <div class="info-icon" style="background:#f0fdf4;color:#10b981;">
                            <i class="bi bi-building-fill"></i>
                        </div>
                        <div>
                            <strong>CZM Bangladesh National Centre</strong>
                            <span>National Zakat Center, 2nd Floor<br>Islamic Foundation Building, Agargaon<br>Dhaka — 1207, Bangladesh</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon" style="background:#eff6ff;color:#3b82f6;">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <strong>National Helpline</strong>
                            <span><a href="tel:16789" style="color:#3b82f6;font-weight:700;text-decoration:none;">16789</a> (Free call)</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon" style="background:#fffbeb;color:#f59e0b;">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <strong>Email Addresses</strong>
                            <span>
                                <a href="mailto:info@czm.gov.bd" style="color:#f59e0b;font-weight:600;text-decoration:none;display:block;">info@czm.gov.bd</a>
                                <a href="mailto:support@czm.gov.bd" style="color:#6b7280;font-size:0.82rem;text-decoration:none;">support@czm.gov.bd</a>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Office Hours --}}
                <div class="info-card">
                    <h5><i class="bi bi-clock-fill me-2" style="color:#3b82f6;"></i> Office Hours</h5>
                    @foreach([
                        ['Saturday – Tuesday', '9:00 AM – 5:00 PM', 'open'],
                        ['Wednesday', '9:00 AM – 3:00 PM', 'open'],
                        ['Thursday', '9:00 AM – 5:00 PM', 'open'],
                        ['Friday', 'Closed', 'closed'],
                    ] as $h)
                    <div class="hours-row">
                        <span class="day">{{ $h[0] }}</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="time">{{ $h[1] }}</span>
                            @if($h[2] === 'open')
                            <span class="badge-open">OPEN</span>
                            @else
                            <span class="badge-closed">CLOSED</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    <div style="background:#f0fdf4;border-radius:10px;padding:12px;margin-top:12px;font-size:0.82rem;color:#166534;">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Emergency beneficiary cases can be escalated via helpline 16789 anytime.
                    </div>
                </div>

                {{-- Map Placeholder --}}
                <div class="info-card">
                    <h5><i class="bi bi-map-fill me-2" style="color:#8b5cf6;"></i> Find Us</h5>
                    <div class="map-box">
                        <i class="bi bi-geo-alt-fill" style="font-size:2.5rem;color:#10b981;margin-bottom:12px;"></i>
                        <strong style="color:#374151;">Islamic Foundation Building</strong>
                        <span style="margin-top:6px;">Agargaon, Dhaka-1207</span>
                        <a href="https://maps.google.com/?q=Islamic+Foundation+Bangladesh+Dhaka" target="_blank" class="btn-pub-outline mt-3" style="font-size:0.82rem;padding:8px 16px;">
                            <i class="bi bi-box-arrow-up-right"></i> Open in Maps
                        </a>
                    </div>
                </div>

                {{-- Social --}}
                <div class="info-card">
                    <h5><i class="bi bi-share-fill me-2" style="color:#ec4899;"></i> Follow Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="social-btn" style="background:#1877f2;color:white;" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-btn" style="background:#000;color:white;" title="Twitter/X">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" class="social-btn" style="background:#ff0000;color:white;" title="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="#" class="social-btn" style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:white;" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                    <p style="font-size:0.82rem;color:#9ca3af;margin-top:12px;margin-bottom:0;">
                        Follow for Zakat tips, Ramadan campaigns, and impact reports.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function updateCount() {
        const ta = document.getElementById('contactMsg');
        document.getElementById('charCount').textContent = ta.value.length;
    }
    // Initialize count on page load
    document.addEventListener('DOMContentLoaded', updateCount);
    function applyPageTranslations(lang) {}
</script>
@endpush
