@use('App\Models\Setting')
@extends('layouts.public')

@section('title', 'কেন্দ্রীয় যাকাত ব্যবস্থাপনা | CZM Bangladesh')
@section('meta_description', 'Bangladesh\'s transparent, Shariah-compliant Zakat management platform. Give Zakat, apply for aid, and track disbursements with complete accountability.')

@push('styles')
<style>
    /* ━━━━ HERO ━━━━ */
    .hero-section {
        background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 40%, #fefce8 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -100px; right: -200px;
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -50px; left: -100px;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(240,165,0,0.05) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 100px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(16,185,129,0.1);
    }
    .hero-badge .dot {
        width: 7px; height: 7px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }
    .hero-title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900;
        color: #111827;
        line-height: 1.15;
        margin-bottom: 20px;
    }
    .hero-title .highlight {
        background: linear-gradient(135deg, #0d6e3f, #10b981);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .hero-desc {
        font-size: 1.05rem;
        color: #4b5563;
        max-width: 540px;
        line-height: 1.75;
        margin-bottom: 36px;
    }
    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        align-items: center;
        margin-bottom: 48px;
    }
    .btn-hero-primary {
        padding: 14px 32px;
        background: linear-gradient(135deg, #0d6e3f, #16a363);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 20px rgba(13,110,63,0.25);
        transition: all 0.3s ease;
    }
    .btn-hero-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 32px rgba(13,110,63,0.35);
        color: white;
    }
    .btn-hero-secondary {
        padding: 14px 28px;
        background: white;
        color: #0d6e3f;
        border: 2px solid #0d6e3f;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    .btn-hero-secondary:hover { background: #0d6e3f; color: white; }
    .hero-trust {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: center;
    }
    .trust-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        color: #6b7280;
    }
    .trust-item i { color: #10b981; }

    /* Hero Stats Cards */
    .hero-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        position: relative;
        z-index: 2;
    }
    .hero-stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }
    .hero-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 40px rgba(0,0,0,0.1);
    }
    .hero-stat-card .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 12px;
    }
    .hero-stat-card .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: #111827;
        display: block;
    }
    .hero-stat-card .stat-label {
        font-size: 0.82rem;
        color: #6b7280;
        font-weight: 500;
        display: block;
    }

    /* ━━━━ STATS BAR ━━━━ */
    .stats-bar {
        background: #0d6e3f;
        padding: 40px 0;
    }
    .stat-bar-item {
        text-align: center;
        color: white;
        position: relative;
    }
    .stat-bar-item::after {
        content: '';
        position: absolute;
        right: 0; top: 20%; bottom: 20%;
        width: 1px;
        background: rgba(255,255,255,0.2);
    }
    .stat-bar-item:last-child::after { display: none; }
    .stat-bar-item .stat-num {
        font-size: 2.2rem;
        font-weight: 800;
        display: block;
        line-height: 1;
    }
    .stat-bar-item .stat-lbl {
        font-size: 0.82rem;
        opacity: 0.75;
        margin-top: 4px;
        display: block;
    }

    /* ━━━━ PORTALS ━━━━ */
    .portals-section {
        background: #f8faf9;
        padding: 96px 0;
    }
    .portal-card {
        background: white;
        border-radius: 20px;
        padding: 36px 28px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        height: 100%;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }
    .portal-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        border-radius: 20px 20px 0 0;
    }
    .portal-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 48px rgba(0,0,0,0.1);
        color: inherit;
    }
    .portal-card .portal-icon {
        width: 64px; height: 64px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.7rem;
        margin-bottom: 20px;
        flex-shrink: 0;
    }
    .portal-card h3 { font-size: 1.2rem; font-weight: 700; color: #111827; margin-bottom: 8px; }
    .portal-card p { font-size: 0.875rem; color: #6b7280; line-height: 1.65; margin-bottom: 20px; flex: 1; }
    .portal-cta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        align-self: flex-start;
    }

    /* Portal colors */
    .portal-donor::before   { background: linear-gradient(90deg, #10b981, #059669); }
    .portal-donor .portal-icon { background: #f0fdf4; color: #10b981; }
    .portal-donor .portal-cta { background: #f0fdf4; color: #10b981; }
    .portal-donor .portal-cta:hover { background: #10b981; color: white; }

    .portal-beneficiary::before { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
    .portal-beneficiary .portal-icon { background: #eff6ff; color: #3b82f6; }
    .portal-beneficiary .portal-cta { background: #eff6ff; color: #3b82f6; }
    .portal-beneficiary .portal-cta:hover { background: #3b82f6; color: white; }

    .portal-volunteer::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .portal-volunteer .portal-icon { background: #fffbeb; color: #f59e0b; }
    .portal-volunteer .portal-cta { background: #fffbeb; color: #d97706; }
    .portal-volunteer .portal-cta:hover { background: #f59e0b; color: white; }

    .portal-org::before { background: linear-gradient(90deg, #8b5cf6, #6d28d9); }
    .portal-org .portal-icon { background: #f5f3ff; color: #8b5cf6; }
    .portal-org .portal-cta { background: #f5f3ff; color: #8b5cf6; }
    .portal-org .portal-cta:hover { background: #8b5cf6; color: white; }

    /* ━━━━ HOW IT WORKS ━━━━ */
    .how-section { padding: 96px 0; background: white; }
    .process-step {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        padding: 24px;
        border-radius: 16px;
        transition: all 0.3s ease;
        margin-bottom: 12px;
    }
    .process-step:hover { background: #f8faf9; }
    .process-num {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, #0d6e3f, #10b981);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(13,110,63,0.2);
    }
    .process-step h5 { font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 4px; }
    .process-step p { font-size: 0.875rem; color: #6b7280; line-height: 1.6; margin: 0; }

    /* Flow visual */
    .flow-visual {
        background: linear-gradient(135deg, #0d6e3f 0%, #065f46 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        height: 100%;
        min-height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .flow-visual::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .flow-node {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        z-index: 2;
    }
    .flow-node-icon {
        width: 40px; height: 40px;
        background: rgba(16,185,129,0.2);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .flow-node h6 { font-size: 0.9rem; font-weight: 600; margin: 0 0 2px; color: rgba(255,255,255,0.95); }
    .flow-node p { font-size: 0.78rem; color: rgba(255,255,255,0.55); margin: 0; }
    .flow-arrow {
        text-align: center;
        color: rgba(255,255,255,0.3);
        font-size: 1.2rem;
        margin: 4px 0;
    }

    /* ━━━━ ZAKAT CALCULATOR ━━━━ */
    .calc-section {
        background: linear-gradient(135deg, #f0fdf4, #fffbeb);
        padding: 96px 0;
    }
    .calc-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
    }
    .calc-input-group {
        background: #f9fafb;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }
    .calc-input-group:focus-within {
        border-color: #10b981;
        background: white;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
    }
    .calc-input-group label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        min-width: 140px;
    }
    .calc-input-group input {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 0.95rem;
        font-weight: 600;
        color: #111827;
        outline: none;
        text-align: right;
    }
    .calc-input-group .unit {
        font-size: 0.78rem;
        color: #9ca3af;
        font-weight: 500;
    }
    .calc-result {
        background: linear-gradient(135deg, #0d6e3f, #10b981);
        border-radius: 16px;
        padding: 28px;
        color: white;
        margin-top: 20px;
    }
    .calc-result .result-amount {
        font-size: 2.5rem;
        font-weight: 900;
        display: block;
    }
    .calc-result .result-label { font-size: 0.875rem; opacity: 0.8; }

    /* ━━━━ SHARIAH ━━━━ */
    .shariah-section { padding: 96px 0; background: white; }
    .shariah-accordion .accordion-item {
        border: 1px solid #e5e7eb;
        border-radius: 12px !important;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .shariah-accordion .accordion-button {
        font-weight: 600;
        font-size: 0.95rem;
        color: #111827;
        background: white;
        padding: 18px 22px;
        border-radius: 12px !important;
    }
    .shariah-accordion .accordion-button:not(.collapsed) {
        background: #f0fdf4;
        color: #0d6e3f;
        box-shadow: none;
    }
    .shariah-accordion .accordion-button::after {
        filter: none;
    }
    .shariah-accordion .accordion-body {
        font-size: 0.9rem;
        color: #4b5563;
        line-height: 1.75;
        padding: 0 22px 20px;
    }

    /* ━━━━ IMPACT ━━━━ */
    .impact-section {
        padding: 96px 0;
        background: #f8faf9;
    }
    .impact-card {
        background: white;
        border-radius: 16px;
        padding: 28px 24px;
        border: 1px solid #e5e7eb;
        text-align: center;
        transition: all 0.3s ease;
    }
    .impact-card:hover { transform: translateY(-4px); box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
    .impact-card .impact-icon {
        width: 56px; height: 56px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 16px;
    }
    .impact-card h4 { font-size: 2rem; font-weight: 800; margin-bottom: 4px; }
    .impact-card p { font-size: 0.875rem; color: #6b7280; margin: 0; }

    /* ━━━━ TESTIMONIALS ━━━━ */
    .testimonials-section { padding: 96px 0; background: white; }
    .testimonial-card {
        background: #f8faf9;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 28px;
        height: 100%;
    }
    .testimonial-card .stars { color: #f59e0b; font-size: 0.9rem; margin-bottom: 12px; }
    .testimonial-card .quote { font-size: 0.9rem; color: #374151; line-height: 1.7; margin-bottom: 20px; }
    .testimonial-author { display: flex; align-items: center; gap: 12px; }
    .testimonial-avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        color: white;
        font-weight: 700;
        flex-shrink: 0;
    }
    .testimonial-author .author-name { font-weight: 700; font-size: 0.9rem; color: #111827; }
    .testimonial-author .author-role { font-size: 0.78rem; color: #9ca3af; }

    /* ━━━━ CTA SECTION ━━━━ */
    .cta-section {
        background: linear-gradient(135deg, #0d6e3f 0%, #065f46 50%, #1a1a2e 100%);
        padding: 96px 0;
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute; top: -100px; right: -100px;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
    }
    .cta-section h2 { color: white; font-size: clamp(1.8rem, 3.5vw, 2.8rem); font-weight: 800; }
    .cta-section p { color: rgba(255,255,255,0.7); font-size: 1rem; }
    .btn-cta-white {
        padding: 14px 32px;
        background: white;
        color: #0d6e3f;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    .btn-cta-white:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.2); color: #0d6e3f; }
    .btn-cta-outline-white {
        padding: 14px 28px;
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    .btn-cta-outline-white:hover { background: rgba(255,255,255,0.1); border-color: white; color: white; }

    /* ━━━━ ANIMATIONS ━━━━ */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        animation: fadeInUp 0.7s ease forwards;
        opacity: 0;
    }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section { padding: 80px 0 60px; }
        .hero-stats { grid-template-columns: repeat(2, 1fr); }
        .hero-actions { flex-direction: column; align-items: flex-start; }
        .calc-card { padding: 24px 20px; }
        .calc-input-group label { min-width: 100px; }
    }
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="animate-fade-up">
                    <div class="hero-badge">
                        <span class="dot"></span>
                        <span id="heroLiveText">Live Platform</span>
                    </div>
                </div>
                <h1 class="hero-title animate-fade-up delay-1">
                    <span id="heroTitle1">Bangladesh's Most</span><br>
                    <span class="highlight" id="heroTitle2">Transparent Zakat</span><br>
                    <span id="heroTitle3">Management System</span>
                </h1>
                <p class="hero-desc animate-fade-up delay-2" id="heroDesc">
                    A Shariah-compliant, AI-assisted, and blockchain-anchored platform ensuring that every Taka of Zakat reaches its rightful beneficiary — with full accountability.
                </p>
                <div class="hero-actions animate-fade-up delay-3">
                    <a href="{{ url('/donor/register') }}" class="btn-hero-primary" id="heroCta1">
                        <i class="bi bi-heart-fill"></i>
                        <span id="heroCta1Txt">Pay Your Zakat</span>
                    </a>
                    <a href="{{ url('/apply') }}" class="btn-hero-secondary" id="heroCta2">
                        <i class="bi bi-hand-index-fill"></i>
                        <span id="heroCta2Txt">Apply for Aid</span>
                    </a>
                </div>
                <div class="hero-trust animate-fade-up delay-4">
                    <div class="trust-item"><i class="bi bi-shield-check"></i><span id="trust1">Shariah Certified</span></div>
                    <div class="trust-item"><i class="bi bi-lock-fill"></i><span id="trust2">100% Secure</span></div>
                    <div class="trust-item"><i class="bi bi-eye-fill"></i><span id="trust3">Fully Transparent</span></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-stats animate-fade-up delay-2">
                    <div class="hero-stat-card">
                        <div class="stat-icon" style="background:#f0fdf4;color:#10b981;"><i class="bi bi-people-fill"></i></div>
                        <span class="stat-number">{{ number_format($totalDonors ?? 24830) }}+</span>
                        <span class="stat-label" id="stat1Lbl">Registered Donors</span>
                    </div>
                    <div class="hero-stat-card">
                        <div class="stat-icon" style="background:#eff6ff;color:#3b82f6;"><i class="bi bi-house-heart-fill"></i></div>
                        <span class="stat-number">{{ number_format($totalBeneficiaries ?? 185000) }}+</span>
                        <span class="stat-label" id="stat2Lbl">Beneficiaries Served</span>
                    </div>
                    <div class="hero-stat-card">
                        <div class="stat-icon" style="background:#fffbeb;color:#f59e0b;"><i class="bi bi-currency-dollar"></i></div>
                        <span class="stat-number">৳{{ number_format($zakatDistributed ?? 124000000) }}</span>
                        <span class="stat-label" id="stat3Lbl">Zakat Distributed</span>
                    </div>
                    <div class="hero-stat-card">
                        <div class="stat-icon" style="background:#f5f3ff;color:#8b5cf6;"><i class="bi bi-building-fill"></i></div>
                        <span class="stat-number">{{ number_format($partnerOrgs ?? 340) }}+</span>
                        <span class="stat-label" id="stat4Lbl">Partner Organizations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── STATS BAR ── --}}
<section class="stats-bar">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-md-3 stat-bar-item">
                <span class="stat-num">64</span>
                <span class="stat-lbl" id="sb1">Districts Covered</span>
            </div>
            <div class="col-6 col-md-3 stat-bar-item">
                <span class="stat-num">98.7%</span>
                <span class="stat-lbl" id="sb2">Fund Utilisation Rate</span>
            </div>
            <div class="col-6 col-md-3 stat-bar-item">
                <span class="stat-num">24h</span>
                <span class="stat-lbl" id="sb3">Avg. Verification Time</span>
            </div>
            <div class="col-6 col-md-3 stat-bar-item">
                <span class="stat-num">5 ⭐</span>
                <span class="stat-lbl" id="sb4">Shariah Board Rating</span>
            </div>
        </div>
    </div>
</section>

{{-- ── PORTALS ── --}}
<section class="portals-section" id="portals">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge"><i class="bi bi-grid-3x3-gap"></i> <span id="portalsBadge">All Portals</span></div>
            <h2 class="section-title" id="portalsTitle">Who Are You?</h2>
            <p class="section-subtitle mx-auto" id="portalsDesc">
                Whether you're giving Zakat, seeking assistance, volunteering, or representing an organization — we have a dedicated portal for you.
            </p>
        </div>
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="portal-card portal-donor">
                    <div class="portal-icon"><i class="bi bi-heart-fill"></i></div>
                    <h3 id="p1Title">Zakat Donor</h3>
                    <p id="p1Desc">Calculate and pay your annual Zakat. Track your donations, get receipts, and see the direct impact of your generosity.</p>
                    <div>
                        <a href="{{ url('/donor/register') }}" class="portal-cta" id="p1Cta">
                            Register as Donor <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="portal-card portal-beneficiary">
                    <div class="portal-icon"><i class="bi bi-hand-index-fill"></i></div>
                    <h3 id="p2Title">Zakat Beneficiary</h3>
                    <p id="p2Desc">Apply for Zakat assistance. Submit your application online and track its status through our transparent review process.</p>
                    <div>
                        <a href="{{ url('/apply') }}" class="portal-cta" id="p2Cta">
                            Apply for Aid <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="portal-card portal-volunteer">
                    <div class="portal-icon"><i class="bi bi-people-fill"></i></div>
                    <h3 id="p3Title">Volunteer</h3>
                    <p id="p3Desc">Join our field team. Help verify beneficiary applications, conduct field visits, and be part of this social mission.</p>
                    <div>
                        <a href="{{ url('/volunteer/register') }}" class="portal-cta" id="p3Cta">
                            Join as Volunteer <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="portal-card portal-org">
                    <div class="portal-icon"><i class="bi bi-building-fill"></i></div>
                    <h3 id="p4Title">Organization</h3>
                    <p id="p4Desc">Register your NGO, mosque committee, or institution to collaborate in Zakat collection and community distribution.</p>
                    <div>
                        <a href="{{ url('/organization/register') }}" class="portal-cta" id="p4Cta">
                            Register Org <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── VERIFIED ORGANIZATIONS & VOLUNTEERS ── --}}
<section style="background:#ffffff; padding:80px 0;">
    <div class="container">
        <div class="row mb-5 text-center">
            <div class="col-12">
                <div class="section-badge"><i class="bi bi-shield-check"></i> <span>Trusted Entities</span></div>
                <h2 class="section-title">Verified Organizations & Volunteers</h2>
                <p class="section-desc mx-auto">Trusted field partners who ensure your Zakat reaches the right beneficiaries under full transparency.</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold m-0" style="color:var(--pub-primary);"><i class="bi bi-building-check me-2"></i>Top Organizations</h4>
                <a href="{{ route('public.organizations') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">View All</a>
            </div>
        </div>
        <div class="row g-3 mb-5">
            @forelse($organizations ?? [] as $org)
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 border rounded-4 text-center p-4" style="background:#f8faf9; cursor: pointer; transition: transform 0.2s;" data-bs-toggle="modal" data-bs-target="#orgModal{{ $org->id }}" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-building-check text-success mb-2" style="font-size: 2.5rem;"></i>
                    <h6 class="fw-bold mb-1 text-dark">{{ $org->name_en }}</h6>
                    <small class="text-muted">{{ $org->district ?? 'Organization' }}</small>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="orgModal{{ $org->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-success text-white border-0 rounded-top-4">
                            <h5 class="modal-title fw-bold"><i class="bi bi-building me-2"></i>Organization Details</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 text-start">
                            <h4 class="fw-bold text-center mb-1">{{ $org->name_en }}</h4>
                            <p class="text-center text-muted mb-4">{{ $org->name_bn }}</p>

                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span class="text-muted"><i class="bi bi-hash me-2"></i>Registration No.</span>
                                    <span class="fw-bold">{{ $org->registration_no ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span class="text-muted"><i class="bi bi-geo-alt me-2"></i>District</span>
                                    <span class="fw-bold">{{ $org->district ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span class="text-muted"><i class="bi bi-people me-2"></i>Total Donors</span>
                                    <span class="fw-bold">{{ $org->total_donors_via_referral ?? 0 }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span class="text-muted"><i class="bi bi-wallet2 me-2"></i>Total Collected</span>
                                    <span class="fw-bold text-success">৳{{ number_format($org->total_collected_via_referral ?? 0) }}</span>
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="{{ route('payment.show', ['ref' => $org->referral_code ?? '', 'rtype' => 'org']) }}" class="btn btn-success rounded-pill px-4 py-2 w-100 fw-bold">
                                    <i class="bi bi-heart-fill me-1"></i> Donate via this Organization
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-3">No verified organizations yet.</div>
            @endforelse
        </div>

        <div class="row mb-4 mt-5">
            <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold m-0" style="color:var(--pub-primary);"><i class="bi bi-person-badge me-2"></i>Top Volunteers</h4>
                <a href="{{ route('public.volunteers') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">View All</a>
            </div>
        </div>
        <div class="row g-3">
            @forelse($volunteers ?? [] as $vol)
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 border rounded-4 text-center p-4" style="background:#f0f6ff; cursor: pointer; transition: transform 0.2s;" data-bs-toggle="modal" data-bs-target="#volModal{{ $vol->id }}" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-person-badge text-primary mb-2" style="font-size: 2.5rem;"></i>
                    <h6 class="fw-bold mb-1 text-dark">{{ $vol->name_en }}</h6>
                    <small class="text-muted">{{ $vol->district ?? 'Volunteer' }}</small>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="volModal{{ $vol->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                            <h5 class="modal-title fw-bold"><i class="bi bi-person-vcard me-2"></i>Volunteer Details</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 text-start">
                            <h4 class="fw-bold text-center mb-1">{{ $vol->name_en }}</h4>
                            <p class="text-center text-muted mb-4">{{ $vol->name_bn }}</p>

                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span class="text-muted"><i class="bi bi-briefcase me-2"></i>Occupation</span>
                                    <span class="fw-bold">{{ $vol->occupation ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span class="text-muted"><i class="bi bi-geo-alt me-2"></i>District/Area</span>
                                    <span class="fw-bold">{{ $vol->district ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span class="text-muted"><i class="bi bi-file-earmark-check me-2"></i>Field Verifications</span>
                                    <span class="fw-bold">{{ $vol->total_verifications ?? 0 }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span class="text-muted"><i class="bi bi-wallet2 me-2"></i>Referred Collections</span>
                                    <span class="fw-bold text-success">৳{{ number_format($vol->total_collected_via_referral ?? 0) }}</span>
                                </li>
                            </ul>
                            <div class="text-center">
                                <a href="{{ route('payment.show', ['ref' => $vol->referral_code ?? '', 'rtype' => 'volunteer']) }}" class="btn btn-primary rounded-pill px-4 py-2 w-100 fw-bold">
                                    <i class="bi bi-heart-fill me-1"></i> Donate via this Volunteer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-3">No active volunteers yet.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- ── HOW IT WORKS ── --}}
<section class="how-section" id="how-it-works">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="section-badge"><i class="bi bi-gear-fill"></i> <span id="howBadge">Process</span></div>
                <h2 class="section-title" id="howTitle">How Our Platform Works</h2>
                <p class="section-subtitle mb-5" id="howDesc">From your Zakat payment to the beneficiary's hand — every step is audited, verified, and recorded.</p>

                <div class="process-step">
                    <div class="process-num">1</div>
                    <div>
                        <h5 id="step1T">Register & Calculate</h5>
                        <p id="step1D">Create your free donor account and use our built-in Shariah-certified Zakat calculator to determine your obligation.</p>
                    </div>
                </div>
                <div class="process-step">
                    <div class="process-num">2</div>
                    <div>
                        <h5 id="step2T">Secure Payment</h5>
                        <p id="step2D">Pay via mobile banking (bKash, Nagad, Rocket) or bank transfer. Instant digital receipt & tax certificate issued.</p>
                    </div>
                </div>
                <div class="process-step">
                    <div class="process-num">3</div>
                    <div>
                        <h5 id="step3T">AI Screening & Shariah Audit</h5>
                        <p id="step3D">Our AI engine cross-verifies beneficiary applications. Shariah board reviews and approves every disbursement.</p>
                    </div>
                </div>
                <div class="process-step">
                    <div class="process-num">4</div>
                    <div>
                        <h5 id="step4T">Direct Disbursement</h5>
                        <p id="step4D">Funds are transferred directly to beneficiaries via mobile banking with real-time tracking and blockchain anchoring.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="flow-visual">
                    <h5 style="color:rgba(255,255,255,0.7);font-size:0.82rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:24px;">System Flow</h5>
                    <div class="flow-node">
                        <div class="flow-node-icon"><i class="bi bi-person-plus-fill"></i></div>
                        <div><h6 id="fn1T">Donor Registration</h6><p id="fn1D">Secure account with KYC verification</p></div>
                    </div>
                    <div class="flow-arrow"><i class="bi bi-chevron-down"></i></div>
                    <div class="flow-node">
                        <div class="flow-node-icon"><i class="bi bi-calculator-fill"></i></div>
                        <div><h6 id="fn2T">Zakat Calculation</h6><p id="fn2D">AI-powered Nisab computation</p></div>
                    </div>
                    <div class="flow-arrow"><i class="bi bi-chevron-down"></i></div>
                    <div class="flow-node">
                        <div class="flow-node-icon"><i class="bi bi-shield-fill-check"></i></div>
                        <div><h6 id="fn3T">Shariah Audit</h6><p id="fn3D">Board-certified approval process</p></div>
                    </div>
                    <div class="flow-arrow"><i class="bi bi-chevron-down"></i></div>
                    <div class="flow-node">
                        <div class="flow-node-icon"><i class="bi bi-robot"></i></div>
                        <div><h6 id="fn4T">AI Beneficiary Screening</h6><p id="fn4D">Fraud detection & eligibility check</p></div>
                    </div>
                    <div class="flow-arrow"><i class="bi bi-chevron-down"></i></div>
                    <div class="flow-node">
                        <div class="flow-node-icon" style="background:rgba(16,185,129,0.4);"><i class="bi bi-send-check-fill"></i></div>
                        <div><h6 id="fn5T">Direct Disbursement</h6><p id="fn5D">Mobile banking transfer + blockchain record</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── ZAKAT CALCULATOR ── --}}
<section class="calc-section" id="calculator">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="section-badge"><i class="bi bi-calculator"></i> <span id="calcBadge">Free Tool</span></div>
                <h2 class="section-title" id="calcTitle">Calculate Your Zakat</h2>
                <p class="section-subtitle mb-4" id="calcDesc">
                    Use our Shariah-certified calculator to determine your Zakat obligation. Enter your assets and liabilities for an instant, accurate result.
                </p>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="step-badge flex-shrink-0">✓</div>
                        <div>
                            <strong style="font-size:0.9rem;" id="cAdv1T">Nisab Based on Gold/Silver</strong>
                            <p style="font-size:0.82rem;color:#6b7280;margin:2px 0 0;" id="cAdv1D">Automatically calculates current Nisab threshold</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="step-badge flex-shrink-0">✓</div>
                        <div>
                            <strong style="font-size:0.9rem;" id="cAdv2T">Covers All Asset Types</strong>
                            <p style="font-size:0.82rem;color:#6b7280;margin:2px 0 0;" id="cAdv2D">Cash, Gold, Silver, Business Inventory, and more</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="step-badge flex-shrink-0">✓</div>
                        <div>
                            <strong style="font-size:0.9rem;" id="cAdv3T">Deduct Liabilities</strong>
                            <p style="font-size:0.82rem;color:#6b7280;margin:2px 0 0;" id="cAdv3D">Subtract debts before computing Zakat due</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="calc-card">
                    <h5 style="font-weight:700;margin-bottom:24px;color:#111827;" id="calcCardTitle">Zakat Calculator</h5>

                    <div class="calc-input-group">
                        <label id="fCash">💵 Cash & Bank Deposits</label>
                        <input type="number" id="cash" placeholder="0" oninput="calcZakat()">
                        <span class="unit">৳ BDT</span>
                    </div>
                    <div class="calc-input-group">
                        <label id="fGold">🪙 Gold Value</label>
                        <input type="number" id="gold" placeholder="0" oninput="calcZakat()">
                        <span class="unit">৳ BDT</span>
                    </div>
                    <div class="calc-input-group">
                        <label id="fSilver">🥈 Silver Value</label>
                        <input type="number" id="silver" placeholder="0" oninput="calcZakat()">
                        <span class="unit">৳ BDT</span>
                    </div>
                    <div class="calc-input-group">
                        <label id="fBusiness">🏪 Business Inventory</label>
                        <input type="number" id="business" placeholder="0" oninput="calcZakat()">
                        <span class="unit">৳ BDT</span>
                    </div>
                    <div class="calc-input-group" style="border-color:#fecaca;">
                        <label id="fLiab" style="color:#dc2626;">📉 Total Liabilities</label>
                        <input type="number" id="liab" placeholder="0" oninput="calcZakat()">
                        <span class="unit">৳ BDT</span>
                    </div>

                    <div class="calc-result" id="calcResult">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-size:0.82rem;opacity:0.8;" id="resultHeader">Your Zakat Due (2.5%)</span>
                            <span id="nisabStatus" style="font-size:0.75rem;background:rgba(255,255,255,0.15);padding:3px 10px;border-radius:20px;"></span>
                        </div>
                        <span class="result-amount" id="zakatAmount">৳ 0</span>
                        <div class="d-flex justify-content-between mt-3" style="font-size:0.8rem;opacity:0.7;">
                            <span id="netWealthLabel">Net Wealth: <strong id="netWealth">৳ 0</strong></span>
                            <span id="nisabLabel">Nisab: <strong>৳ 85,000</strong></span>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ url('/donor/register') }}" class="btn-pub-primary w-100" style="justify-content:center;padding:14px;" id="calcPayBtn">
                            <i class="bi bi-heart-fill"></i> Pay This Zakat Online
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── SHARIAH GUIDELINES ── --}}
<section class="shariah-section" id="about">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="section-badge"><i class="bi bi-book-fill"></i> <span id="shariahBadge">Islamic Guidelines</span></div>
                <h2 class="section-title" id="shariahTitle">Shariah Principles of Zakat</h2>
                <p class="section-subtitle mb-4" id="shariahDesc">
                    Our platform strictly adheres to classical Islamic jurisprudence (Fiqh al-Zakat) as validated by our Shariah Advisory Board.
                </p>
                <div class="d-flex align-items-center gap-3 p-4" style="background:#f0fdf4;border-radius:16px;border:1px solid #bbf7d0;">
                    <i class="bi bi-patch-check-fill" style="font-size:2rem;color:#10b981;flex-shrink:0;"></i>
                    <div>
                        <strong style="font-size:0.9rem;color:#166534;" id="boardTitle">Shariah Advisory Board</strong>
                        <p style="font-size:0.82rem;color:#4b5563;margin:4px 0 0;" id="boardDesc">Certified by qualified Islamic scholars and reviewed annually.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="accordion shariah-accordion" id="shariahAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#sh1">
                                <i class="bi bi-gem me-2" style="color:#f59e0b;"></i>
                                <span id="sh1Q">What is Nisab?</span>
                            </button>
                        </h2>
                        <div id="sh1" class="accordion-collapse collapse show" data-bs-parent="#shariahAccordion">
                            <div class="accordion-body" id="sh1A">
                                Nisab is the minimum threshold of wealth above which Zakat becomes obligatory. It equals the value of 85 grams of gold or 595 grams of silver. If your net wealth exceeds this for a full lunar year (Hawl), 2.5% is due as Zakat.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sh2">
                                <i class="bi bi-list-check me-2" style="color:#3b82f6;"></i>
                                <span id="sh2Q">Which assets are Zakatable?</span>
                            </button>
                        </h2>
                        <div id="sh2" class="accordion-collapse collapse" data-bs-parent="#shariahAccordion">
                            <div class="accordion-body" id="sh2A">
                                Zakatable assets include: Cash & bank savings, Gold & silver jewelry (beyond personal use), Business inventory and receivables, Agricultural produce (Ushr), Livestock (specific types). Personal use items like clothing, furniture, and primary residence are exempt.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sh3">
                                <i class="bi bi-people me-2" style="color:#8b5cf6;"></i>
                                <span id="sh3Q">Who are the 8 recipients of Zakat?</span>
                            </button>
                        </h2>
                        <div id="sh3" class="accordion-collapse collapse" data-bs-parent="#shariahAccordion">
                            <div class="accordion-body" id="sh3A">
                                The Quran (9:60) specifies 8 categories: (1) Al-Fuqara — the poor, (2) Al-Masakeen — the needy, (3) Al-Amileen — Zakat administrators, (4) Al-Muallafah — those whose hearts are being reconciled, (5) Ar-Riqab — to free slaves, (6) Al-Gharimeen — the debt-ridden, (7) Fi Sabilillah — in the cause of Allah, (8) Ibnus Sabil — the stranded traveler.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sh4">
                                <i class="bi bi-calendar-check me-2" style="color:#10b981;"></i>
                                <span id="sh4Q">What is Hawl?</span>
                            </button>
                        </h2>
                        <div id="sh4" class="accordion-collapse collapse" data-bs-parent="#shariahAccordion">
                            <div class="accordion-body" id="sh4A">
                                Hawl refers to the completion of one full Islamic lunar year (approximately 354 days) during which your wealth remains above the Nisab threshold. Zakat is due once the Hawl is completed. Our platform tracks your Hawl automatically from your registration date.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sh5">
                                <i class="bi bi-file-earmark-text me-2" style="color:#f59e0b;"></i>
                                <span id="sh5Q">Can I deduct debts from Zakat?</span>
                            </button>
                        </h2>
                        <div id="sh5" class="accordion-collapse collapse" data-bs-parent="#shariahAccordion">
                            <div class="accordion-body" id="sh5A">
                                Yes. Short-term debts due within the year may be deducted from your Zakatable assets before calculating Zakat. Long-term liabilities (mortgages, etc.) are treated differently based on the Madhab. Our calculator applies the Hanafi position (most common in Bangladesh), which deducts current-year liabilities.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── IMPACT ── --}}
<section class="impact-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge"><i class="bi bi-bar-chart-fill"></i> <span id="impactBadge">Our Impact</span></div>
            <h2 class="section-title" id="impactTitle">Changing Lives Across Bangladesh</h2>
            <p class="section-subtitle mx-auto" id="impactDesc">Real numbers. Real people. Real change.</p>
        </div>
        <div class="row g-4 mb-5">
            <div class="col-6 col-md-3">
                <div class="impact-card">
                    <div class="impact-icon" style="background:#f0fdf4;color:#10b981;"><i class="bi bi-house-fill"></i></div>
                    <h4 style="color:#10b981;" id="imp1Num">42,000</h4>
                    <p id="imp1">Families Received Aid</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="impact-card">
                    <div class="impact-icon" style="background:#eff6ff;color:#3b82f6;"><i class="bi bi-mortarboard-fill"></i></div>
                    <h4 style="color:#3b82f6;" id="imp2Num">8,500</h4>
                    <p id="imp2">Students Funded</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="impact-card">
                    <div class="impact-icon" style="background:#fffbeb;color:#f59e0b;"><i class="bi bi-hospital-fill"></i></div>
                    <h4 style="color:#f59e0b;" id="imp3Num">12,300</h4>
                    <p id="imp3">Medical Treatments</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="impact-card">
                    <div class="impact-icon" style="background:#fdf2f8;color:#ec4899;"><i class="bi bi-briefcase-fill"></i></div>
                    <h4 style="color:#ec4899;" id="imp4Num">6,800</h4>
                    <p id="imp4">Micro-Enterprises Started</p>
                </div>
            </div>
        </div>

        {{-- Distribution by Category --}}
        <div class="pub-card">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <h4 style="font-weight:700;margin-bottom:8px;" id="distTitle">Zakat Distribution by Category</h4>
                    <p style="color:#6b7280;font-size:0.875rem;" id="distDesc">Transparent breakdown of how every Taka is allocated across eligible categories.</p>
                </div>
                <div class="col-lg-7">
                    <div class="d-flex flex-column gap-3">
                        @php
                        $categories = [
                            ['Faqir (Extreme Poor)', '#10b981', 35],
                            ['Miskin (Needy)', '#3b82f6', 28],
                            ['Gharimin (Debt-Ridden)', '#f59e0b', 15],
                            ['Ibn us-Sabil (Stranded)', '#8b5cf6', 12],
                            ['Fi Sabilillah (Education)', '#ec4899', 10],
                        ];
                        @endphp
                        @foreach($categories as $cat)
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span style="font-size:0.82rem;font-weight:600;color:#374151;">{{ $cat[0] }}</span>
                                <span style="font-size:0.82rem;font-weight:700;color:{{ $cat[1] }};">{{ $cat[2] }}%</span>
                            </div>
                            <div style="height:8px;background:#f3f4f6;border-radius:100px;">
                                <div style="height:8px;width:{{ $cat[2] }}%;background:{{ $cat[1] }};border-radius:100px;transition:width 1s ease;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── TESTIMONIALS ── --}}
<section class="testimonials-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge"><i class="bi bi-chat-quote-fill"></i> <span id="testiBadge">Testimonials</span></div>
            <h2 class="section-title" id="testiTitle">Voices from Our Community</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p class="quote" id="t1Q">"Finally, a platform where I know exactly where my Zakat goes. The tracking feature is amazing — I could see when the beneficiary received my donation."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background:linear-gradient(135deg,#10b981,#059669);">RM</div>
                        <div>
                            <div class="author-name">Rafiqul Islam</div>
                            <div class="author-role">Donor — Dhaka</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p class="quote" id="t2Q">"My application was verified within just 3 days of submission and I received help in 7 days. This platform is truly transparent."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);">FK</div>
                        <div>
                            <div class="author-name">Fatema Khatun</div>
                            <div class="author-role">Beneficiary — Sylhet</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p class="quote" id="t3Q">"As a volunteer, the dashboard makes it easy to manage field visits and submit reports. The training resources are comprehensive and the coordination is excellent."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background:linear-gradient(135deg,#f59e0b,#d97706);">MA</div>
                        <div>
                            <div class="author-name">Mohammad Ashraf</div>
                            <div class="author-role">Volunteer — Chittagong</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CONTACT ── --}}
<section style="padding:96px 0;background:#f8faf9;" id="contact">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="section-badge"><i class="bi bi-telephone-fill"></i> <span id="contactBadge">Get in Touch</span></div>
                <h2 class="section-title" id="contactTitle">Need Help?</h2>
                <p class="section-subtitle mb-5" id="contactDesc">Our support team is available 7 days a week to assist donors, beneficiaries, and organizations.</p>
                <div class="d-flex flex-column gap-4">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;background:#f0fdf4;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#10b981;font-size:1.2rem;flex-shrink:0;">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#111827;" id="c1T">National Helpline</div>
                            <div style="font-size:1.2rem;font-weight:800;color:#10b981;">16789</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#3b82f6;font-size:1.2rem;flex-shrink:0;">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#111827;" id="c2T">Email Support</div>
                            <div style="color:#3b82f6;font-weight:600;">info@czm.gov.bd</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;background:#fffbeb;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#f59e0b;font-size:1.2rem;flex-shrink:0;">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#111827;" id="c3T">Office Hours</div>
                            <div style="color:#6b7280;font-size:0.875rem;" id="c3V">Saturday – Thursday, 9AM – 5PM</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="pub-card" style="padding:40px;">
                    <h5 style="font-weight:700;margin-bottom:24px;" id="formTitle">Send Us a Message</h5>
                    <form method="POST" action="{{ url('/contact') }}" onsubmit="return handleContact(event)">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="pub-form-label" id="fNameLabel">Full Name *</label>
                                <input type="text" name="name" class="pub-form-control" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label" id="fMobLabel">Mobile Number *</label>
                                <input type="tel" name="mobile" class="pub-form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label" id="fEmailLabel">Email Address</label>
                                <input type="email" name="email" class="pub-form-control">
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label" id="fSubLabel">Subject *</label>
                                <select name="subject" class="pub-form-control" required>
                                    <option value="" id="fSubOpt0">-- Select Topic --</option>
                                    <option value="donor" id="fSubOpt1">Donor Registration Help</option>
                                    <option value="beneficiary" id="fSubOpt2">Beneficiary Application Status</option>
                                    <option value="volunteer" id="fSubOpt3">Volunteer Onboarding</option>
                                    <option value="org" id="fSubOpt4">Organization Partnership</option>
                                    <option value="technical" id="fSubOpt5">Technical Support</option>
                                    <option value="other" id="fSubOpt6">Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label" id="fMsgLabel">Message *</label>
                                <textarea name="message" class="pub-form-control" rows="4" required style="resize:vertical;"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-pub-primary w-100" style="justify-content:center;padding:14px;font-size:1rem;" id="fSubmitBtn">
                                    <i class="bi bi-send-fill"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="cta-section">
    <div class="container text-center" style="position:relative;z-index:2;">
        <div class="section-badge" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.8);margin-bottom:24px;" id="ctaBadge"><i class="bi bi-stars"></i> Take Action Today</div>
        <h2 class="mb-3" id="ctaTitle">Your Zakat Can Change a Life</h2>
        <p class="mb-5 mx-auto" style="max-width:480px;" id="ctaDesc">Join thousands of Muslims across Bangladesh who are fulfilling their religious duty with complete transparency.</p>
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <a href="{{ url('/donor/register') }}" class="btn-cta-white" id="ctaBtn1"><i class="bi bi-heart-fill"></i> Pay Zakat Now</a>
            <a href="{{ url('/apply') }}" class="btn-cta-outline-white" id="ctaBtn2"><i class="bi bi-file-earmark-plus"></i> Apply for Aid</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // ── Zakat Calculator ──
    const NISAB_BDT = 85000;
    function calcZakat() {
        const cash     = parseFloat(document.getElementById('cash').value) || 0;
        const gold     = parseFloat(document.getElementById('gold').value) || 0;
        const silver   = parseFloat(document.getElementById('silver').value) || 0;
        const business = parseFloat(document.getElementById('business').value) || 0;
        const liab     = parseFloat(document.getElementById('liab').value) || 0;
        const gross    = cash + gold + silver + business;
        const net      = Math.max(0, gross - liab);
        const zakat    = net >= NISAB_BDT ? net * 0.025 : 0;
        const fmt = n => '৳ ' + Math.floor(n).toLocaleString('en-BD');
        document.getElementById('zakatAmount').textContent = fmt(zakat);
        document.getElementById('netWealth').textContent   = fmt(net);
        document.getElementById('nisabStatus').textContent = net >= NISAB_BDT ? '✓ Nisab Met' : '✗ Below Nisab';
        document.getElementById('nisabStatus').style.background = net >= NISAB_BDT ? 'rgba(255,255,255,0.2)' : 'rgba(255,0,0,0.2)';
    }

    // ── Contact form prevent default (demo) ──
    function handleContact(e) {
        e.preventDefault();
        alert('শুক্রিয়া! আপনার বার্তা পাঠানো হয়েছে।\nThank you! Your message has been sent.');
        e.target.reset();
        return false;
    }

    // ── Page-level translations ──
    const pageTranslations = {
        heroLiveText:  { en: 'Live Platform | সক্রিয় প্ল্যাটফর্ম', bn: 'সক্রিয় প্ল্যাটফর্ম | Live Platform' },
        heroTitle1:    { en: "Bangladesh's Most", bn: 'বাংলাদেশের সবচেয়ে' },
        heroTitle2:    { en: 'Transparent Zakat', bn: 'স্বচ্ছ যাকাত' },
        heroTitle3:    { en: 'Management System', bn: 'ব্যবস্থাপনা সিস্টেম' },
        heroDesc:      { en: 'A Shariah-compliant, AI-assisted, and blockchain-anchored platform ensuring that every Taka of Zakat reaches its rightful beneficiary — with full accountability.',
                        bn: 'একটি শরীয়াহ-সম্মত, AI-সহায়ক এবং ব্লকচেইন-নোঙ্গরযুক্ত প্ল্যাটফর্ম যা নিশ্চিত করে প্রতিটি যাকাতের টাকা সঠিক সুবিধাভোগীর কাছে পৌঁছায়।' },
        heroCta1Txt:   { en: 'Pay Your Zakat', bn: 'যাকাত প্রদান করুন' },
        heroCta2Txt:   { en: 'Apply for Aid', bn: 'সহায়তার আবেদন করুন' },
        trust1:        { en: 'Shariah Certified', bn: 'শরীয়াহ সার্টিফাইড' },
        trust2:        { en: '100% Secure', bn: '১০০% নিরাপদ' },
        trust3:        { en: 'Fully Transparent', bn: 'সম্পূর্ণ স্বচ্ছ' },
        stat1Lbl:      { en: 'Registered Donors', bn: 'নিবন্ধিত দাতা' },
        stat2Lbl:      { en: 'Beneficiaries Served', bn: 'সুবিধাভোগী' },
        stat3Lbl:      { en: 'Zakat Distributed', bn: 'বিতরণকৃত যাকাত' },
        stat4Lbl:      { en: 'Partner Organizations', bn: 'অংশীদার সংস্থা' },
        sb1:           { en: 'Districts Covered', bn: 'জেলা আওতাভুক্ত' },
        sb2:           { en: 'Fund Utilisation Rate', bn: 'তহবিল ব্যবহারের হার' },
        sb3:           { en: 'Avg. Verification Time', bn: 'গড় যাচাই সময়' },
        sb4:           { en: 'Shariah Board Rating', bn: 'শরীয়াহ বোর্ড রেটিং' },
        portalsBadge:  { en: 'All Portals', bn: 'সকল পোর্টাল' },
        portalsTitle:  { en: 'Who Are You?', bn: 'আপনি কে?' },
        portalsDesc:   { en: 'Whether you\'re giving Zakat, seeking assistance, volunteering, or representing an organization — we have a dedicated portal for you.',
                        bn: 'আপনি যাকাত দিচ্ছেন, সহায়তা চাইছেন, স্বেচ্ছাসেবী হচ্ছেন বা কোনো সংস্থার প্রতিনিধিত্ব করছেন — আপনার জন্য আমাদের আলাদা পোর্টাল আছে।' },
        p1Title:       { en: 'Zakat Donor', bn: 'যাকাত দাতা' },
        p1Desc:        { en: 'Calculate and pay your annual Zakat. Track your donations, get receipts, and see the direct impact of your generosity.', bn: 'আপনার বার্ষিক যাকাত হিসাব করুন এবং পরিশোধ করুন। দান ট্র্যাক করুন, রসিদ পান এবং আপনার দানের সরাসরি প্রভাব দেখুন।' },
        p1Cta:         { en: 'Register as Donor →', bn: 'দাতা হিসেবে নিবন্ধন →' },
        p2Title:       { en: 'Zakat Beneficiary', bn: 'যাকাত গ্রহীতা' },
        p2Desc:        { en: 'Apply for Zakat assistance. Submit your application online and track its status through our transparent review process.', bn: 'যাকাত সহায়তার জন্য আবেদন করুন। আপনার আবেদন অনলাইনে জমা দিন এবং আমাদের স্বচ্ছ পর্যালোচনা প্রক্রিয়ার মাধ্যমে স্থিতি ট্র্যাক করুন।' },
        p2Cta:         { en: 'Apply for Aid →', bn: 'সহায়তার আবেদন →' },
        p3Title:       { en: 'Volunteer', bn: 'স্বেচ্ছাসেবী' },
        p3Desc:        { en: 'Join our field team. Help verify beneficiary applications, conduct field visits, and be part of this social mission.', bn: 'আমাদের মাঠ দলে যোগ দিন। উপকারভোগীর আবেদন যাচাই করুন, মাঠ পরিদর্শন পরিচালনা করুন এবং এই সামাজিক মিশনের অংশ হন।' },
        p3Cta:         { en: 'Join as Volunteer →', bn: 'স্বেচ্ছাসেবী হিসেবে যোগ দিন →' },
        p4Title:       { en: 'Organization', bn: 'সংগঠন' },
        p4Desc:        { en: 'Register your NGO, mosque committee, or institution to collaborate in Zakat collection and community distribution.', bn: 'আপনার এনজিও, মসজিদ কমিটি বা প্রতিষ্ঠান নিবন্ধন করুন যাকাত সংগ্রহ ও বিতরণে সহযোগিতা করতে।' },
        p4Cta:         { en: 'Register Org →', bn: 'সংগঠন নিবন্ধন →' },
        howBadge:      { en: 'Process', bn: 'প্রক্রিয়া' },
        howTitle:      { en: 'How Our Platform Works', bn: 'আমাদের প্ল্যাটফর্ম কীভাবে কাজ করে' },
        howDesc:       { en: 'From your Zakat payment to the beneficiary\'s hand — every step is audited, verified, and recorded.', bn: 'আপনার যাকাত প্রদান থেকে উপকারভোগীর হাতে — প্রতিটি পদক্ষেপ অডিট, যাচাই এবং রেকর্ড করা হয়।' },
        step1T:        { en: 'Register & Calculate', bn: 'নিবন্ধন ও হিসাব করুন' },
        step1D:        { en: 'Create your free donor account and use our built-in Shariah-certified Zakat calculator.', bn: 'আপনার বিনামূল্যে দাতার অ্যাকাউন্ট তৈরি করুন এবং শরীয়াহ-সার্টিফাইড ক্যালকুলেটর ব্যবহার করুন।' },
        step2T:        { en: 'Secure Payment', bn: 'নিরাপদ পেমেন্ট' },
        step2D:        { en: 'Pay via mobile banking (bKash, Nagad, Rocket) or bank transfer. Instant receipt issued.', bn: 'মোবাইল ব্যাংকিং বা ব্যাংক ট্রান্সফারের মাধ্যমে পরিশোধ করুন। তাৎক্ষণিক রসিদ প্রদান করা হয়।' },
        step3T:        { en: 'AI Screening & Shariah Audit', bn: 'AI স্ক্রীনিং ও শরীয়াহ অডিট' },
        step3D:        { en: 'Our AI engine cross-verifies beneficiary applications. Shariah board reviews every disbursement.', bn: 'আমাদের AI সুবিধাভোগীর আবেদন যাচাই করে। শরীয়াহ বোর্ড প্রতিটি বিতরণ পর্যালোচনা করে।' },
        step4T:        { en: 'Direct Disbursement', bn: 'সরাসরি বিতরণ' },
        step4D:        { en: 'Funds are transferred directly to beneficiaries via mobile banking with real-time tracking.', bn: 'মোবাইল ব্যাংকিংয়ের মাধ্যমে সরাসরি উপকারভোগীদের কাছে ট্রান্সফার করা হয়।' },
        calcBadge:     { en: 'Free Tool', bn: 'বিনামূল্যে টুল' },
        calcTitle:     { en: 'Calculate Your Zakat', bn: 'আপনার যাকাত হিসাব করুন' },
        calcDesc:      { en: 'Use our Shariah-certified calculator to determine your Zakat obligation.', bn: 'আপনার যাকাতের বাধ্যবাধকতা নির্ধারণ করতে আমাদের শরীয়াহ-সার্টিফাইড ক্যালকুলেটর ব্যবহার করুন।' },
        calcCardTitle: { en: 'Zakat Calculator', bn: 'যাকাত ক্যালকুলেটর' },
        fCash:         { en: '💵 Cash & Bank Deposits', bn: '💵 নগদ ও ব্যাংক আমানত' },
        fGold:         { en: '🪙 Gold Value', bn: '🪙 সোনার মূল্য' },
        fSilver:       { en: '🥈 Silver Value', bn: '🥈 রুপার মূল্য' },
        fBusiness:     { en: '🏪 Business Inventory', bn: '🏪 ব্যবসায়িক পণ্যমজুদ' },
        fLiab:         { en: '📉 Total Liabilities', bn: '📉 মোট দায়' },
        calcPayBtn:    { en: 'Pay This Zakat Online', bn: 'এই যাকাত অনলাইনে পরিশোধ করুন' },
        shariahBadge:  { en: 'Islamic Guidelines', bn: 'ইসলামিক নির্দেশিকা' },
        shariahTitle:  { en: 'Shariah Principles of Zakat', bn: 'যাকাতের শরীয়াহ নীতিমালা' },
        shariahDesc:   { en: 'Our platform strictly adheres to classical Islamic jurisprudence as validated by our Shariah Advisory Board.', bn: 'আমাদের প্ল্যাটফর্ম ক্লাসিক্যাল ইসলামিক ন্যায়শাস্ত্র মেনে চলে।' },
        boardTitle:    { en: 'Shariah Advisory Board', bn: 'শরীয়াহ উপদেষ্টা বোর্ড' },
        boardDesc:     { en: 'Certified by qualified Islamic scholars and reviewed annually.', bn: 'যোগ্য ইসলামিক পণ্ডিতদের দ্বারা সার্টিফাইড এবং বার্ষিকভাবে পর্যালোচনা করা হয়।' },
        impactBadge:   { en: 'Our Impact', bn: 'আমাদের প্রভাব' },
        impactTitle:   { en: 'Changing Lives Across Bangladesh', bn: 'বাংলাদেশ জুড়ে জীবন পরিবর্তন করছি' },
        impactDesc:    { en: 'Real numbers. Real people. Real change.', bn: 'বাস্তব সংখ্যা। বাস্তব মানুষ। বাস্তব পরিবর্তন।' },
        imp1:          { en: 'Families Received Aid', bn: 'পরিবার সহায়তা পেয়েছে' },
        imp2:          { en: 'Students Funded', bn: 'শিক্ষার্থী সহায়তা পেয়েছে' },
        imp3:          { en: 'Medical Treatments', bn: 'চিকিৎসা সুবিধা' },
        imp4:          { en: 'Micro-Enterprises Started', bn: 'ক্ষুদ্র উদ্যোগ শুরু' },
        distTitle:     { en: 'Zakat Distribution by Category', bn: 'বিভাগ অনুযায়ী যাকাত বিতরণ' },
        distDesc:      { en: 'Transparent breakdown of how every Taka is allocated.', bn: 'প্রতিটি টাকা কীভাবে বরাদ্দ হয় তার স্বচ্ছ বিশ্লেষণ।' },
        testiBadge:    { en: 'Testimonials', bn: 'প্রতিক্রিয়া' },
        testiTitle:    { en: 'Voices from Our Community', bn: 'আমাদের সম্প্রদায়ের কণ্ঠস্বর' },
        contactBadge:  { en: 'Get in Touch', bn: 'যোগাযোগ করুন' },
        contactTitle:  { en: 'Need Help?', bn: 'সাহায্য দরকার?' },
        contactDesc:   { en: 'Our support team is available 7 days a week to assist donors, beneficiaries, and organizations.', bn: 'আমাদের সহায়তা দল সপ্তাহে ৭ দিন দাতা, উপকারভোগী এবং সংস্থাগুলিকে সহায়তা করতে প্রস্তুত।' },
        c1T:           { en: 'National Helpline', bn: 'জাতীয় হেল্পলাইন' },
        c2T:           { en: 'Email Support', bn: 'ইমেইল সহায়তা' },
        c3T:           { en: 'Office Hours', bn: 'অফিস সময়' },
        c3V:           { en: 'Saturday – Thursday, 9AM – 5PM', bn: 'শনিবার – বৃহস্পতিবার, সকাল ৯টা – বিকাল ৫টা' },
        fNameLabel:    { en: 'Full Name *', bn: 'পুরো নাম *' },
        fMobLabel:     { en: 'Mobile Number *', bn: 'মোবাইল নম্বর *' },
        fEmailLabel:   { en: 'Email Address', bn: 'ইমেইল ঠিকানা' },
        fSubLabel:     { en: 'Subject *', bn: 'বিষয় *' },
        fMsgLabel:     { en: 'Message *', bn: 'বার্তা *' },
        fSubmitBtn:    { en: 'Send Message', bn: 'বার্তা পাঠান' },
        formTitle:     { en: 'Send Us a Message', bn: 'আমাদের বার্তা পাঠান' },
        ctaBadge:      { en: 'Take Action Today', bn: 'আজই পদক্ষেপ নিন' },
        ctaTitle:      { en: 'Your Zakat Can Change a Life', bn: 'আপনার যাকাত একটি জীবন পরিবর্তন করতে পারে' },
        ctaDesc:       { en: 'Join thousands of Muslims across Bangladesh who are fulfilling their religious duty with complete transparency.', bn: 'বাংলাদেশ জুড়ে হাজার হাজার মুসলমানের সাথে যোগ দিন যারা সম্পূর্ণ স্বচ্ছতার সাথে তাদের ধর্মীয় দায়িত্ব পালন করছেন।' },
        ctaBtn1:       { en: 'Pay Zakat Now', bn: 'এখনই যাকাত দিন' },
        ctaBtn2:       { en: 'Apply for Aid', bn: 'সহায়তার আবেদন করুন' },
    };

    function applyPageTranslations(lang) {
        Object.keys(pageTranslations).forEach(id => {
            const el = document.getElementById(id);
            if (el && pageTranslations[id][lang] !== undefined) {
                const isInput = el.tagName === 'INPUT' || el.tagName === 'TEXTAREA';
                if (isInput) el.placeholder = pageTranslations[id][lang];
                else el.textContent = pageTranslations[id][lang];
            }
        });
    }

    // Observe elements for animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.style.opacity = '1';
                e.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.pub-card, .portal-card, .impact-card, .testimonial-card, .process-step').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });
</script>
@endpush
