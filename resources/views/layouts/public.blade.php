<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Central Zakat Management Platform - Bangladesh')">
    <title>@yield('title', 'CZM Bangladesh') | Central Zakat Management</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Noto+Sans+Bengali:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --pub-primary: #0d6e3f;
            --pub-primary-light: #16a363;
            --pub-primary-dark: #084d2c;
            --pub-accent: #f0a500;
            --pub-accent-light: #fbbf24;
            --pub-text: #1a1a2e;
            --pub-text-secondary: #4b5563;
            --pub-text-muted: #9ca3af;
            --pub-bg: #ffffff;
            --pub-bg-soft: #f8faf9;
            --pub-bg-card: #ffffff;
            --pub-border: #e5e7eb;
            --pub-border-soft: #f3f4f6;
            --pub-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.04);
            --pub-shadow-md: 0 4px 20px rgba(0,0,0,0.08), 0 8px 32px rgba(0,0,0,0.06);
            --pub-shadow-lg: 0 8px 40px rgba(0,0,0,0.10), 0 16px 64px rgba(0,0,0,0.06);
            --pub-radius: 12px;
            --pub-radius-lg: 20px;
            --pub-navbar-h: 72px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        html { scroll-behavior: smooth; }
        
        body {
            font-family: 'Inter', 'Noto Sans Bengali', 'Poppins', sans-serif;
            background: var(--pub-bg);
            color: var(--pub-text);
            line-height: 1.7;
            font-size: 15px;
        }

        /* ── Navbar ── */
        .pub-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: var(--pub-navbar-h);
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--pub-border);
            transition: box-shadow 0.3s ease;
        }
        .pub-navbar.scrolled {
            box-shadow: 0 4px 24px rgba(13,110,63,0.08);
        }
        .pub-navbar .container {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .pub-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .pub-brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--pub-primary), var(--pub-primary-light));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .pub-brand-text .brand-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--pub-primary);
            display: block;
            line-height: 1.2;
        }
        .pub-brand-text .brand-sub {
            font-size: 0.72rem;
            color: var(--pub-text-muted);
            display: block;
            letter-spacing: 0.02em;
        }

        .pub-nav-links {
            display: flex;
            align-items: center;
            gap: 2px;
            list-style: none;
        }
        .pub-nav-links a {
            padding: 8px 13px;
            border-radius: 8px;
            color: var(--pub-text-secondary);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .pub-nav-links a:hover, .pub-nav-links a.active {
            background: rgba(13,110,63,0.08);
            color: var(--pub-primary);
        }
        .pub-nav-links a.active {
            font-weight: 700;
        }

        .pub-nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-pub-outline {
            padding: 8px 18px;
            border: 1.5px solid var(--pub-primary);
            border-radius: 8px;
            color: var(--pub-primary);
            background: transparent;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-pub-outline:hover {
            background: var(--pub-primary);
            color: white;
        }
        .btn-pub-primary {
            padding: 8px 20px;
            background: linear-gradient(135deg, var(--pub-primary), var(--pub-primary-light));
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(13,110,63,0.2);
        }
        .btn-pub-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(13,110,63,0.3);
            color: white;
        }
        .lang-toggle {
            padding: 6px 12px;
            background: var(--pub-bg-soft);
            border: 1px solid var(--pub-border);
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--pub-text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .lang-toggle:hover {
            background: var(--pub-primary);
            color: white;
            border-color: var(--pub-primary);
        }

        /* ── Hamburger ── */
        .pub-hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: var(--pub-text);
            cursor: pointer;
        }
        .pub-mobile-menu {
            display: none;
            position: fixed;
            top: var(--pub-navbar-h);
            left: 0;
            right: 0;
            background: white;
            border-bottom: 1px solid var(--pub-border);
            padding: 16px;
            z-index: 999;
            box-shadow: var(--pub-shadow-md);
        }
        .pub-mobile-menu.open { display: block; }
        .pub-mobile-menu a {
            display: block;
            padding: 12px 16px;
            color: var(--pub-text-secondary);
            text-decoration: none;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 4px;
        }
        .pub-mobile-menu a:hover { background: var(--pub-bg-soft); color: var(--pub-primary); }

        /* ── Page content ── */
        .pub-page {
            padding-top: var(--pub-navbar-h);
            min-height: 100vh;
        }

        /* ── Footer ── */
        .pub-footer {
            background: var(--pub-text);
            color: rgba(255,255,255,0.8);
        }
        .pub-footer-top {
            padding: 64px 0 40px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .pub-footer-brand .brand-name { color: #10b981; font-size: 1.1rem; font-weight: 700; }
        .pub-footer-brand p { color: rgba(255,255,255,0.55); font-size: 0.875rem; margin-top: 8px; max-width: 280px; }
        .pub-footer h6 { color: white; font-weight: 700; font-size: 0.875rem; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 16px; }
        .pub-footer ul { list-style: none; }
        .pub-footer ul li { margin-bottom: 8px; display: flex; align-items: center; }
        .pub-footer ul a { color: rgba(255,255,255,0.55); text-decoration: none; font-size: 0.875rem; transition: color 0.2s; display: flex; align-items: center; }
        .pub-footer ul i { width: 22px; text-align: left; flex-shrink: 0; }
        .pub-footer ul a:hover { color: #10b981; }
        .pub-footer-bottom {
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .pub-footer-bottom p { color: rgba(255,255,255,0.4); font-size: 0.8rem; margin: 0; }
        .pub-footer-badges { display: flex; gap: 8px; }
        .pub-footer-badges span {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.5);
            font-size: 0.72rem;
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* ── Cards ── */
        .pub-card {
            background: white;
            border-radius: var(--pub-radius);
            border: 1px solid var(--pub-border);
            box-shadow: var(--pub-shadow);
            padding: 28px;
            transition: all 0.3s ease;
        }
        .pub-card:hover {
            box-shadow: var(--pub-shadow-md);
            transform: translateY(-2px);
        }

        /* ── Section headings ── */
        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(13,110,63,0.08);
            color: var(--pub-primary);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 16px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .section-title {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 800;
            color: var(--pub-text);
            line-height: 1.25;
            margin-bottom: 12px;
        }
        .section-subtitle {
            font-size: 1rem;
            color: var(--pub-text-secondary);
            max-width: 560px;
            line-height: 1.7;
        }

        /* ── Forms ── */
        .pub-form-label { font-weight: 600; font-size: 0.875rem; color: var(--pub-text); margin-bottom: 6px; display: block; }
        .pub-form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--pub-border);
            border-radius: 8px;
            font-size: 0.875rem;
            color: var(--pub-text);
            background: white;
            transition: all 0.2s ease;
            outline: none;
        }
        .pub-form-control:focus {
            border-color: var(--pub-primary);
            box-shadow: 0 0 0 3px rgba(13,110,63,0.1);
        }
        .pub-form-hint { font-size: 0.8rem; color: var(--pub-text-muted); margin-top: 4px; }
        
        /* ── Alert / Flash ── */
        .pub-alert {
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 0.9rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }
        .pub-alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .pub-alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .pub-alert-info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }

        /* ── Steps ── */
        .step-badge {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--pub-primary), var(--pub-primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .pub-nav-links, .pub-nav-actions { display: none; }
            .pub-hamburger { display: block; }
        }
        @media (max-width: 768px) {
            .section-title { font-size: 1.6rem; }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Navigation --}}
    <nav class="pub-navbar" id="pubNavbar">
        <div class="container">
            <a href="{{ url('/') }}" class="pub-brand">
                <div class="pub-brand-icon">
                    <i class="bi bi-moon-stars-fill"></i>
                </div>
                <div class="pub-brand-text">
                    <span class="brand-name" id="navBrandName">CZM Bangladesh</span>
                    <span class="brand-sub" id="navBrandSub">Zakat Management Portal</span>
                </div>
            </a>

            <ul class="pub-nav-links" id="pubNavLinks">
                <li><a href="{{ url('/') }}" id="navHome" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('about') || request()->is('how-it-works') ? 'active' : '' }}" href="#" id="navbarDropdownAbout" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        About Us
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm mt-2" aria-labelledby="navbarDropdownAbout" style="border-radius: 12px;">
                        <li><a class="dropdown-item" href="{{ route('public.about') }}">About CZM</a></li>
                        <li><a class="dropdown-item" href="{{ route('public.how') }}">How It Works</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('public.calculator') }}" id="navCalc" class="{{ request()->is('zakat-calculator') ? 'active' : '' }}">Calculator</a></li>
                <li><a href="{{ route('public.track') }}" id="navTrack" class="{{ request()->is('track*') ? 'active' : '' }}">Track</a></li>
                <li><a href="{{ route('leaderboard') }}" id="navLeader" class="{{ request()->is('leaderboard') ? 'active' : '' }}">Leaderboard</a></li>
                <li><a href="{{ route('public.contact') }}" id="navContact" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>
            </ul>

            <div class="pub-nav-actions">
                <button class="lang-toggle" id="langToggleBtn" onclick="toggleLang()">বাং</button>
                @auth
                <a href="{{ route('dashboard') }}" class="btn-pub-outline" id="navLogin"><i class="bi bi-speedometer2"></i> <span id="navLoginTxt">Dashboard</span></a>
                @else
                <a href="{{ url('/login') }}" class="btn-pub-outline" id="navLogin"><i class="bi bi-person"></i> <span id="navLoginTxt">Login</span></a>
                @endauth
                <a href="{{ url('/donor/register') }}" class="btn-pub-primary" id="navRegister"><i class="bi bi-heart-fill"></i> <span id="navRegisterTxt">Give Zakat</span></a>
            </div>

            <button class="pub-hamburger" onclick="toggleMobile()"><i class="bi bi-list"></i></button>
        </div>
    </nav>

    {{-- Mobile Menu --}}
    <div class="pub-mobile-menu" id="pubMobileMenu">
        <a href="{{ url('/') }}">🏠 Home</a>
        <a href="{{ route('public.about') }}">ℹ️ About Us</a>
        <a href="{{ route('public.how') }}">⚙️ How It Works</a>
        <a href="{{ route('public.calculator') }}">🧮 Zakat Calculator</a>
        <a href="{{ route('public.track') }}">🔍 Track Application</a>
        <a href="{{ route('leaderboard') }}">🏆 Leaderboard</a>
        <a href="{{ route('public.contact') }}">📞 Contact Us</a>
        <hr style="border-color: var(--pub-border); margin: 8px 0;">
        @auth
        <a href="{{ route('dashboard') }}" style="color: var(--pub-primary); font-weight: 600;">Dashboard</a>
        @else
        <a href="{{ url('/login') }}" style="color: var(--pub-primary); font-weight: 600;">Login / Dashboard</a>
        @endauth
        <a href="{{ url('/donor/register') }}" style="background: var(--pub-primary); color: white; text-align: center; margin-top: 8px;">Give Zakat →</a>
    </div>

    {{-- Main Content --}}
    <div class="pub-page">
        @if(session('success'))
        <div class="container pt-4">
            <div class="pub-alert pub-alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="container pt-4">
            <div class="pub-alert pub-alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div>{{ session('error') }}</div>
            </div>
        </div>
        @endif

        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="pub-footer">
        <div class="pub-footer-top">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-4">
                        <div class="pub-footer-brand">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div style="width:36px;height:36px;background:linear-gradient(135deg,#10b981,#059669);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;">
                                    <i class="bi bi-moon-stars-fill"></i>
                                </div>
                                <span class="brand-name">CZM Bangladesh</span>
                            </div>
                            <p id="footerDesc">Central Zakat Management Platform — A Shariah-compliant, transparent, and technology-driven Zakat distribution system.</p>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <a href="#" style="width:36px;height:36px;background:rgba(255,255,255,0.06);border-radius:8px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.5);text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#10b981';this.style.color='white'" onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='rgba(255,255,255,0.5)'"><i class="bi bi-facebook"></i></a>
                            <a href="#" style="width:36px;height:36px;background:rgba(255,255,255,0.06);border-radius:8px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.5);text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#10b981';this.style.color='white'" onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='rgba(255,255,255,0.5)'"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" style="width:36px;height:36px;background:rgba(255,255,255,0.06);border-radius:8px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.5);text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#10b981';this.style.color='white'" onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='rgba(255,255,255,0.5)'"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                    <div class="col-6 col-lg-2">
                        <h6>Portal</h6>
                        <ul class="ps-0">
                            <li><a href="{{ url('/') }}"><i class="bi bi-house me-2"></i>Home</a></li>
                            <li><a href="{{ route('public.about') }}"><i class="bi bi-info-circle me-2"></i>About Us</a></li>
                            <li><a href="{{ route('public.how') }}"><i class="bi bi-gear me-2"></i>How It Works</a></li>
                            <li><a href="{{ route('public.calculator') }}"><i class="bi bi-calculator me-2"></i>Zakat Calculator</a></li>
                            <li><a href="{{ route('public.track') }}"><i class="bi bi-search me-2"></i>Track Application</a></li>
                            <li><a href="{{ route('public.organizations') }}"><i class="bi bi-building-check me-2"></i>Organizations</a></li>
                            <li><a href="{{ route('public.volunteers') }}"><i class="bi bi-person-badge me-2"></i>Volunteers</a></li>
                            <li><a href="{{ route('leaderboard') }}"><i class="bi bi-trophy me-2"></i>Leaderboard</a></li>
                            <li><a href="{{ route('public.transparency') }}"><i class="bi bi-bar-chart-fill me-2"></i>Transparency</a></li>
                            <li><a href="{{ route('public.contact') }}"><i class="bi bi-envelope me-2"></i>Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-lg-2">
                        <h6>Register</h6>
                        <ul class="ps-0">
                            <li><a href="{{ url('/donor/register') }}"><i class="bi bi-heart me-2"></i>Pay Zakat</a></li>
                            <li><a href="{{ url('/apply') }}"><i class="bi bi-hand-index me-2"></i>Apply for Aid</a></li>
                            <li><a href="{{ url('/volunteer/register') }}"><i class="bi bi-people me-2"></i>Volunteer</a></li>
                            <li><a href="{{ url('/organization/register') }}"><i class="bi bi-building me-2"></i>Organization</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <h6>Contact</h6>
                        <ul class="ps-0">
                            <li><a href="tel:16789"><i class="bi bi-telephone me-2"></i>16789 (Helpline)</a></li>
                            <li><a href="mailto:info@czm.gov.bd"><i class="bi bi-envelope me-2"></i>info@czm.gov.bd</a></li>
                            <li style="color:rgba(255,255,255,0.45);font-size:0.875rem;"><i class="bi bi-geo-alt me-2"></i>National Zakat Center, Dhaka-1200</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container pub-footer-bottom">
            <p id="footerCopyright">© {{ date('Y') }} Central Zakat Management Platform, Bangladesh. All rights reserved.</p>
            <div class="pub-footer-badges">
                <span>Shariah Compliant</span>
                <span>SSL Secured</span>
                <span>Gov. Verified</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('pubNavbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });

        // Mobile menu toggle
        function toggleMobile() {
            document.getElementById('pubMobileMenu').classList.toggle('open');
        }

        // Language toggle
        let currentLang = 'en';
        const translations = {
            navBrandName:    { en: 'CZM Bangladesh', bn: 'সিজেডএম বাংলাদেশ' },
            navBrandSub:     { en: 'Zakat Management Portal', bn: 'যাকাত ব্যবস্থাপনা পোর্টাল' },
            navHome:         { en: 'Home', bn: 'হোম' },
            navAbout:        { en: 'About', bn: 'পরিচিতি' },
            navHow:          { en: 'How It Works', bn: 'কীভাবে কাজ করে' },
            navCalc:         { en: 'Calculator', bn: 'ক্যালকুলেটর' },
            navTrack:        { en: 'Track', bn: 'আবেদন ট্র্যাকিং' },
            navLeader:       { en: 'Leaderboard', bn: 'লিডারবোর্ড' },
            navContact:      { en: 'Contact', bn: 'যোগাযোগ' },
            navLoginTxt:     { en: "{{ Auth::check() ? 'Dashboard' : 'Login' }}", bn: "{{ Auth::check() ? 'ড্যাশবোর্ড' : 'লগইন' }}" },
            navRegisterTxt:  { en: 'Give Zakat', bn: 'যাকাত দিন' },
        };

        function applyTranslations(lang) {
            Object.keys(translations).forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = translations[id][lang];
            });
            // Apply page-level translations if defined
            if (typeof applyPageTranslations === 'function') applyPageTranslations(lang);
            document.getElementById('langToggleBtn').textContent = lang === 'en' ? 'বাং' : 'ENG';
        }

        function toggleLang() {
            currentLang = currentLang === 'en' ? 'bn' : 'en';
            applyTranslations(currentLang);
        }
    </script>

    @stack('scripts')
</body>
</html>
