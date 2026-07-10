@extends('layouts.public')

@section('title', 'How It Works | কীভাবে কাজ করে — CZM Bangladesh')
@section('meta_description', 'Understand exactly how CZM Bangladesh processes your Zakat — from registration and payment to AI verification and direct disbursement to beneficiaries.')

@push('styles')
<style>
    /* ── Hero ── */
    .how-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e3a8a 100%);
        padding: 100px 0 80px; color: white;
        position: relative; overflow: hidden;
    }
    .how-hero::before {
        content: '';
        position: absolute; top: -80px; right: -80px;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
    }
    .how-hero::after {
        content: '';
        position: absolute; bottom: -60px; left: -60px;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
    }
    .how-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.9); font-size: 0.82rem; font-weight: 600;
        padding: 6px 18px; border-radius: 100px; margin-bottom: 24px;
    }
    .how-hero h1 {
        font-size: clamp(2.2rem, 5vw, 3.6rem); font-weight: 900;
        line-height: 1.15; margin-bottom: 20px; color: white;
    }
    .how-hero h1 span { color: #a5b4fc; }
    .how-hero p { color: rgba(255,255,255,0.72); font-size: 1.05rem; max-width: 580px; line-height: 1.75; }
    .hero-tabs {
        display: flex; flex-wrap: wrap; gap: 12px; margin-top: 32px;
    }
    .hero-tab {
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.85); padding: 8px 18px; border-radius: 100px;
        font-size: 0.85rem; font-weight: 600; text-decoration: none;
        transition: all 0.2s;
    }
    .hero-tab:hover { background: rgba(255,255,255,0.2); color: white; }
    .hero-tab i { margin-right: 6px; }

    /* ── Journey Overview ── */
    .journey-section { padding: 96px 0; background: white; }
    .journey-step {
        text-align: center; padding: 20px;
        transition: all 0.3s;
    }
    .journey-step:hover .step-circle { transform: scale(1.08); }
    .step-circle {
        width: 80px; height: 80px; border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.5rem;
        margin: 0 auto 16px;
        box-shadow: 0 8px 24px rgba(59,130,246,0.3);
        transition: transform 0.3s;
        position: relative;
    }
    .step-circle.green  { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 8px 24px rgba(16,185,129,0.3); }
    .step-circle.amber  { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 8px 24px rgba(245,158,11,0.3); }
    .step-circle.purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); box-shadow: 0 8px 24px rgba(139,92,246,0.3); }
    .step-circle.rose   { background: linear-gradient(135deg, #f43f5e, #be123c); box-shadow: 0 8px 24px rgba(244,63,94,0.3); }
    .step-circle .step-num {
        position: absolute; top: -6px; right: -6px;
        width: 28px; height: 28px; background: white;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: 800; color: #374151;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .journey-step h5 { font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 8px; }
    .journey-step p  { font-size: 0.875rem; color: #6b7280; line-height: 1.65; max-width: 180px; margin: 0 auto; }
    .journey-arrow {
        display: flex; align-items: center; justify-content: center;
        color: #d1d5db; font-size: 1.5rem; padding-top: 40px;
    }

    /* ── Donor Journey ── */
    .donor-section { padding: 96px 0; background: #f0fdf4; }
    .step-card {
        background: white; border-radius: 20px;
        padding: 36px; border: 1px solid #e5e7eb;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        display: flex; gap: 24px; align-items: flex-start;
        margin-bottom: 20px;
        transition: all 0.3s;
        position: relative;
    }
    .step-card:hover { transform: translateX(4px); box-shadow: 0 8px 40px rgba(0,0,0,0.08); }
    .step-card::before {
        content: '';
        position: absolute; left: -1px; top: 20%; bottom: 20%;
        width: 4px; border-radius: 0 4px 4px 0;
    }
    .step-card.green::before  { background: #10b981; }
    .step-card.blue::before   { background: #3b82f6; }
    .step-card.amber::before  { background: #f59e0b; }
    .step-card.purple::before { background: #8b5cf6; }
    .step-card.rose::before   { background: #f43f5e; }
    .step-num-badge {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; font-weight: 800; color: white;
        flex-shrink: 0;
    }
    .step-card h4 { font-size: 1.1rem; font-weight: 700; color: #111827; margin-bottom: 8px; }
    .step-card p  { font-size: 0.9rem; color: #6b7280; line-height: 1.7; margin: 0; }
    .step-detail {
        display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px;
    }
    .step-tag {
        background: #f3f4f6; color: #374151;
        font-size: 0.78rem; font-weight: 600;
        padding: 4px 12px; border-radius: 20px;
        display: inline-flex; align-items: center; gap: 4px;
    }

    /* ── Beneficiary Journey ── */
    .bene-section { padding: 96px 0; background: #eff6ff; }

    /* ── Technology ── */
    .tech-section { padding: 96px 0; background: white; }
    .tech-card {
        background: #f8faf9; border-radius: 20px; padding: 32px;
        border: 1px solid #e5e7eb; height: 100%;
        transition: all 0.3s;
    }
    .tech-card:hover { background: white; box-shadow: 0 8px 40px rgba(0,0,0,0.08); transform: translateY(-4px); }
    .tech-icon {
        width: 56px; height: 56px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; margin-bottom: 18px;
    }
    .tech-card h4 { font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 10px; }
    .tech-card p  { font-size: 0.875rem; color: #6b7280; line-height: 1.7; margin: 0; }

    /* ── FAQ ── */
    .faq-section { padding: 96px 0; background: #f8faf9; }
    .faq-item {
        background: white; border: 1px solid #e5e7eb;
        border-radius: 14px; padding: 24px 28px;
        margin-bottom: 12px; cursor: pointer;
        transition: all 0.3s;
    }
    .faq-item:hover { border-color: #10b981; }
    .faq-q {
        font-weight: 700; font-size: 0.95rem; color: #111827;
        display: flex; justify-content: space-between; align-items: center; gap: 12px;
    }
    .faq-q i { color: #10b981; font-size: 1.1rem; flex-shrink: 0; }
    .faq-a {
        font-size: 0.9rem; color: #6b7280; line-height: 1.75;
        margin-top: 12px; padding-top: 12px;
        border-top: 1px solid #f3f4f6;
    }

    /* ── CTA ── */
    .how-cta {
        background: linear-gradient(135deg, #0d6e3f 0%, #065f46 100%);
        padding: 96px 0; text-align: center;
    }
    .how-cta h2 { color: white; font-size: clamp(1.8rem,3.5vw,2.8rem); font-weight: 800; margin-bottom: 16px; }
    .how-cta p  { color: rgba(255,255,255,0.7); font-size: 1rem; max-width: 480px; margin: 0 auto 36px; }
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<section class="how-hero">
    <div class="container" style="position:relative;z-index:2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="how-badge"><i class="bi bi-gear-fill"></i> Platform Process</div>
                <h1>How <span>CZM Bangladesh</span><br>Handles Your Zakat</h1>
                <p>From the moment you register to the moment a beneficiary receives aid — every step is automated, audited, and completely transparent.</p>
                <div class="hero-tabs">
                    <a href="#donor-journey" class="hero-tab"><i class="bi bi-heart-fill"></i> Donor Journey</a>
                    <a href="#bene-journey" class="hero-tab"><i class="bi bi-hand-index-fill"></i> Beneficiary Journey</a>
                    <a href="#technology" class="hero-tab"><i class="bi bi-cpu-fill"></i> Technology</a>
                    <a href="#faq" class="hero-tab"><i class="bi bi-question-circle-fill"></i> FAQ</a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div style="position:relative; width:100%; max-width:400px;">
                    <div style="width:100%; height:320px; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:24px; position:relative; overflow:hidden;">
                        <!-- process nodes -->
                        <div style="position:absolute; top:40px; left:40px; width:60px; height:60px; border-radius:16px; background:rgba(16, 185, 129, 0.2); border:1px solid rgba(16, 185, 129, 0.4); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#10b981;">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div style="position:absolute; top:68px; left:100px; width:120px; border-bottom:2px dashed rgba(255,255,255,0.2);"></div>
                        
                        <div style="position:absolute; top:40px; right:40px; width:60px; height:60px; border-radius:16px; background:rgba(59, 130, 246, 0.2); border:1px solid rgba(59, 130, 246, 0.4); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#3b82f6;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div style="position:absolute; top:100px; right:68px; height:80px; border-left:2px dashed rgba(255,255,255,0.2);"></div>
                        
                        <div style="position:absolute; bottom:40px; right:40px; width:60px; height:60px; border-radius:16px; background:rgba(245, 158, 11, 0.2); border:1px solid rgba(245, 158, 11, 0.4); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#f59e0b;">
                            <i class="bi bi-person-heart"></i>
                        </div>
                        <div style="position:absolute; bottom:68px; left:100px; width:120px; border-bottom:2px dashed rgba(255,255,255,0.2);"></div>
                        
                        <div style="position:absolute; bottom:40px; left:40px; width:60px; height:60px; border-radius:16px; background:rgba(236, 72, 153, 0.2); border:1px solid rgba(236, 72, 153, 0.4); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#ec4899;">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div style="position:absolute; top:100px; left:68px; height:80px; border-left:2px dashed rgba(255,255,255,0.2);"></div>
                        
                        <!-- center node -->
                        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,0.1); border:2px solid rgba(255,255,255,0.3); display:flex; align-items:center; justify-content:center; font-size:2.5rem; color:white; box-shadow:0 0 40px rgba(16, 185, 129, 0.3);">
                            <i class="bi bi-arrow-repeat" style="animation: spin 8s linear infinite;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── OVERVIEW ── --}}
<section class="journey-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge"><i class="bi bi-map-fill"></i> At a Glance</div>
            <h2 class="section-title">The Complete Journey</h2>
            <p class="section-subtitle mx-auto">Five clear steps from donation to disbursement, all completed within 7 days on average.</p>
        </div>
        <div class="row align-items-start g-0">
            <div class="col">
                <div class="journey-step">
                    <div class="step-circle green">
                        <i class="bi bi-person-plus-fill"></i>
                        <div class="step-num">1</div>
                    </div>
                    <h5>Register</h5>
                    <p>Create your donor account with KYC verification</p>
                </div>
            </div>
            <div class="col-auto d-none d-md-flex"><div class="journey-arrow"><i class="bi bi-chevron-right"></i></div></div>
            <div class="col">
                <div class="journey-step">
                    <div class="step-circle blue">
                        <i class="bi bi-calculator-fill"></i>
                        <div class="step-num">2</div>
                    </div>
                    <h5>Calculate</h5>
                    <p>Use our Shariah-certified Nisab calculator</p>
                </div>
            </div>
            <div class="col-auto d-none d-md-flex"><div class="journey-arrow"><i class="bi bi-chevron-right"></i></div></div>
            <div class="col">
                <div class="journey-step">
                    <div class="step-circle amber">
                        <i class="bi bi-credit-card-fill"></i>
                        <div class="step-num">3</div>
                    </div>
                    <h5>Pay</h5>
                    <p>Secure payment via bKash, Nagad, or bank</p>
                </div>
            </div>
            <div class="col-auto d-none d-md-flex"><div class="journey-arrow"><i class="bi bi-chevron-right"></i></div></div>
            <div class="col">
                <div class="journey-step">
                    <div class="step-circle purple">
                        <i class="bi bi-shield-fill-check"></i>
                        <div class="step-num">4</div>
                    </div>
                    <h5>Audit</h5>
                    <p>AI screening + Shariah board approval</p>
                </div>
            </div>
            <div class="col-auto d-none d-md-flex"><div class="journey-arrow"><i class="bi bi-chevron-right"></i></div></div>
            <div class="col">
                <div class="journey-step">
                    <div class="step-circle rose">
                        <i class="bi bi-send-check-fill"></i>
                        <div class="step-num">5</div>
                    </div>
                    <h5>Reach</h5>
                    <p>Direct mobile banking transfer to beneficiary</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── DONOR JOURNEY ── --}}
<section class="donor-section" id="donor-journey">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="section-badge"><i class="bi bi-heart-fill"></i> For Donors</div>
                <h2 class="section-title">Donor Journey</h2>
                <p class="section-subtitle mb-4">Everything you need to fulfill your Zakat obligation — in under 10 minutes.</p>
                <div style="background:white;border-radius:20px;padding:28px;border:1px solid #bbf7d0;">
                    <div style="font-weight:700;color:#166534;margin-bottom:16px;"><i class="bi bi-stopwatch-fill me-2"></i>Average Time: 7 minutes</div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3" style="font-size:0.875rem;color:#4b5563;">
                            <i class="bi bi-check-circle-fill" style="color:#10b981;"></i> No paperwork required
                        </div>
                        <div class="d-flex align-items-center gap-3" style="font-size:0.875rem;color:#4b5563;">
                            <i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Instant digital receipt
                        </div>
                        <div class="d-flex align-items-center gap-3" style="font-size:0.875rem;color:#4b5563;">
                            <i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Track your donation in real-time
                        </div>
                        <div class="d-flex align-items-center gap-3" style="font-size:0.875rem;color:#4b5563;">
                            <i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Tax certificate for income tax return
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="step-card green">
                    <div class="step-num-badge" style="background:linear-gradient(135deg,#10b981,#059669);">1</div>
                    <div>
                        <h4>Create Your Donor Account</h4>
                        <p>Register with your name, mobile number, and email. Optional: add your NID for identity verification and unlock tax certificate generation.</p>
                        <div class="step-detail">
                            <span class="step-tag"><i class="bi bi-clock"></i> 2 min</span>
                            <span class="step-tag"><i class="bi bi-phone"></i> Mobile OTP</span>
                            <span class="step-tag"><i class="bi bi-shield"></i> 256-bit SSL</span>
                        </div>
                    </div>
                </div>
                <div class="step-card blue">
                    <div class="step-num-badge" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);">2</div>
                    <div>
                        <h4>Calculate Your Zakat Obligation</h4>
                        <p>Our Shariah-certified calculator determines your exact Zakat due based on cash, gold, silver, business inventory, and liabilities. Nisab is updated daily based on current gold market rates.</p>
                        <div class="step-detail">
                            <span class="step-tag"><i class="bi bi-calculator"></i> Auto-calculated</span>
                            <span class="step-tag"><i class="bi bi-gem"></i> Live gold rates</span>
                        </div>
                    </div>
                </div>
                <div class="step-card amber">
                    <div class="step-num-badge" style="background:linear-gradient(135deg,#f59e0b,#d97706);">3</div>
                    <div>
                        <h4>Make Your Payment Securely</h4>
                        <p>Pay via bKash, Nagad, Rocket, or direct bank transfer. All payment gateways are PCI-DSS compliant. An official Zakat receipt is instantly generated and emailed.</p>
                        <div class="step-detail">
                            <span class="step-tag">bKash</span>
                            <span class="step-tag">Nagad</span>
                            <span class="step-tag">Rocket</span>
                            <span class="step-tag">Bank Transfer</span>
                        </div>
                    </div>
                </div>
                <div class="step-card purple">
                    <div class="step-num-badge" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);">4</div>
                    <div>
                        <h4>Track Your Impact</h4>
                        <p>Your donor dashboard shows real-time updates as your Zakat is pooled, allocated to a specific category, disbursed to a beneficiary, and confirmed received. Full audit trail preserved.</p>
                        <div class="step-detail">
                            <span class="step-tag"><i class="bi bi-graph-up"></i> Live tracking</span>
                            <span class="step-tag"><i class="bi bi-download"></i> Download certificate</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── BENEFICIARY JOURNEY ── --}}
<section class="bene-section" id="bene-journey">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="step-card blue">
                    <div class="step-num-badge" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);">1</div>
                    <div>
                        <h4>Submit Your Application Online</h4>
                        <p>Fill our simple beneficiary form with household information, income, and the type of aid needed. Upload supporting documents (NID, proof of hardship). Mobile-friendly for easy submission.</p>
                        <div class="step-detail">
                            <span class="step-tag"><i class="bi bi-phone"></i> Mobile-friendly</span>
                            <span class="step-tag"><i class="bi bi-translate"></i> Bangla / English</span>
                        </div>
                    </div>
                </div>
                <div class="step-card purple">
                    <div class="step-num-badge" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);">2</div>
                    <div>
                        <h4>AI-Powered Eligibility Screening</h4>
                        <p>Our machine learning model cross-references your application against national databases, previous applications, and wealth indicators within 24 hours — flagging suspicious patterns and confirming genuine need.</p>
                        <div class="step-detail">
                            <span class="step-tag"><i class="bi bi-cpu"></i> AI scoring</span>
                            <span class="step-tag"><i class="bi bi-clock"></i> 24-hour response</span>
                        </div>
                    </div>
                </div>
                <div class="step-card amber">
                    <div class="step-num-badge" style="background:linear-gradient(135deg,#f59e0b,#d97706);">3</div>
                    <div>
                        <h4>Field Verification by Volunteers</h4>
                        <p>For larger grants, a trained volunteer visits your location to verify living conditions, income sources, and household composition. The visit is announced in advance and completed within 3 days.</p>
                        <div class="step-detail">
                            <span class="step-tag"><i class="bi bi-person-check"></i> 3-day visit</span>
                            <span class="step-tag"><i class="bi bi-shield"></i> Privacy respected</span>
                        </div>
                    </div>
                </div>
                <div class="step-card green">
                    <div class="step-num-badge" style="background:linear-gradient(135deg,#10b981,#059669);">4</div>
                    <div>
                        <h4>Direct Mobile Banking Transfer</h4>
                        <p>Upon Shariah board approval, funds are transferred directly to your bKash or Nagad account — no middleman, no delay. You receive an SMS notification with the transfer details and a follow-up welfare check in 30 days.</p>
                        <div class="step-detail">
                            <span class="step-tag"><i class="bi bi-phone-fill"></i> bKash / Nagad</span>
                            <span class="step-tag"><i class="bi bi-check2-all"></i> Same-day transfer</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="section-badge"><i class="bi bi-hand-index-fill"></i> For Beneficiaries</div>
                <h2 class="section-title">Beneficiary Journey</h2>
                <p class="section-subtitle mb-4">Applying for Zakat aid is simple, dignified, and confidential. Our process is designed to be respectful of your situation.</p>
                <div style="background:white;border-radius:20px;padding:28px;border:1px solid #bfdbfe;">
                    <div style="font-weight:700;color:#1e40af;margin-bottom:16px;"><i class="bi bi-calendar-check-fill me-2"></i>Average Processing: 7 days</div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3" style="font-size:0.875rem;color:#4b5563;">
                            <i class="bi bi-check-circle-fill" style="color:#3b82f6;"></i> Your information stays private
                        </div>
                        <div class="d-flex align-items-center gap-3" style="font-size:0.875rem;color:#4b5563;">
                            <i class="bi bi-check-circle-fill" style="color:#3b82f6;"></i> Free to apply — no fees
                        </div>
                        <div class="d-flex align-items-center gap-3" style="font-size:0.875rem;color:#4b5563;">
                            <i class="bi bi-check-circle-fill" style="color:#3b82f6;"></i> SMS status updates throughout
                        </div>
                        <div class="d-flex align-items-center gap-3" style="font-size:0.875rem;color:#4b5563;">
                            <i class="bi bi-check-circle-fill" style="color:#3b82f6;"></i> Grievance process if declined
                        </div>
                    </div>
                    <a href="{{ url('/apply') }}" class="btn-pub-primary mt-4 w-100" style="justify-content:center;padding:14px;">
                        <i class="bi bi-file-earmark-plus-fill"></i> Apply for Zakat Aid
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── TECHNOLOGY ── --}}
<section class="tech-section" id="technology">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge"><i class="bi bi-cpu-fill"></i> Technology Stack</div>
            <h2 class="section-title">What Powers Our Platform</h2>
            <p class="section-subtitle mx-auto">We've combined the best of modern technology with strict Shariah compliance to build the most trusted Zakat platform in Bangladesh.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="tech-card">
                    <div class="tech-icon" style="background:#f0fdf4;color:#10b981;"><i class="bi bi-robot"></i></div>
                    <h4>AI Beneficiary Screening</h4>
                    <p>Our ML model trained on 50,000+ verified cases detects fraudulent applications with 94% accuracy, processing each application in under 2 minutes and outputting a confidence score reviewed by our team.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="tech-card">
                    <div class="tech-icon" style="background:#eff6ff;color:#3b82f6;"><i class="bi bi-link-45deg"></i></div>
                    <h4>Blockchain Anchoring</h4>
                    <p>Every disbursement record is cryptographically hashed and anchored to a public blockchain, creating an immutable audit trail. Donors can verify any transaction independently at any time.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="tech-card">
                    <div class="tech-icon" style="background:#fffbeb;color:#f59e0b;"><i class="bi bi-shield-lock-fill"></i></div>
                    <h4>PCI-DSS Payment Security</h4>
                    <p>All financial transactions are processed through PCI-DSS Level 1 certified payment gateways. Your financial data is never stored on our servers — only encrypted tokens.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="tech-card">
                    <div class="tech-icon" style="background:#f5f3ff;color:#8b5cf6;"><i class="bi bi-geo-alt-fill"></i></div>
                    <h4>GPS Field Verification</h4>
                    <p>Volunteer field visits are GPS-tagged and timestamped, confirming actual visits to beneficiary locations. Photo documentation is encrypted and stored for audit purposes.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="tech-card">
                    <div class="tech-icon" style="background:#fdf2f8;color:#ec4899;"><i class="bi bi-bar-chart-fill"></i></div>
                    <h4>Real-Time Analytics</h4>
                    <p>Live dashboards for donors, administrators, and the Shariah board track every Taka — collection rates, disbursement velocity, category breakdowns, and regional coverage maps.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="tech-card">
                    <div class="tech-icon" style="background:#f0fdf4;color:#10b981;"><i class="bi bi-phone-fill"></i></div>
                    <h4>Mobile-First Design</h4>
                    <p>75% of our users are on mobile phones. Our platform is fully responsive and works on 2G networks, with offline-capable application forms for rural areas with poor connectivity.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── FAQ ── --}}
<section class="faq-section" id="faq">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge"><i class="bi bi-question-circle-fill"></i> FAQ</div>
            <h2 class="section-title">Frequently Asked Questions</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @foreach([
                    ['Is CZM Bangladesh government-regulated?', 'Yes. CZM Bangladesh is registered with the Bangladesh NGO Bureau and operates under the supervision of the Ministry of Religious Affairs. All operations are independently audited annually by a Chartered Accountant firm.'],
                    ['How do I know my Zakat reached a real beneficiary?', 'Your donor dashboard shows the full chain — payment confirmation, fund allocation, Shariah approval, disbursement date, and the beneficiary\'s district and category. Blockchain hashes are also available for technical verification.'],
                    ['What if my application is rejected?', 'You will receive an SMS and email explaining the reason. You may reapply after 30 days with updated information, or appeal the decision through our formal grievance process — which is reviewed by a committee within 7 working days.'],
                    ['Can I specify where my Zakat goes?', 'You can choose a broad category (e.g., education, medical, extreme poverty). However, the final allocation of funds within that category is determined by our Shariah board to ensure equitable distribution — direct earmarking to specific individuals is not permitted under Zakat law.'],
                    ['Is online Zakat payment Shariah-valid?', 'Yes. Our Shariah Advisory Board has issued a Fatwa confirming that digital Zakat payments are fully valid under Hanafi jurisprudence, provided the amount is correctly calculated and the intention (niyyah) is made at the time of payment.'],
                    ['How are volunteer field workers selected?', 'Volunteers undergo a 3-day training program, background verification through NID records, and must be recommended by a partner organization. They are periodically re-evaluated based on accuracy and timeliness of their field reports.'],
                ] as $faq)
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-q">
                        <span>{{ $faq[0] }}</span>
                        <i class="bi bi-plus-circle-fill"></i>
                    </div>
                    <div class="faq-a" style="display:none;">{{ $faq[1] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="how-cta">
    <div class="container" style="position:relative;z-index:2;">
        <div class="section-badge" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);margin-bottom:24px;">
            <i class="bi bi-stars"></i> Ready to Begin?
        </div>
        <h2>Start Your Zakat Journey Today</h2>
        <p>Join over 24,000 donors who are fulfilling their Islamic duty with complete confidence and transparency.</p>
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <a href="{{ url('/donor/register') }}" class="btn-pub-primary" style="padding:14px 32px;font-size:1rem;">
                <i class="bi bi-heart-fill"></i> Register as Donor
            </a>
            <a href="{{ url('/apply') }}" class="btn-pub-outline" style="padding:14px 28px;font-size:1rem;background:transparent;color:white;border-color:rgba(255,255,255,0.4);">
                <i class="bi bi-file-earmark-plus-fill"></i> Apply for Aid
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function toggleFaq(el) {
        const ans = el.querySelector('.faq-a');
        const icon = el.querySelector('.faq-q i');
        const isOpen = ans.style.display !== 'none';
        // close all
        document.querySelectorAll('.faq-a').forEach(a => a.style.display = 'none');
        document.querySelectorAll('.faq-q i').forEach(i => { i.className = 'bi bi-plus-circle-fill'; });
        if (!isOpen) {
            ans.style.display = 'block';
            icon.className = 'bi bi-dash-circle-fill';
            el.style.borderColor = '#10b981';
        }
    }
    function applyPageTranslations(lang) {}
</script>
@endpush
