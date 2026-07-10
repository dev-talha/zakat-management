@extends('layouts.public')

@section('title', 'Donor Dashboard | দাতার ড্যাশবোর্ড')

@push('styles')
<style>
    .dash-hero { background: linear-gradient(135deg, #0d6e3f 0%, #065f46 100%); padding: 40px 0; color: white; }
    .dash-stat { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; padding: 20px; }
    .dash-stat .stat-num { font-size: 1.8rem; font-weight: 800; display: block; }
    .dash-stat .stat-lbl { font-size: 0.78rem; opacity: 0.75; }
    .dash-card { background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
    .dash-card h5 { font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #f3f4f6; }
    .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .status-active   { background: #f0fdf4; color: #166534; }
    .status-pending  { background: #fffbeb; color: #92400e; }
    .quick-action { display: flex; align-items: center; gap: 14px; padding: 16px; border: 1.5px solid #e5e7eb; border-radius: 12px; text-decoration: none; color: inherit; transition: all 0.2s ease; }
    .quick-action:hover { border-color: #10b981; background: #f0fdf4; color: inherit; }
    .quick-action .qa-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
</style>
@endpush

@section('content')
{{-- Donor Hero --}}
<div class="dash-hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:56px;height:56px;background:rgba(255,255,255,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;">🙏</div>
                    <div>
                        <h2 style="font-size:1.3rem;font-weight:700;margin:0;">As-salamu alaykum, {{ $user->name }}!</h2>
                        <div style="font-size:0.82rem;opacity:0.75;"><i class="bi bi-heart-fill me-1" style="color:#f59e0b;"></i> Zakat Donor</div>
                    </div>
                </div>
                @if($donor && $donor->kyc_status !== 'verified')
                <div style="background:rgba(245,158,11,0.2);border:1px solid rgba(245,158,11,0.4);border-radius:10px;padding:12px 16px;font-size:0.82rem;">
                    <i class="bi bi-exclamation-triangle-fill me-2" style="color:#fbbf24;"></i>
                    Your KYC verification is pending. Please complete identity verification to enable payments.
                </div>
                @endif
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-4"><div class="dash-stat text-center"><span class="stat-num">৳{{ number_format($totalDonated ?? 0) }}</span><span class="stat-lbl">Total Donated</span></div></div>
                    <div class="col-4"><div class="dash-stat text-center"><span class="stat-num">{{ $donationCount ?? 0 }}</span><span class="stat-lbl">Donations Made</span></div></div>
                    <div class="col-4"><div class="dash-stat text-center"><span class="stat-num">{{ $donationCount ?? 0 }}</span><span class="stat-lbl">Families Helped</span></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Dashboard Content --}}
<div style="background:#f8faf9;padding:40px 0;min-height:60vh;">
    <div class="container">
        <div class="row g-4">
            {{-- Left --}}
            <div class="col-lg-8">
                {{-- Quick Actions --}}
                <div class="dash-card">
                    <h5><i class="bi bi-lightning-charge-fill me-2" style="color:#10b981;"></i>Quick Actions</h5>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <a href="{{ route('public.calculator') }}" class="quick-action">
                                <div class="qa-icon" style="background:#f0fdf4;color:#10b981;"><i class="bi bi-calculator-fill"></i></div>
                                <div>
                                    <div style="font-weight:700;font-size:0.9rem;">Calculate Zakat</div>
                                    <div style="font-size:0.78rem;color:#9ca3af;">Shariah-certified calculator</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ url('/pay') }}" class="quick-action">
                                <div class="qa-icon" style="background:#eff6ff;color:#3b82f6;"><i class="bi bi-credit-card-fill"></i></div>
                                <div>
                                    <div style="font-weight:700;font-size:0.9rem;">Pay Zakat Now</div>
                                    <div style="font-size:0.78rem;color:#9ca3af;">bKash, Nagad, SSLCommerz</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="#" class="quick-action" onclick="alert('Certificate download coming soon!')">
                                <div class="qa-icon" style="background:#fffbeb;color:#f59e0b;"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                                <div>
                                    <div style="font-weight:700;font-size:0.9rem;">Download Receipts</div>
                                    <div style="font-size:0.78rem;color:#9ca3af;">Tax certificates & receipts</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="#" class="quick-action">
                                <div class="qa-icon" style="background:#f5f3ff;color:#8b5cf6;"><i class="bi bi-eye-fill"></i></div>
                                <div>
                                    <div style="font-weight:700;font-size:0.9rem;">Track Impact</div>
                                    <div style="font-size:0.78rem;color:#9ca3af;">See where your Zakat went</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Donation History --}}
                <div class="dash-card">
                    <h5><i class="bi bi-clock-history me-2" style="color:#3b82f6;"></i>Donation History</h5>
                    <div style="background:#f0fdf4;border:1px solid #dcfce7;border-radius:10px;padding:12px;font-size:0.78rem;color:#15803d;margin-bottom:16px;display:flex;align-items:start;gap:8px;">
                        <i class="bi bi-shield-lock-fill" style="font-size:1.1rem;margin-top:1px;"></i>
                        <div class="privacyNoticeTxt">
                            Privacy Shield Active: Your exact donation amounts are confidential. Public leaderboards use range bands only.
                        </div>
                    </div>
                    @if(isset($collections) && count($collections) > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle" style="font-size:0.875rem;">
                                <thead style="background:#f3f4f6;color:#6b7280;font-weight:600;">
                                    <tr>
                                        <th class="rounded-start">Date</th>
                                        <th>Receipt No</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th class="text-end rounded-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($collections as $col)
                                    <tr style="border-bottom:1px solid #f3f4f6;">
                                        <td style="color:#6b7280;">{{ $col->created_at->format('d M Y') }}</td>
                                        <td style="font-weight:600;color:#111827;">{{ $col->receipt_no }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ strtoupper($col->payment_gateway ?? 'N/A') }}</span></td>
                                        <td>
                                            @if($col->payment_status === 'paid')
                                                <span class="status-badge status-active"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                            @else
                                                <span class="status-badge status-pending"><i class="bi bi-clock-fill"></i> Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-end" style="font-weight:700;color:#0d6e3f;">৳{{ number_format($col->amount) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-2">
                            <a href="#" class="btn btn-sm btn-link text-decoration-none" style="color:#3b82f6;">View Full History <i class="bi bi-arrow-right"></i></a>
                        </div>
                    @else
                        <div style="text-align:center;padding:40px 20px;">
                            <div style="font-size:3rem;margin-bottom:12px;">💝</div>
                            <h6 style="font-weight:700;color:#111827;">No Donations Yet</h6>
                            <p style="font-size:0.875rem;color:#9ca3af;margin-bottom:20px;">Make your first Zakat payment to see it here.</p>
                            <a href="{{ url('/pay') }}" class="btn-pub-primary" style="display:inline-flex;">
                                <i class="bi bi-credit-card"></i> Pay Zakat Online
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right --}}
            <div class="col-lg-4">
                {{-- Profile Card --}}
                <div class="dash-card">
                    <h5><i class="bi bi-person-circle me-2" style="color:#10b981;"></i>My Profile</h5>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <div style="font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Name</div>
                            <div style="font-weight:600;color:#111827;">{{ $user->name }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Email</div>
                            <div style="font-weight:600;color:#111827;">{{ $user->email }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Mobile</div>
                            <div style="font-weight:600;color:#111827;">{{ $user->mobile ?? 'Not set' }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">KYC Status</div>
                            @if($donor)
                            <span class="status-badge {{ $donor->kyc_status === 'verified' ? 'status-active' : 'status-pending' }}">
                                <i class="bi bi-{{ $donor->kyc_status === 'verified' ? 'patch-check-fill' : 'clock' }}"></i>
                                {{ ucfirst($donor->kyc_status ?? 'pending') }}
                            </span>
                            @else
                            <span class="status-badge status-pending"><i class="bi bi-clock"></i> Pending</span>
                            @endif
                        </div>
                        <div>
                            <div style="font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Donor Type</div>
                            <div style="font-weight:600;color:#111827;">{{ ucfirst($donor->donor_type ?? 'individual') }}</div>
                        </div>
                    </div>
                    <a href="#" class="btn-pub-outline w-100 mt-4" style="justify-content:center;" onclick="alert('Profile editing coming soon!')">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </a>
                </div>

                {{-- Shariah Reminder (Hawl) --}}
                <div style="background:linear-gradient(135deg,#0d6e3f,#10b981);border-radius:16px;padding:24px;color:white;margin-bottom:20px;position:relative;overflow:hidden;">
                    <div style="position:absolute;right:-20px;top:-20px;font-size:6rem;opacity:0.1;line-height:1;">🌙</div>
                    <div style="font-size:1.3rem;margin-bottom:8px;">🌙</div>
                    <h6 style="font-weight:700;margin-bottom:8px;">Hawl (Zakat Year) Tracker</h6>
                    @if(isset($collections) && count($collections) > 0)
                        @php
                            $lastPayment = $collections->first();
                            $nextDueDate = $lastPayment->created_at->addDays(354); // Islamic year approx
                        @endphp
                        <p style="font-size:0.82rem;opacity:0.9;margin-bottom:12px;">Your last Zakat was paid on <strong>{{ $lastPayment->created_at->format('d M Y') }}</strong>.</p>
                        <div style="background:rgba(255,255,255,0.2);border-radius:8px;padding:12px;margin-bottom:16px;">
                            <div style="font-size:0.75rem;text-transform:uppercase;opacity:0.8;">Next Zakat Due By</div>
                            <div style="font-weight:800;font-size:1.1rem;">{{ $nextDueDate->format('d M Y') }}</div>
                        </div>
                    @else
                        <p style="font-size:0.82rem;opacity:0.8;margin-bottom:16px;">Zakat is due once per Islamic year (Hawl). Make your first payment to start tracking.</p>
                    @endif
                    <a href="#" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;padding:8px 16px;border-radius:8px;font-size:0.82rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;" onclick="alert('Reminder settings coming soon!')">
                        <i class="bi bi-bell"></i> Manage Reminder
                    </a>
                </div>

                {{-- Navigation --}}
                <div class="dash-card">
                    <h5><i class="bi bi-grid me-2" style="color:#8b5cf6;"></i>Navigation</h5>
                    @foreach([
                        ['bi-house-fill', 'Public Homepage', url('/'), '#10b981'],
                        ['bi-calculator-fill', 'Zakat Calculator', route('public.calculator'), '#3b82f6'],
                        ['bi-book-fill', 'Shariah Guidelines', url('/about'), '#f59e0b'],
                        ['bi-telephone-fill', 'Contact Support', url('/contact'), '#8b5cf6'],
                    ] as $nav)
                    <a href="{{ $nav[2] }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;text-decoration:none;color:#374151;font-size:0.875rem;font-weight:500;transition:all 0.2s;margin-bottom:4px;" onmouseover="this.style.background='#f8faf9'" onmouseout="this.style.background='transparent'">
                        <i class="bi {{ $nav[0] }}" style="color:{{ $nav[3] }};font-size:1rem;width:20px;text-align:center;"></i>
                        {{ $nav[1] }}
                        <i class="bi bi-chevron-right ms-auto" style="font-size:0.7rem;color:#d1d5db;"></i>
                    </a>
                    @endforeach
                    <hr style="border-color:#f3f4f6;margin:8px 0;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="display:flex;align-items:center;gap:10px;padding:10px 12px;width:100%;border:none;background:none;color:#dc2626;font-size:0.875rem;font-weight:600;cursor:pointer;border-radius:8px;transition:all 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                            <i class="bi bi-box-arrow-right" style="width:20px;text-align:center;"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const donorTranslations = {
        privacyNotice: {
            en: 'Privacy Shield Active: Your exact donation amounts are confidential. Public leaderboards use range bands only.',
            bn: 'গোপনীয়তা সচল: আপনার যাকাত প্রদানের সুনির্দিষ্ট পরিমাণ সুরক্ষিত ও অপ্রকাশিত। জনসমক্ষে তা কেবল রেঞ্জ ব্যান্ডে প্রদর্শিত হয়।'
        }
    };
    function applyPageTranslations(lang) {
        document.querySelectorAll('.privacyNoticeTxt').forEach(el => {
            el.textContent = donorTranslations.privacyNotice[lang];
        });
    }
</script>
@endpush
