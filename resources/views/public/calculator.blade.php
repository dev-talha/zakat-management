@extends('layouts.public')

@section('title', 'Zakat Calculator | যাকাত ক্যালকুলেটর — CZM Bangladesh')
@section('meta_description', 'Calculate your Zakat obligation accurately with our free Shariah-certified Zakat calculator. Covers cash, gold, silver, business inventory, and liabilities.')

@push('styles')
<style>
    /* ── Hero ── */
    .calc-hero {
        background: linear-gradient(135deg, #064e3b 0%, #0d6e3f 50%, #065f46 100%);
        padding: 100px 0 80px; color: white;
        position: relative; overflow: hidden;
    }
    .calc-hero::before {
        content: '';
        position: absolute; top: -80px; right: -80px;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
    }
    .calc-hero::after {
        content: '';
        position: absolute; bottom: -60px; left: -60px;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(240,165,0,0.08) 0%, transparent 70%);
    }
    .calc-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.9); font-size: 0.82rem; font-weight: 600;
        padding: 6px 18px; border-radius: 100px; margin-bottom: 24px;
    }
    .calc-hero h1 {
        font-size: clamp(2.2rem, 5vw, 3.5rem); font-weight: 900;
        line-height: 1.15; margin-bottom: 20px; color: white;
    }
    .calc-hero h1 span { color: #fbbf24; }
    .calc-hero p { color: rgba(255,255,255,0.72); font-size: 1.05rem; max-width: 520px; line-height: 1.75; }
    .nisab-pill {
        display: inline-flex; align-items: center; gap: 10px;
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50px; padding: 10px 20px; margin-top: 24px; color: white;
    }
    .nisab-pill .label { font-size: 0.82rem; opacity: 0.75; }
    .nisab-pill .value { font-size: 1.1rem; font-weight: 800; color: #fbbf24; }

    /* ── Calculator ── */
    .calculator-section { padding: 80px 0; background: #f8faf9; }
    .calc-main-card {
        background: white; border-radius: 24px; padding: 48px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 8px 48px rgba(0,0,0,0.08);
    }
    .asset-group {
        border: 1.5px solid #e5e7eb; border-radius: 14px; padding: 20px 24px;
        margin-bottom: 12px; display: flex; align-items: center; gap: 16px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .asset-group:focus-within {
        border-color: #10b981; background: #f0fdf4;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
    }
    .asset-group.liability:focus-within {
        border-color: #ef4444; background: #fef2f2;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
    }
    .asset-emoji { font-size: 1.5rem; flex-shrink: 0; width: 40px; text-align: center; }
    .asset-label { font-size: 0.875rem; font-weight: 600; color: #374151; min-width: 160px; }
    .asset-label small { display: block; font-size: 0.75rem; font-weight: 400; color: #9ca3af; }
    .asset-input {
        flex: 1; border: none; background: transparent;
        font-size: 1.05rem; font-weight: 700; color: #111827;
        outline: none; text-align: right;
        min-width: 0;
    }
    .asset-input::placeholder { color: #d1d5db; font-weight: 400; }
    .asset-unit { font-size: 0.82rem; color: #9ca3af; font-weight: 500; margin-left: 8px; flex-shrink: 0; }

    /* Result box */
    .result-box {
        background: linear-gradient(135deg, #0d6e3f, #10b981);
        border-radius: 20px; padding: 36px;
        color: white; margin-top: 24px;
        position: relative; overflow: hidden;
    }
    .result-box::before {
        content: '';
        position: absolute; top: -40px; right: -40px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,0.06); border-radius: 50%;
    }
    .result-label { font-size: 0.82rem; opacity: 0.8; margin-bottom: 4px; }
    .result-amount { font-size: 3rem; font-weight: 900; display: block; line-height: 1; }
    .result-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1);
        font-size: 0.875rem;
    }
    .result-row:last-child { border-bottom: none; }
    .result-row .r-label { opacity: 0.75; }
    .result-row .r-val   { font-weight: 700; }
    .nisab-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700;
    }

    /* Sidebar info */
    .info-section { padding: 80px 0; background: white; }
    .nisab-info-card {
        background: linear-gradient(135deg, #fffbeb, #fef9e7);
        border: 1px solid #fde68a; border-radius: 20px; padding: 28px;
        margin-bottom: 20px;
    }
    .asset-type-card {
        background: #f8faf9; border: 1px solid #e5e7eb;
        border-radius: 16px; padding: 20px; margin-bottom: 12px;
    }
    .asset-type-card h6 { font-weight: 700; color: #111827; margin-bottom: 6px; font-size: 0.9rem; }
    .asset-type-card p  { font-size: 0.82rem; color: #6b7280; line-height: 1.6; margin: 0; }

    /* ── Shariah Notes ── */
    .shariah-section { padding: 80px 0; background: #f8faf9; }
    .shariah-note {
        display: flex; gap: 16px; align-items: flex-start;
        background: white; border-radius: 16px; padding: 24px;
        border: 1px solid #e5e7eb; margin-bottom: 14px;
        transition: all 0.3s;
    }
    .shariah-note:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
    .note-icon { font-size: 1.4rem; flex-shrink: 0; }
    .shariah-note h6 { font-weight: 700; color: #111827; margin-bottom: 4px; font-size: 0.9rem; }
    .shariah-note p  { font-size: 0.85rem; color: #6b7280; line-height: 1.65; margin: 0; }
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<section class="calc-hero">
    <div class="container" style="position:relative;z-index:2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="calc-badge"><i class="bi bi-calculator-fill"></i> Shariah-Certified Tool</div>
                <h1>Calculate Your<br><span>Zakat</span> Accurately</h1>
                <p>Our free, Shariah-certified calculator determines your exact Zakat obligation based on current Nisab rates. Covers all asset types and deducts eligible liabilities.</p>
                <div class="nisab-pill">
                    <i class="bi bi-gem"></i>
                    <div>
                        <div class="label">Current Nisab (Gold 85g) — Updated Daily</div>
                        <div class="value">৳ 85,000 <span style="font-size:0.8rem;font-weight:400;opacity:0.75;">approx.</span></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-flex justify-content-center">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:340px;width:100%;">
                    @foreach([
                        ['💵','Cash & Savings','Any bank balance'],
                        ['🪙','Gold','Jewelry & bullion'],
                        ['🥈','Silver','Any silver assets'],
                        ['🏪','Business','Inventory & stock'],
                    ] as $a)
                    <div style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:16px;padding:20px;text-align:center;">
                        <div style="font-size:1.8rem;margin-bottom:8px;">{{ $a[0] }}</div>
                        <div style="font-weight:700;font-size:0.875rem;color:white;">{{ $a[1] }}</div>
                        <div style="font-size:0.75rem;color:rgba(255,255,255,0.55);margin-top:3px;">{{ $a[2] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CALCULATOR ── --}}
<section class="calculator-section">
    <div class="container">
        <div class="row g-5">

            {{-- Main Calculator --}}
            <div class="col-lg-7">
                <div class="calc-main-card">
                    <h3 style="font-weight:800;color:#111827;margin-bottom:8px;">
                        <i class="bi bi-calculator-fill me-2" style="color:#10b981;"></i>
                        Zakat Calculator (যাকাত ক্যালকুলেটর)
                    </h3>
                    <p style="color:#6b7280;font-size:0.875rem;margin-bottom:28px;">Enter your asset values in Bangladeshi Taka (BDT). Leave fields blank if not applicable.</p>

                    <div style="font-size:0.78rem;font-weight:700;color:#10b981;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:12px;">
                        <i class="bi bi-plus-circle me-1"></i> Zakatable Assets
                    </div>

                    <div class="asset-group">
                        <div class="asset-emoji">💵</div>
                        <div class="asset-label">Cash & Bank Deposits<small>Current accounts, savings, fixed deposits</small></div>
                        <input type="number" id="cash" class="asset-input" placeholder="0" min="0" oninput="calcZakat()">
                        <span class="asset-unit">BDT</span>
                    </div>
                    <div class="asset-group">
                        <div class="asset-emoji">🪙</div>
                        <div class="asset-label">Gold Value<small>Market value of all gold owned</small></div>
                        <input type="number" id="gold" class="asset-input" placeholder="0" min="0" oninput="calcZakat()">
                        <span class="asset-unit">BDT</span>
                    </div>
                    <div class="asset-group">
                        <div class="asset-emoji">🥈</div>
                        <div class="asset-label">Silver Value<small>Market value of all silver owned</small></div>
                        <input type="number" id="silver" class="asset-input" placeholder="0" min="0" oninput="calcZakat()">
                        <span class="asset-unit">BDT</span>
                    </div>
                    <div class="asset-group">
                        <div class="asset-emoji">🏪</div>
                        <div class="asset-label">Business Inventory<small>Stock for sale at market value</small></div>
                        <input type="number" id="business" class="asset-input" placeholder="0" min="0" oninput="calcZakat()">
                        <span class="asset-unit">BDT</span>
                    </div>
                    <div class="asset-group">
                        <div class="asset-emoji">📈</div>
                        <div class="asset-label">Investments & Shares<small>Stocks, mutual funds, bonds</small></div>
                        <input type="number" id="investments" class="asset-input" placeholder="0" min="0" oninput="calcZakat()">
                        <span class="asset-unit">BDT</span>
                    </div>
                    <div class="asset-group">
                        <div class="asset-emoji">💸</div>
                        <div class="asset-label">Money Owed to You<small>Receivables, loans given to others</small></div>
                        <input type="number" id="receivables" class="asset-input" placeholder="0" min="0" oninput="calcZakat()">
                        <span class="asset-unit">BDT</span>
                    </div>

                    <div style="font-size:0.78rem;font-weight:700;color:#ef4444;text-transform:uppercase;letter-spacing:0.06em;margin:20px 0 12px;">
                        <i class="bi bi-dash-circle me-1"></i> Deductible Liabilities
                    </div>

                    <div class="asset-group liability">
                        <div class="asset-emoji">📉</div>
                        <div class="asset-label">Total Liabilities<small>Short-term debts due this year</small></div>
                        <input type="number" id="liab" class="asset-input" placeholder="0" min="0" oninput="calcZakat()" style="color:#ef4444;">
                        <span class="asset-unit">BDT</span>
                    </div>

                    {{-- Result --}}
                    <div class="result-box" id="resultBox">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="result-label">Your Annual Zakat Due (2.5%)</div>
                                <div class="result-amount" id="zakatAmount">৳ 0</div>
                            </div>
                            <div id="nisabBadge" class="nisab-badge" style="background:rgba(255,255,255,0.15);color:white;">
                                ✗ Below Nisab
                            </div>
                        </div>
                        <div style="border-top:1px solid rgba(255,255,255,0.15);padding-top:16px;margin-top:8px;">
                            <div class="result-row">
                                <span class="r-label">Total Gross Assets</span>
                                <span class="r-val" id="grossWealth">৳ 0</span>
                            </div>
                            <div class="result-row">
                                <span class="r-label">Total Liabilities</span>
                                <span class="r-val" id="totalLiab" style="color:#fca5a5;">৳ 0</span>
                            </div>
                            <div class="result-row">
                                <span class="r-label">Net Zakatable Wealth</span>
                                <span class="r-val" id="netWealth">৳ 0</span>
                            </div>
                            <div class="result-row">
                                <span class="r-label">Nisab Threshold</span>
                                <span class="r-val" style="opacity:0.8;">৳ 85,000</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <a href="{{ url('/donor/register') }}" class="btn-pub-primary" style="flex:1;justify-content:center;padding:14px;font-size:1rem;" id="payBtn">
                            <i class="bi bi-heart-fill"></i> Pay This Zakat Online
                        </a>
                        <button onclick="resetCalc()" class="btn-pub-outline" style="padding:14px 20px;">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-5">

                {{-- Nisab Info --}}
                <div class="nisab-info-card">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                        <i class="bi bi-gem" style="font-size:1.5rem;color:#d97706;"></i>
                        <h5 style="font-weight:800;color:#92400e;margin:0;">What is Nisab?</h5>
                    </div>
                    <p style="font-size:0.875rem;color:#78350f;line-height:1.75;margin:0;">
                        Nisab is the minimum wealth threshold above which Zakat becomes obligatory. It equals the value of <strong>85 grams of gold</strong> or <strong>595 grams of silver</strong> — whichever is lower.<br><br>
                        If your net wealth has been above this threshold for a full lunar year (Hawl), you must pay 2.5% as Zakat.
                    </p>
                </div>

                {{-- Zakatable Assets --}}
                <div style="background:white;border:1px solid #e5e7eb;border-radius:20px;padding:24px;margin-bottom:20px;">
                    <h5 style="font-weight:800;color:#111827;margin-bottom:16px;">
                        <i class="bi bi-list-check me-2" style="color:#10b981;"></i>Zakatable Assets
                    </h5>
                    @foreach([
                        ['✅','Cash & bank savings','All liquid money including FDR'],
                        ['✅','Gold & silver','Beyond a small personal use threshold'],
                        ['✅','Business inventory','Goods held for trade at market price'],
                        ['✅','Investments','Stocks, mutual funds, business equity'],
                        ['✅','Money receivable','Loans you have given to others'],
                        ['❌','Primary residence','Your home is exempt'],
                        ['❌','Personal vehicle','Your daily-use car is exempt'],
                        ['❌','Personal jewelry','Small amount for regular wear, exempt'],
                    ] as $a)
                    <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;font-size:0.82rem;color:#4b5563;">
                        <span style="flex-shrink:0;margin-top:1px;">{{ $a[0] }}</span>
                        <div><strong>{{ $a[1] }}</strong> — {{ $a[2] }}</div>
                    </div>
                    @endforeach
                </div>

                {{-- Shariah certification badge --}}
                <div style="background:linear-gradient(135deg,#0d6e3f,#059669);border-radius:20px;padding:24px;color:white;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                        <i class="bi bi-patch-check-fill" style="font-size:1.5rem;color:#fbbf24;"></i>
                        <h5 style="font-weight:800;margin:0;color:white;">Shariah Certified</h5>
                    </div>
                    <p style="font-size:0.85rem;color:rgba(255,255,255,0.8);line-height:1.65;margin-bottom:16px;">
                        This calculator uses the Hanafi method — the most widely followed school of Islamic jurisprudence in Bangladesh. Reviewed and certified by our Shariah Advisory Board.
                    </p>
                    <a href="{{ route('public.about') }}" style="color:#fbbf24;font-size:0.82rem;font-weight:700;text-decoration:none;">
                        Meet our Shariah Board <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── SHARIAH NOTES ── --}}
<section class="shariah-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-4">
                <div class="section-badge"><i class="bi bi-book-fill"></i> Important Notes</div>
                <h2 class="section-title">Shariah Guidelines for This Calculator</h2>
                <p class="section-subtitle">Understanding these principles ensures your Zakat is correctly calculated and spiritually accepted.</p>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    @foreach([
                        ['📅','One Full Lunar Year (Hawl)','Your wealth must have been above Nisab for a complete Islamic year. If you dip below at any point and come back up, your Hawl resets from that point.'],
                        ['📐','The 2.5% Rate (Zakat Rate)','The obligatory rate of Zakat is 2.5% of your total net Zakatable wealth. There are no tiers — the rate is flat regardless of how wealthy you are.'],
                        ['💳','Short-Term Debts Only','Only debts due within the current year can be deducted. Long-term mortgages or vehicle loans are generally not deductible under the Hanafi position.'],
                        ['🏦','Fixed Deposits Count','Bank fixed deposits (FDR) are fully Zakatable. Even though you cannot access them immediately, they are owned by you and therefore count toward your Nisab.'],
                        ['🌾','Agricultural Income (Ushr)','Zakat on agricultural produce (Ushr — 5% or 10%) is calculated separately from regular wealth Zakat and is not covered in this calculator.'],
                        ['🤝','Intention (Niyyah)','Zakat requires the specific intention of paying Zakat. Simply giving charity without the intention of fulfilling your Zakat obligation does not count as Zakat.'],
                    ] as $note)
                    <div class="col-sm-6">
                        <div class="shariah-note">
                            <div class="note-icon">{{ $note[0] }}</div>
                            <div>
                                <h6>{{ $note[1] }}</h6>
                                <p>{{ $note[2] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section style="background:linear-gradient(135deg,#1a1a2e 0%,#0d6e3f 100%);padding:80px 0;text-align:center;">
    <div class="container">
        <div class="section-badge" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);margin-bottom:24px;">
            <i class="bi bi-heart-fill"></i> Ready to Pay?
        </div>
        <h2 style="color:white;font-size:clamp(1.8rem,3.5vw,2.8rem);font-weight:800;margin-bottom:16px;">
            Pay Your Zakat in Minutes
        </h2>
        <p style="color:rgba(255,255,255,0.7);max-width:480px;margin:0 auto 36px;">
            Register as a donor and complete your Zakat payment securely via bKash, Nagad, or bank transfer. Instant digital receipt provided.
        </p>
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <a href="{{ url('/donor/register') }}" class="btn-pub-primary" style="padding:14px 32px;font-size:1rem;">
                <i class="bi bi-heart-fill"></i> Pay Zakat Now
            </a>
            <a href="{{ route('public.how') }}" class="btn-pub-outline" style="padding:14px 28px;font-size:1rem;background:transparent;color:white;border-color:rgba(255,255,255,0.4);">
                <i class="bi bi-info-circle-fill"></i> How It Works
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    const NISAB = 85000;

    function calcZakat() {
        const v = id => parseFloat(document.getElementById(id).value) || 0;
        const cash        = v('cash');
        const gold        = v('gold');
        const silver      = v('silver');
        const business    = v('business');
        const investments = v('investments');
        const receivables = v('receivables');
        const liab        = v('liab');

        const gross = cash + gold + silver + business + investments + receivables;
        const net   = Math.max(0, gross - liab);
        const zakat = net >= NISAB ? net * 0.025 : 0;
        const nisabMet = net >= NISAB;

        const fmt = n => '৳ ' + Math.floor(n).toLocaleString('en-IN');

        document.getElementById('zakatAmount').textContent = fmt(zakat);
        document.getElementById('grossWealth').textContent = fmt(gross);
        document.getElementById('totalLiab').textContent   = fmt(liab);
        document.getElementById('netWealth').textContent   = fmt(net);

        const badge = document.getElementById('nisabBadge');
        if (nisabMet) {
            badge.textContent = '✓ Nisab Met';
            badge.style.background = 'rgba(255,255,255,0.2)';
            document.getElementById('resultBox').style.background = 'linear-gradient(135deg, #0d6e3f, #10b981)';
        } else {
            badge.textContent = '✗ Below Nisab';
            badge.style.background = 'rgba(239,68,68,0.3)';
            document.getElementById('resultBox').style.background = 'linear-gradient(135deg, #374151, #4b5563)';
        }
    }

    function resetCalc() {
        ['cash','gold','silver','business','investments','receivables','liab'].forEach(id => {
            document.getElementById(id).value = '';
        });
        calcZakat();
    }

    function applyPageTranslations(lang) {}
</script>
@endpush
