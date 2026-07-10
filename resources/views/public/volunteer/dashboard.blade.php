@extends('layouts.public')

@section('title', 'Volunteer Dashboard | স্বেচ্ছাসেবী ড্যাশবোর্ড')

@push('styles')
<style>
    .dash-hero { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); padding: 40px 0; color: white; }
    .dash-stat { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); border-radius: 14px; padding: 18px; text-align: center; }
    .dash-stat .stat-num { font-size: 1.6rem; font-weight: 800; display: block; }
    .dash-stat .stat-lbl { font-size: 0.75rem; opacity: 0.75; }
    .dash-card { background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
    .dash-card h5 { font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #f3f4f6; }
    .quick-action { display: flex; align-items: center; gap: 14px; padding: 14px; border: 1.5px solid #e5e7eb; border-radius: 12px; text-decoration: none; color: inherit; transition: all 0.2s ease; }
    .quick-action:hover { border-color: #f59e0b; background: #fffbeb; color: inherit; }
</style>
@endpush

@section('content')
<div class="dash-hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:56px;height:56px;background:rgba(255,255,255,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🤝</div>
                    <div>
                        <h2 style="font-size:1.3rem;font-weight:700;margin:0;">Welcome, {{ $user->name }}!</h2>
                        <div style="font-size:0.82rem;opacity:0.75;"><i class="bi bi-people-fill me-1"></i> Field Volunteer</div>
                    </div>
                </div>
                @if($volunteer && $volunteer->status === 'pending')
                <div style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:10px;padding:12px 16px;font-size:0.82rem;margin-top:12px;">
                    <i class="bi bi-hourglass-split me-2"></i>
                    Your application is under review. We will contact you within 3 business days.
                </div>
                @endif
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-4"><div class="dash-stat"><span class="stat-num">{{ $volunteer->total_verifications ?? 0 }}</span><span class="stat-lbl">Verifications</span></div></div>
                    <div class="col-4"><div class="dash-stat"><span class="stat-num">{{ $volunteer->total_followups ?? 0 }}</span><span class="stat-lbl">Follow-ups</span></div></div>
                    <div class="col-4"><div class="dash-stat"><span class="stat-num">{{ ucfirst($volunteer->status ?? 'pending') }}</span><span class="stat-lbl">Status</span></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="background:#f8faf9;padding:40px 0;min-height:60vh;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="dash-card">
                    <h5><i class="bi bi-lightning-charge-fill me-2" style="color:#f59e0b;"></i>My Tasks</h5>
                    @if($volunteer && $volunteer->status === 'active')
                    <a href="{{ route('volunteer.verifications.index') }}" class="quick-action">
                        <div style="width:42px;height:42px;border-radius:12px;background:#fffbeb;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">📋</div>
                        <div>
                            <div style="font-weight:700;color:#111827;">Zakat Application Verifications</div>
                            <div style="font-size:0.8rem;color:#9ca3af;">Review & verify applications from your union</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color:#9ca3af;"></i>
                    </a>
                    @else
                    <div style="text-align:center;padding:40px 20px;">
                        <div style="font-size:3rem;margin-bottom:12px;">📋</div>
                        <h6 style="font-weight:700;color:#111827;">No Active Assignments</h6>
                        <p style="font-size:0.875rem;color:#9ca3af;">
                            Once your application is approved, verification tasks from your union will appear here.
                        </p>
                    </div>
                    @endif
                </div>

                <div class="dash-card">
                    <h5><i class="bi bi-map-fill me-2" style="color:#3b82f6;"></i>My Coverage Area</h5>
                    @if($volunteer)
                    {{-- Referral Widget --}}
                    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:20px;margin-bottom:20px;">
                        <h6 style="font-weight:700;color:#92400e;margin-bottom:12px;"><i class="bi bi-link-45deg fs-5"></i> My Zakat Collection Link</h6>
                        <p style="font-size:0.875rem;color:#b45309;margin-bottom:12px;">Share this link to collect Zakat online. Donations will be tracked under your profile.</p>
                        
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <input type="text" class="form-control form-control-sm" value="{{ $volunteer ? $volunteer->referral_url : '' }}" id="refLinkInput" readonly style="background:white;color:#111827;font-weight:600;font-family:monospace;">
                            <button class="btn btn-sm" style="background:#f59e0b;color:white;font-weight:600;border:none;flex-shrink:0;" onclick="copyRefLink()">Copy Link</button>
                        </div>
                        
                        <div class="row g-2 text-center" style="border-top:1px dashed #fcd34d;padding-top:16px;margin-top:16px;">
                            <div class="col-4">
                                <div style="font-size:1.1rem;font-weight:800;color:#92400e;">৳{{ number_format($volunteer->total_collected_via_referral ?? 0) }}</div>
                                <div style="font-size:0.75rem;text-transform:uppercase;color:#d97706;">Raised</div>
                            </div>
                            <div class="col-4">
                                <div style="font-size:1.1rem;font-weight:800;color:#92400e;">{{ $volunteer->total_donors_via_referral ?? 0 }}</div>
                                <div style="font-size:0.75rem;text-transform:uppercase;color:#d97706;">Donors</div>
                            </div>
                            <div class="col-4">
                                <div style="font-size:1.1rem;font-weight:800;color:#92400e;">#{{ $leaderboardRank ?? '-' }}</div>
                                <div style="font-size:0.75rem;text-transform:uppercase;color:#d97706;">Rank</div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('leaderboard') }}" style="font-size:0.875rem;color:#b45309;font-weight:600;text-decoration:none;"><i class="bi bi-trophy"></i> View Leaderboard</a>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div style="background:#fffbeb;border-radius:10px;padding:14px;text-align:center;">
                                <div style="font-size:0.75rem;color:#9ca3af;">Level</div>
                                <div style="font-weight:700;color:#d97706;">{{ ucfirst($volunteer->coverage_level ?? 'union') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div style="background:#eff6ff;border-radius:10px;padding:14px;text-align:center;">
                                <div style="font-size:0.75rem;color:#9ca3af;">Code</div>
                                <div style="font-weight:700;color:#3b82f6;font-size:0.9rem;">{{ $volunteer->volunteer_code }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div style="background:#f0fdf4;border-radius:10px;padding:14px;text-align:center;">
                                <div style="font-size:0.75rem;color:#9ca3af;">Status</div>
                                <div style="font-weight:700;color:{{ $volunteer->status === 'active' ? '#10b981' : ($volunteer->status === 'pending' ? '#f59e0b' : '#ef4444') }};">{{ ucfirst($volunteer->status ?? 'pending') }}</div>
                            </div>
                        </div>
                    </div>
                    @else
                    <p style="color:#9ca3af;font-size:0.875rem;text-align:center;padding:20px 0;">Profile information will appear here after approval.</p>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="dash-card">
                    <h5><i class="bi bi-person-circle me-2" style="color:#f59e0b;"></i>My Profile</h5>
                    <div class="d-flex flex-column gap-3" style="font-size:0.875rem;">
                        <div><div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Name</div><div style="font-weight:600;">{{ $user->name }}</div></div>
                        <div><div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Email</div><div style="font-weight:600;">{{ $user->email }}</div></div>
                        <div><div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Mobile</div><div style="font-weight:600;">{{ $user->mobile ?? 'Not set' }}</div></div>
                        @if($volunteer)
                        <div><div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">NID No.</div><div style="font-weight:600;">{{ $volunteer->nid_no }}</div></div>
                        <div><div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Occupation</div><div style="font-weight:600;">{{ $volunteer->occupation ?? 'Not provided' }}</div></div>
                        @endif
                    </div>
                </div>

                <div class="dash-card">
                    <h5><i class="bi bi-book-fill me-2" style="color:#8b5cf6;"></i>Training Resources</h5>
                    @foreach(['Field Verification Guide','Beneficiary Assessment Form','Code of Conduct','Report Submission Process'] as $res)
                    <a href="#" style="display:flex;align-items:center;gap:8px;padding:10px 0;border-bottom:1px solid #f3f4f6;color:#374151;text-decoration:none;font-size:0.82rem;font-weight:500;" onclick="alert('Resource coming soon!')">
                        <i class="bi bi-file-pdf-fill" style="color:#ef4444;"></i>{{ $res }}<i class="bi bi-download ms-auto" style="color:#9ca3af;font-size:0.75rem;"></i>
                    </a>
                    @endforeach
                </div>

                <div class="dash-card">
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
function copyRefLink() {
    var copyText = document.getElementById("refLinkInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    // Toast notification
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
    Toast.fire({
        icon: 'success',
        title: 'Referral link copied to clipboard!'
    });
}
function applyPageTranslations(lang) {}
</script>
@endpush
