@extends('layouts.public')

@section('title', 'About Us | আমাদের সম্পর্কে — CZM Bangladesh')
@section('meta_description', 'Learn about the Central Zakat Management platform — our mission, Shariah Advisory Board, and impact across Bangladesh.')

@push('styles')
<style>
    /* ── Hero ── */
    .about-hero {
        background: linear-gradient(135deg, #0a4f2e 0%, #065f46 50%, #0d6e3f 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
        color: white;
    }
    .about-hero::before {
        content: '';
        position: absolute;
        top: -120px; right: -120px;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        pointer-events: none;
    }
    .about-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -80px;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(240,165,0,0.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .about-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.25);
        color: rgba(255,255,255,0.9);
        font-size: 0.82rem; font-weight: 600;
        padding: 6px 18px; border-radius: 100px;
        margin-bottom: 24px;
        backdrop-filter: blur(10px);
    }
    .about-hero h1 {
        font-size: clamp(2.2rem, 5vw, 3.6rem);
        font-weight: 900; line-height: 1.15;
        margin-bottom: 20px; color: white;
    }
    .about-hero h1 span {
        background: linear-gradient(90deg, #fbbf24, #f0a500);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .about-hero p { color: rgba(255,255,255,0.75); font-size: 1.05rem; max-width: 560px; line-height: 1.75; }
    .hero-stat-pill {
        display: inline-flex; align-items: center; gap: 10px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 50px; padding: 10px 20px;
        color: white;
    }
    .hero-stat-pill strong { font-size: 1.4rem; font-weight: 800; color: #fbbf24; }
    .hero-stat-pill span { font-size: 0.82rem; opacity: 0.8; }

    /* ── Mission ── */
    .mission-section { padding: 96px 0; background: #f8faf9; }
    .mission-card {
        background: white; border-radius: 20px; padding: 40px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        height: 100%;
        transition: all 0.3s ease;
        position: relative; overflow: hidden;
    }
    .mission-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 4px;
        border-radius: 20px 20px 0 0;
    }
    .mission-card:hover { transform: translateY(-4px); box-shadow: 0 12px 48px rgba(0,0,0,0.1); }
    .mission-icon {
        width: 64px; height: 64px; border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.7rem; margin-bottom: 20px;
    }
    .mission-card h3 { font-size: 1.2rem; font-weight: 800; color: #111827; margin-bottom: 12px; }
    .mission-card p { font-size: 0.9rem; color: #6b7280; line-height: 1.75; margin: 0; }
    .card-green::before { background: linear-gradient(90deg, #10b981, #059669); }
    .card-blue::before  { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
    .card-amber::before { background: linear-gradient(90deg, #f59e0b, #d97706); }

    /* ── Values ── */
    .values-section { padding: 96px 0; background: white; }
    .value-item {
        display: flex; gap: 20px; align-items: flex-start;
        padding: 28px; border-radius: 16px; border: 1px solid #f3f4f6;
        transition: all 0.3s ease; background: white;
    }
    .value-item:hover { background: #f8faf9; border-color: #e5e7eb; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    .value-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
    }
    .value-item h5 { font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 6px; }
    .value-item p  { font-size: 0.875rem; color: #6b7280; line-height: 1.65; margin: 0; }

    /* ── Timeline ── */
    .timeline-section { padding: 96px 0; background: #f8faf9; }
    .timeline { position: relative; max-width: 760px; margin: 0 auto; }
    .timeline::before {
        content: '';
        position: absolute; left: 50%; top: 0; bottom: 0;
        width: 2px; background: linear-gradient(to bottom, #10b981, #059669);
        transform: translateX(-50%);
    }
    .timeline-item {
        display: flex; gap: 40px; margin-bottom: 48px; position: relative;
    }
    .timeline-item.right { flex-direction: row-reverse; }
    .timeline-content {
        flex: 1; background: white; border-radius: 16px; padding: 28px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }
    .timeline-content:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .timeline-dot {
        position: absolute; left: 50%; top: 28px;
        transform: translateX(-50%);
        width: 48px; height: 48px; border-radius: 50%;
        background: linear-gradient(135deg, #0d6e3f, #10b981);
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(13,110,63,0.25);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 800; font-size: 0.9rem;
        z-index: 2;
    }
    .timeline-year { font-size: 0.78rem; font-weight: 700; color: #10b981; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px; }
    .timeline-content h5 { font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 6px; }
    .timeline-content p  { font-size: 0.875rem; color: #6b7280; line-height: 1.65; margin: 0; }
    .timeline-spacer { flex: 1; }

    /* ── Shariah Board ── */
    .board-section { padding: 96px 0; background: white; }
    .board-header {
        background: linear-gradient(135deg, #0d6e3f, #065f46);
        border-radius: 24px; padding: 60px 40px;
        color: white; text-align: center; position: relative; overflow: hidden; margin-bottom: 48px;
    }
    .board-header::before {
        content: '';
        position: absolute; top: -60px; right: -60px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.04); border-radius: 50%;
    }
    .board-header h2 { font-size: clamp(1.6rem,3vw,2.4rem); font-weight: 800; margin-bottom: 12px; }
    .board-header p  { color: rgba(255,255,255,0.7); font-size: 1rem; max-width: 560px; margin: 0 auto; }
    .board-member {
        background: #f8faf9; border: 1px solid #e5e7eb;
        border-radius: 20px; padding: 28px 24px; text-align: center;
        transition: all 0.3s;
    }
    .board-member:hover { background: white; box-shadow: 0 8px 32px rgba(0,0,0,0.08); transform: translateY(-4px); }
    .board-avatar {
        width: 72px; height: 72px; border-radius: 50%;
        background: linear-gradient(135deg, #0d6e3f, #10b981);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.5rem; font-weight: 700;
        margin: 0 auto 16px;
        box-shadow: 0 4px 16px rgba(13,110,63,0.2);
    }
    .board-member h5 { font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 4px; }
    .board-member .role { font-size: 0.8rem; color: #10b981; font-weight: 600; margin-bottom: 8px; }
    .board-member p   { font-size: 0.8rem; color: #6b7280; line-height: 1.65; margin: 0; }

    /* ── Partners ── */
    .partners-section { padding: 80px 0; background: #f8faf9; }
    .partner-logo {
        background: white; border: 1px solid #e5e7eb;
        border-radius: 16px; padding: 24px 32px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: #6b7280; font-size: 0.9rem;
        min-height: 80px; transition: all 0.3s;
        text-align: center;
    }
    .partner-logo:hover { border-color: #10b981; color: #0d6e3f; box-shadow: 0 4px 20px rgba(13,110,63,0.08); }

    /* ── CTA ── */
    .about-cta {
        background: linear-gradient(135deg, #1a1a2e 0%, #0d6e3f 100%);
        padding: 96px 0; text-align: center;
    }
    .about-cta h2 { color: white; font-size: clamp(1.8rem,3.5vw,2.8rem); font-weight: 800; margin-bottom: 16px; }
    .about-cta p  { color: rgba(255,255,255,0.7); font-size: 1rem; max-width: 480px; margin: 0 auto 36px; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .timeline::before { left: 24px; }
        .timeline-item, .timeline-item.right { flex-direction: column; padding-left: 60px; }
        .timeline-dot { left: 24px; }
        .timeline-spacer { display: none; }
    }
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<section class="about-hero">
    <div class="container" style="position:relative;z-index:2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="about-badge"><i class="bi bi-info-circle-fill"></i> About CZM Bangladesh</div>
                <h1>Transparent Zakat.<br><span>Transformative Impact.</span></h1>
                <p>We are Bangladesh's premier Shariah-compliant, technology-driven Zakat management platform — ensuring every Taka reaches its rightful recipient with complete accountability.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <div class="hero-stat-pill"><strong>2020</strong><span>Founded</span></div>
                    <div class="hero-stat-pill"><strong>64</strong><span>Districts</span></div>
                    <div class="hero-stat-pill"><strong>৳12 Crore+</strong><span>Distributed</span></div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div style="width:320px;height:320px;border-radius:50%;background:rgba(255,255,255,0.05);border:2px solid rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;position:relative;">
                    <img src="https://cdn-icons-png.flaticon.com/512/5690/5690021.png" alt="CZM Logo" style="height:140px; filter:brightness(0) invert(1);">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── MISSION / VISION / VALUES ── --}}
<section class="mission-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge"><i class="bi bi-bullseye"></i> Our Foundation</div>
            <h2 class="section-title">What Drives Us</h2>
            <p class="section-subtitle mx-auto">Our platform is built on three uncompromising pillars that guide every decision we make.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="mission-card card-green">
                    <div class="mission-icon" style="background:#f0fdf4;color:#10b981;">
                        <i class="bi bi-crosshair2"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To create a seamless, transparent, and Shariah-compliant bridge between Zakat givers and the deserving poor across all 64 districts of Bangladesh — eliminating inefficiency, fraud, and intermediary leakage.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mission-card card-blue">
                    <div class="mission-icon" style="background:#eff6ff;color:#3b82f6;">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>A Bangladesh where no eligible Muslim remains deprived of Zakat, where the institution of Zakat is fully digitized, and where every donor has complete visibility into the impact of their generosity.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mission-card card-amber">
                    <div class="mission-icon" style="background:#fffbeb;color:#f59e0b;">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <h3>Our Promise</h3>
                    <p>100% of every Taka you donate reaches its intended beneficiary. Our administrative costs are covered by government grants and institutional funding — never from your Zakat contributions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CORE VALUES ── --}}
<section class="values-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-4">
                <div class="section-badge"><i class="bi bi-star-fill"></i> Core Values</div>
                <h2 class="section-title">Principles We Live By</h2>
                <p class="section-subtitle">Every feature of our platform is built to honour these six values.</p>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="value-item">
                            <div class="value-icon" style="background:#f0fdf4;color:#10b981;"><i class="bi bi-shield-fill-check"></i></div>
                            <div>
                                <h5>Shariah Compliance</h5>
                                <p>Every process validated by our board of qualified Islamic scholars.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="value-item">
                            <div class="value-icon" style="background:#eff6ff;color:#3b82f6;"><i class="bi bi-eye-fill"></i></div>
                            <div>
                                <h5>Full Transparency</h5>
                                <p>Every transaction is traceable and publicly auditable on our platform.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="value-item">
                            <div class="value-icon" style="background:#fffbeb;color:#f59e0b;"><i class="bi bi-robot"></i></div>
                            <div>
                                <h5>Tech-Driven</h5>
                                <p>AI-assisted beneficiary screening reduces fraud and speeds up aid delivery.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="value-item">
                            <div class="value-icon" style="background:#f5f3ff;color:#8b5cf6;"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <h5>Community First</h5>
                                <p>1,200+ volunteers across Bangladesh form our grassroots verification network.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="value-item">
                            <div class="value-icon" style="background:#fdf2f8;color:#ec4899;"><i class="bi bi-lock-fill"></i></div>
                            <div>
                                <h5>Privacy & Security</h5>
                                <p>Bank-grade encryption protects all donor and beneficiary data.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="value-item">
                            <div class="value-icon" style="background:#f0fdf4;color:#10b981;"><i class="bi bi-graph-up-arrow"></i></div>
                            <div>
                                <h5>Measurable Impact</h5>
                                <p>Real-time dashboards show exactly how donations are making a difference.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── TIMELINE ── --}}
<section class="timeline-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge"><i class="bi bi-clock-history"></i> Our Journey</div>
            <h2 class="section-title">How We Grew</h2>
            <p class="section-subtitle mx-auto">From a pilot project in Dhaka to a nationwide platform serving all 64 districts.</p>
        </div>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">2020</div>
                    <h5>Platform Founded</h5>
                    <p>CZM Bangladesh was established as a pilot in Dhaka with 5 partner mosques and 120 initial donors, collecting ৳18 lakh in Zakat in the first Ramadan season.</p>
                </div>
                <div class="timeline-dot">20</div>
                <div class="timeline-spacer"></div>
            </div>
            <div class="timeline-item right">
                <div class="timeline-spacer"></div>
                <div class="timeline-dot">21</div>
                <div class="timeline-content">
                    <div class="timeline-year">2021</div>
                    <h5>Shariah Board Established</h5>
                    <p>A formal Shariah Advisory Board of 7 qualified scholars was constituted, issuing our first Fatwa certifying the platform's processes as fully compliant with Hanafi fiqh.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">2022</div>
                    <h5>National Expansion</h5>
                    <p>Expanded to all 8 divisions, onboarding 340 partner organizations and launching the volunteer program with 800 field workers covering rural beneficiary verification.</p>
                </div>
                <div class="timeline-dot">22</div>
                <div class="timeline-spacer"></div>
            </div>
            <div class="timeline-item right">
                <div class="timeline-spacer"></div>
                <div class="timeline-dot">23</div>
                <div class="timeline-content">
                    <div class="timeline-year">2023</div>
                    <h5>AI Screening Launched</h5>
                    <p>Deployed machine-learning models for beneficiary eligibility scoring, reducing fraudulent applications by 94% and cutting average verification time from 7 days to 24 hours.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-year">2024</div>
                    <h5>৳12 Crore Milestone</h5>
                    <p>Surpassed ৳12 crore in cumulative Zakat distributed, serving 185,000+ beneficiaries and registering 24,000+ active donors — a 400% growth from launch.</p>
                </div>
                <div class="timeline-dot">24</div>
                <div class="timeline-spacer"></div>
            </div>
            <div class="timeline-item right">
                <div class="timeline-spacer"></div>
                <div class="timeline-dot">25</div>
                <div class="timeline-content">
                    <div class="timeline-year">2025 – Present</div>
                    <h5>Blockchain Anchoring</h5>
                    <p>All disbursement records are now cryptographically anchored to an immutable ledger, providing the highest standard of audit trail for donors and regulators alike.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── SHARIAH ADVISORY BOARD ── --}}
<section class="board-section">
    <div class="container">
        <div class="board-header">
            <div style="font-size:3rem;margin-bottom:16px;">☪️</div>
            <h2>Our Shariah Advisory Board</h2>
            <p>A panel of distinguished Islamic scholars who certify our processes, review every disbursement policy, and ensure CZM Bangladesh operates in full accordance with classical Islamic jurisprudence.</p>
        </div>
        <div class="row g-4">
            <div class="col-sm-6 col-lg-3">
                <div class="board-member">
                    <div class="board-avatar">MA</div>
                    <h5>Mufti Abdullah Al-Hasan</h5>
                    <div class="role">Chairman, Shariah Board</div>
                    <p>PhD in Islamic Jurisprudence, Al-Azhar University. 30+ years in Fiqh al-Zakat research.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="board-member">
                    <div class="board-avatar" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);">MR</div>
                    <h5>Sheikh Mohammad Rashid</h5>
                    <div class="role">Hanafi Fiqh Specialist</div>
                    <p>Principal, Jamia Islamia Dhaka. Expert in contemporary Zakat computation methodologies.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="board-member">
                    <div class="board-avatar" style="background:linear-gradient(135deg,#f59e0b,#d97706);">FI</div>
                    <h5>Dr. Farida Islam</h5>
                    <div class="role">Women's Zakat Rights</div>
                    <p>Associate Professor, Islamic Studies, Dhaka University. Expert in female-headed household Zakat eligibility.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="board-member">
                    <div class="board-avatar" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);">SK</div>
                    <h5>Mawlana Sulaiman Khan</h5>
                    <div class="role">Digital Finance Halal Audit</div>
                    <p>Certified Islamic Finance Advisor. Specialist in halal digital payment systems and blockchain Zakat distribution.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── PARTNERS ── --}}
<section class="partners-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge"><i class="bi bi-building-fill"></i> Partners & Affiliations</div>
            <h2 class="section-title">Who We Work With</h2>
        </div>
        <div class="row g-4">
            @foreach([
                'Islamic Foundation Bangladesh',
                'Bangladesh Bank — Payment Division',
                'BRAC Microfinance Division',
                'Islami Bank Bangladesh Limited',
                'Ministry of Religious Affairs',
                'Bangladesh NGO Bureau',
                'Grameen Bank Social Enterprise',
                'Al-Arafah Islami Bank'
            ] as $partner)
            <div class="col-6 col-md-3">
                <div class="partner-logo">{{ $partner }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="about-cta">
    <div class="container" style="position:relative;z-index:2;">
        <div class="section-badge" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);margin-bottom:24px;">
            <i class="bi bi-stars"></i> Join the Movement
        </div>
        <h2>Be Part of Something Greater</h2>
        <p>Whether you're a donor, beneficiary, volunteer, or organization — there's a place for you in our mission.</p>
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <a href="{{ url('/donor/register') }}" class="btn-pub-primary" style="padding:14px 32px;font-size:1rem;">
                <i class="bi bi-heart-fill"></i> Pay Zakat Now
            </a>
            <a href="{{ route('public.how') }}" class="btn-pub-outline" style="padding:14px 28px;font-size:1rem;background:transparent;color:white;border-color:rgba(255,255,255,0.4);">
                <i class="bi bi-play-circle-fill"></i> How It Works
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function applyPageTranslations(lang) {}
</script>
@endpush
