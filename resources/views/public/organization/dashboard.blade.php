@extends('layouts.public')

@section('title', 'Organization Dashboard | সংগঠন ড্যাশবোর্ড')

@push('styles')
<style>
    .dash-hero { background: linear-gradient(135deg, #7c3aed 0%, #4c1d95 100%); padding: 40px 0; color: white; }
    .dash-stat { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); border-radius: 14px; padding: 18px; text-align: center; }
    .dash-stat .stat-num { font-size: 1.5rem; font-weight: 800; display: block; }
    .dash-stat .stat-lbl { font-size: 0.75rem; opacity: 0.75; }
    .dash-card { background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
    .dash-card h5 { font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #f3f4f6; }
</style>
@endpush

@section('content')
<div class="dash-hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:56px;height:56px;background:rgba(255,255,255,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🏢</div>
                    <div>
                        <h2 style="font-size:1.2rem;font-weight:700;margin:0;">{{ $org ? $org->name_en : 'Organization Dashboard' }}</h2>
                        <div style="font-size:0.82rem;opacity:0.75;"><i class="bi bi-building me-1"></i> Partner Organization</div>
                    </div>
                </div>
                @if($org && $org->status === 'pending')
                <div style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:10px;padding:12px 16px;font-size:0.82rem;margin-top:12px;">
                    <i class="bi bi-hourglass-split me-2"></i>
                    Your organization is under review. Approval typically takes 5–7 business days.
                </div>
                @endif
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-4"><div class="dash-stat"><span class="stat-num">0</span><span class="stat-lbl">Active Volunteers</span></div></div>
                    <div class="col-4"><div class="dash-stat"><span class="stat-num">0</span><span class="stat-lbl">Cases Handled</span></div></div>
                    <div class="col-4"><div class="dash-stat"><span class="stat-num">{{ $org ? ucfirst($org->status) : 'Pending' }}</span><span class="stat-lbl">Approval Status</span></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="background:#f8faf9;padding:40px 0;min-height:60vh;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                @if($org)
                <a href="{{ route('organization.verifications.index') }}" class="dash-card" style="display:flex;align-items:center;gap:14px;text-decoration:none;color:inherit;border-left:4px solid #7c3aed;">
                    <div style="width:46px;height:46px;border-radius:12px;background:#f5f3ff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;">✔️</div>
                    <div>
                        <div style="font-weight:700;color:#111827;">Final Verification Queue</div>
                        <div style="font-size:0.82rem;color:#9ca3af;">Review & finalize Zakat applications verified by your volunteers</div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto" style="color:#9ca3af;"></i>
                </a>
                @endif
                <div class="dash-card">
                    <h5><i class="bi bi-info-circle-fill me-2" style="color:#8b5cf6;"></i>Organization Details</h5>
                    @if($org)
                    <div class="row g-3">
                        @foreach([
                            ['Organization Code', $org->org_code],
                            ['Type', ucfirst($org->type ?? 'NGO')],
                            ['Contact Person', $org->contact_person_name],
                            ['Contact Email', $org->contact_email],
                            ['Mobile', $org->contact_mobile],
                            ['Division', $org->division],
                            ['District', $org->district],
                            ['Status', ucfirst($org->status)],
                        ] as $field)
                        <div class="col-sm-6">
                            <div style="background:#f8faf9;border-radius:10px;padding:12px 14px;">
                                <div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:2px;">{{ $field[0] }}</div>
                                <div style="font-weight:700;color:#111827;font-size:0.9rem;">{{ $field[1] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p style="color:#9ca3af;text-align:center;padding:20px 0;">Organization profile not found.</p>
                    @endif
                </div>

                @if($org)
                {{-- Referral Widget --}}
                <div style="background:#f3e8ff;border:1px solid #e9d5ff;border-radius:12px;padding:20px;margin-bottom:20px;">
                    <h6 style="font-weight:700;color:#6b21a8;margin-bottom:12px;"><i class="bi bi-link-45deg fs-5"></i> Organization Collection Link</h6>
                    <p style="font-size:0.875rem;color:#7e22ce;margin-bottom:12px;">Share this link to collect Zakat directly on behalf of your organization.</p>
                    
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <input type="text" class="form-control form-control-sm" value="{{ $org->referral_url }}" id="refLinkInput" readonly style="background:white;color:#111827;font-weight:600;font-family:monospace;">
                        <button class="btn btn-sm" style="background:#8b5cf6;color:white;font-weight:600;border:none;flex-shrink:0;" onclick="copyRefLink()">Copy Link</button>
                    </div>
                    
                    <div class="row g-2 text-center" style="border-top:1px dashed #d8b4fe;padding-top:16px;margin-top:16px;">
                        <div class="col-4">
                            <div style="font-size:1.1rem;font-weight:800;color:#6b21a8;">৳{{ number_format($org->total_collected_via_referral ?? 0) }}</div>
                            <div style="font-size:0.75rem;text-transform:uppercase;color:#9333ea;">Raised</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size:1.1rem;font-weight:800;color:#6b21a8;">{{ $org->total_donors_via_referral ?? 0 }}</div>
                            <div style="font-size:0.75rem;text-transform:uppercase;color:#9333ea;">Donors</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size:1.1rem;font-weight:800;color:#6b21a8;">#{{ $leaderboardRank ?? '-' }}</div>
                            <div style="font-size:0.75rem;text-transform:uppercase;color:#9333ea;">Rank</div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('leaderboard') }}" style="font-size:0.875rem;color:#6b21a8;font-weight:600;text-decoration:none;"><i class="bi bi-trophy"></i> View Leaderboard</a>
                    </div>
                </div>

                {{-- Registered Volunteers --}}
                <div class="dash-card">
                    <h5><i class="bi bi-people-fill me-2" style="color:#f59e0b;"></i>Our Volunteers</h5>
                    @if(isset($org->volunteers) && count($org->volunteers) > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle" style="font-size:0.875rem;">
                                <thead style="background:#f3f4f6;color:#6b7280;font-weight:600;">
                                    <tr>
                                        <th class="rounded-start">Name</th>
                                        <th>Code</th>
                                        <th>Role/Area</th>
                                        <th class="text-end rounded-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($org->volunteers as $vol)
                                    <tr style="border-bottom:1px solid #f3f4f6;">
                                        <td style="font-weight:600;color:#111827;">{{ $vol->name_en }}</td>
                                        <td style="color:#6b7280;">{{ $vol->volunteer_code }}</td>
                                        <td style="color:#6b7280;">{{ ucfirst($vol->coverage_level) }}</td>
                                        <td class="text-end">
                                            @if($vol->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ ucfirst($vol->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="text-align:center;padding:20px;color:#9ca3af;">
                            <i class="bi bi-person-x fs-1 mb-2 d-block"></i>
                            <p>No volunteers have registered under your organization yet.</p>
                        </div>
                    @endif
                </div>
                @endif

                <div class="dash-card">
                    <h5><i class="bi bi-diagram-3-fill me-2" style="color:#3b82f6;"></i>Partnership Features</h5>
                    <div class="row g-3">
                        @foreach([['Beneficiary Matching','Match with eligible beneficiaries in your area','#10b981','bi-people-fill'],['Fund Management','Manage and track Zakat distributions','#3b82f6','bi-cash-stack'],['Volunteer Network','Manage your volunteer team','#f59e0b','bi-diagram-3'],['Reports & Analytics','Access your organization\'s impact data','#8b5cf6','bi-bar-chart-fill']] as $feat)
                        <div class="col-sm-6">
                            <div style="border:1.5px solid #e5e7eb;border-radius:12px;padding:16px;opacity:{{ ($org && $org->status !== 'active') ? '0.5' : '1' }};">
                                <div style="width:36px;height:36px;background:{{ $feat[2] }}15;border-radius:10px;display:flex;align-items:center;justify-content:center;color:{{ $feat[2] }};margin-bottom:10px;">
                                    <i class="bi {{ $feat[3] }}"></i>
                                </div>
                                <div style="font-weight:700;font-size:0.875rem;color:#111827;margin-bottom:4px;">{{ $feat[0] }}</div>
                                <div style="font-size:0.78rem;color:#9ca3af;">{{ $feat[1] }}</div>
                                @if(!$org || $org->status !== 'active')
                                <div style="font-size:0.72rem;color:#ef4444;margin-top:6px;"><i class="bi bi-lock me-1"></i>Unlocks after approval</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="dash-card">
                    <h5><i class="bi bi-person-circle me-2" style="color:#8b5cf6;"></i>Admin Account</h5>
                    <div class="d-flex flex-column gap-3" style="font-size:0.875rem;">
                        <div><div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Name</div><div style="font-weight:600;">{{ $user->name }}</div></div>
                        <div><div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Email</div><div style="font-weight:600;">{{ $user->email }}</div></div>
                        <div><div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Role</div><div style="font-weight:600;">Organization Admin</div></div>
                    </div>
                </div>

                <div class="dash-card">
                    <h5><i class="bi bi-envelope me-2" style="color:#3b82f6;"></i>Support</h5>
                    <p style="font-size:0.82rem;color:#6b7280;margin-bottom:12px;">For partnership inquiries or assistance:</p>
                    <div style="font-weight:700;color:#8b5cf6;">partner@czm.gov.bd</div>
                    <div style="font-size:0.82rem;color:#9ca3af;">or call: 16789 (Ext. 3)</div>
                </div>

                <div class="dash-card">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="display:flex;align-items:center;gap:10px;padding:10px 12px;width:100%;border:none;background:none;color:#dc2626;font-size:0.875rem;font-weight:600;cursor:pointer;border-radius:8px;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
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
