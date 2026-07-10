@extends('layouts.public')

@section('title', 'CZM Leaderboard | টপ পারফর্মার — CZM Bangladesh')

@php
if (!function_exists('amountBand')) {
    function amountBand($amount, $lang = 'en') {
        if ($lang === 'bn') {
            if ($amount < 10000) return '৳০–১০ হাজার';
            if ($amount < 50000) return '৳১০–৫০ হাজার';
            if ($amount < 100000) return '৳৫০ হাজার–১ লক্ষ';
            if ($amount < 500000) return '৳১–৫ লক্ষ';
            if ($amount < 1000000) return '৳৫–১০ লক্ষ';
            return '৳১০ লক্ষ+';
        } else {
            if ($amount < 10000) return '৳0–10K';
            if ($amount < 50000) return '৳10K–50K';
            if ($amount < 100000) return '৳50K–1L';
            if ($amount < 500000) return '৳1L–5L';
            if ($amount < 1000000) return '৳5L–10L';
            return '৳10L+';
        }
    }
}
@endphp

@push('styles')
<style>
    .leaderboard-hero { background: linear-gradient(135deg, #0a4f2e 0%, #065f46 50%, #0d6e3f 100%); padding: 60px 0 40px; color: white; text-align: center; }
    .leaderboard-hero h1 { font-size: clamp(2rem,4vw,3rem); font-weight: 800; margin-bottom: 16px; }
    .stat-card { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; padding: 24px; text-align: center; backdrop-filter: blur(10px); }
    .stat-card .num { font-size: 2.2rem; font-weight: 800; color: #facc15; line-height: 1; margin-bottom: 8px; }
    .stat-card .lbl { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.9; }
    
    .rank-card { background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); transition: all 0.2s; }
    .rank-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.06); }
    .rank-badge { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; flex-shrink: 0; }
    .rank-1 { background: linear-gradient(135deg, #fef08a, #eab308); color: #713f12; box-shadow: 0 4px 12px rgba(234,179,8,0.3); }
    .rank-2 { background: linear-gradient(135deg, #e5e7eb, #9ca3af); color: #1f2937; }
    .rank-3 { background: linear-gradient(135deg, #fed7aa, #f97316); color: #7c2d12; }
    .rank-other { background: #f3f4f6; color: #6b7280; }
    
    .rank-info { flex-grow: 1; }
    .rank-name { font-size: 1.1rem; font-weight: 700; color: #111827; margin-bottom: 4px; }
    .rank-sub { font-size: 0.82rem; color: #6b7280; display: flex; align-items: center; gap: 12px; }
    
    .rank-amount { text-align: right; }
    .rank-amount .val { font-size: 1.2rem; font-weight: 800; color: #0d6e3f; }
    .rank-amount .lbl { font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; }
</style>
@endpush

@section('content')
<div class="leaderboard-hero">
    <div class="container">
        <div style="font-size:3rem;margin-bottom:16px;">🏆</div>
        <h1 id="leaderboardTitle">Zakat Collection Leaderboard</h1>
        <p id="leaderboardSub" style="font-size:1.1rem;opacity:0.85;max-width:600px;margin:0 auto 20px;">Recognizing the outstanding efforts of our partner organizations and dedicated volunteers in raising Zakat funds.</p>
        
        <div style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:12px;padding:12px 18px;max-width:640px;margin:0 auto 40px;font-size:0.82rem;display:flex;align-items:center;gap:10px;justify-content:center;">
            <i class="bi bi-shield-lock-fill" style="color:#facc15;font-size:1.1rem;"></i>
            <span class="privacyNoticeTxt">To protect donor privacy, individual raised amounts are shown in range bands instead of exact values. No personal donor wealth can be inferred.</span>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-md-3 col-6"><div class="stat-card"><div class="num">৳{{ number_format($totalCollected) }}</div><div class="lbl" id="lblTotalOnline">Total Online Zakat</div></div></div>
            <div class="col-md-3 col-6"><div class="stat-card"><div class="num" style="color:#60a5fa;">{{ number_format($totalDonors) }}</div><div class="lbl" id="lblUniqueDonors">Unique Donors</div></div></div>
            <div class="col-md-3 col-6"><div class="stat-card"><div class="num" style="color:#34d399;">{{ number_format($totalOrgs) }}</div><div class="lbl" id="lblPartnerOrgs">Partner Orgs</div></div></div>
            <div class="col-md-3 col-6"><div class="stat-card"><div class="num" style="color:#f472b6;">{{ number_format($totalVolunteers) }}</div><div class="lbl" id="lblActiveVolunteers">Active Volunteers</div></div></div>
        </div>
    </div>
</div>

<div style="background:#f8faf9;padding:60px 0;">
    <div class="container">
        <div class="row g-5">
            {{-- Top Organizations --}}
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="width:48px;height:48px;background:#f3e8ff;color:#7c3aed;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;"><i class="bi bi-building-fill"></i></div>
                    <h3 style="font-weight:800;margin:0;color:#111827;" id="lblTopOrgs">Top Organizations</h3>
                </div>
                
                @forelse($topOrgs as $index => $org)
                <div class="rank-card">
                    <div class="rank-badge {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : 'rank-other')) }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="rank-info">
                        <div class="rank-name">{{ $org->name_en }}</div>
                        <div class="rank-sub">
                            <span><i class="bi bi-geo-alt-fill text-muted me-1"></i>{{ $org->district }}</span>
                            <span><i class="bi bi-people-fill text-muted me-1"></i>{{ $org->total_donors_via_referral }} <span class="lblDonors">Donors</span></span>
                        </div>
                    </div>
                    <div class="rank-amount">
                        <div class="val">
                            <span class="band-en">{{ amountBand($org->total_collected_via_referral, 'en') }}</span>
                            <span class="band-bn" style="display:none;">{{ amountBand($org->total_collected_via_referral, 'bn') }}</span>
                        </div>
                        <div class="lbl lblRaised">Raised</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5" style="background:white;border-radius:16px;border:1px dashed #e5e7eb;">
                    <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted" id="lblNoOrg">No organizational collections yet.</h6>
                </div>
                @endforelse
            </div>
            
            {{-- Top Volunteers --}}
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="width:48px;height:48px;background:#fef3c7;color:#d97706;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;"><i class="bi bi-person-badge-fill"></i></div>
                    <h3 style="font-weight:800;margin:0;color:#111827;" id="lblTopVolunteers">Top Volunteers</h3>
                </div>
                
                @forelse($topVolunteers as $index => $vol)
                <div class="rank-card">
                    <div class="rank-badge {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : 'rank-other')) }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="rank-info">
                        <div class="rank-name">{{ $vol->name_en }}</div>
                        <div class="rank-sub">
                            <span><i class="bi bi-building text-muted me-1"></i>{{ $vol->organization ? $vol->organization->name_en : 'Independent' }}</span>
                            <span><i class="bi bi-people-fill text-muted me-1"></i>{{ $vol->total_donors_via_referral }} <span class="lblDonors">Donors</span></span>
                        </div>
                    </div>
                    <div class="rank-amount">
                        <div class="val">
                            <span class="band-en">{{ amountBand($vol->total_collected_via_referral, 'en') }}</span>
                            <span class="band-bn" style="display:none;">{{ amountBand($vol->total_collected_via_referral, 'bn') }}</span>
                        </div>
                        <div class="lbl lblRaised">Raised</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5" style="background:white;border-radius:16px;border:1px dashed #e5e7eb;">
                    <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted" id="lblNoVol">No volunteer collections yet.</h6>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const pageTranslations = {
        leaderboardTitle: { en: 'Zakat Collection Leaderboard', bn: 'যাকাত সংগ্রহ লিডারবোর্ড' },
        leaderboardSub: { en: 'Recognizing the outstanding efforts of our partner organizations and dedicated volunteers in raising Zakat funds.', bn: 'যাকাত তহবিল সংগ্রহে আমাদের সহযোগী সংস্থা এবং নিবেদিতপ্রাণ স্বেচ্ছাসেবকদের অসামান্য প্রচেষ্টার স্বীকৃতি।' },
        privacyNotice: {
            en: 'To protect donor privacy, individual raised amounts are shown in range bands instead of exact values. No personal donor wealth can be inferred.',
            bn: 'দাতাদের গোপনীয়তা রক্ষার স্বার্থে, ব্যক্তিগতভাবে সংগৃহীত যাকাতের পরিমাণ সুনির্দিষ্ট সংখ্যার পরিবর্তে রেঞ্জ ব্যান্ডে প্রদর্শন করা হয়েছে। দাতাদের মোট সম্পদের কোনো তথ্য এর মাধ্যমে প্রকাশ পায় না।'
        },
        lblTotalOnline: { en: 'Total Online Zakat', bn: 'মোট অনলাইন যাকাত সংগ্রহ' },
        lblUniqueDonors: { en: 'Unique Donors', bn: 'মোট অনন্য দাতা' },
        lblPartnerOrgs: { en: 'Partner Orgs', bn: 'সহযোগী সংস্থাসমূহ' },
        lblActiveVolunteers: { en: 'Active Volunteers', bn: 'সক্রিয় স্বেচ্ছাসেবকবৃন্দ' },
        lblTopOrgs: { en: 'Top Organizations', bn: 'সেরা সংস্থাসমূহ' },
        lblTopVolunteers: { en: 'Top Volunteers', bn: 'সেরা স্বেচ্ছাসেবকবৃন্দ' },
        lblNoOrg: { en: 'No organizational collections yet.', bn: 'কোনো প্রাতিষ্ঠানিক সংগ্রহ এখনো পাওয়া যায়নি।' },
        lblNoVol: { en: 'No volunteer collections yet.', bn: 'কোনো স্বেচ্ছাসেবক সংগ্রহ এখনো পাওয়া যায়নি।' }
    };

    function applyPageTranslations(lang) {
        // Text strings
        Object.keys(pageTranslations).forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = pageTranslations[id][lang];
        });

        document.querySelectorAll('.privacyNoticeTxt').forEach(el => {
            el.textContent = pageTranslations.privacyNotice[lang];
        });

        document.querySelectorAll('.lblRaised').forEach(el => {
            el.textContent = lang === 'en' ? 'Raised' : 'সংগৃহীত';
        });

        document.querySelectorAll('.lblDonors').forEach(el => {
            el.textContent = lang === 'en' ? 'Donors' : 'দাতা';
        });
        
        document.querySelectorAll('.band-en').forEach(el => el.style.display = lang === 'en' ? 'inline' : 'none');
        document.querySelectorAll('.band-bn').forEach(el => el.style.display = lang === 'bn' ? 'inline' : 'none');
    }
</script>
@endpush
