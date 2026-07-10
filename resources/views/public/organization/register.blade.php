@extends('layouts.public')

@section('title', 'Organization Registration | সংগঠন নিবন্ধন')
@section('meta_description', 'Register your NGO, mosque committee, or institution with CZM Bangladesh to collaborate in Zakat collection and distribution.')

@push('styles')
<style>
    .reg-hero { background: linear-gradient(135deg, #f5f3ff 0%, #ffffff 60%); padding: 60px 0 40px; border-bottom: 1px solid #e5e7eb; }
    .form-section { background: white; border: 1px solid #e5e7eb; border-radius: 20px; padding: 36px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); margin-bottom: 24px; }
    .form-section-title { font-size: 1rem; font-weight: 700; color: #111827; padding-bottom: 14px; border-bottom: 2px solid #f3f4f6; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
    .form-section-title i { color: #8b5cf6; font-size: 1.1rem; }
    .org-type-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
        font-size: 0.82rem;
        font-weight: 600;
    }
    .org-type-card:hover { border-color: #8b5cf6; background: #f5f3ff; }
    .org-type-card.selected { border-color: #8b5cf6; background: #f5f3ff; color: #7c3aed; }
    .org-type-card .type-icon { font-size: 1.5rem; margin-bottom: 6px; display: block; }
</style>
@endpush

@section('content')
<div class="reg-hero">
    <div class="container">
        <div class="d-flex align-items-center gap-2 mb-3" style="font-size:0.85rem;color:#9ca3af;">
            <a href="{{ url('/') }}" style="color:#8b5cf6;text-decoration:none;">Home</a>
            <i class="bi bi-chevron-right"></i> Organization Registration
        </div>
        <div class="section-badge mb-3" style="background:rgba(139,92,246,0.08);color:#7c3aed;"><i class="bi bi-building-fill"></i> Partner with Us</div>
        <h1 class="mb-3">Register Your Organization<br><span style="color:#8b5cf6;">সংগঠন নিবন্ধন করুন</span></h1>
        <p style="color:#6b7280;">NGOs, mosque committees, and institutions can partner with CZM to expand Zakat reach and impact.</p>
    </div>
</div>

<div style="background:#f8faf9;padding:60px 0;">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                @if($errors->any())
                <div class="pub-alert pub-alert-error mb-4">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div><strong>Please fix:</strong><ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                </div>
                @endif

                <form method="POST" action="{{ route('organization.register.store') }}">
                    @csrf

                    {{-- Org Type --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-grid-fill"></i> Organization Type</div>
                        <div class="row g-3 mb-3">
                            @foreach([['ngo','🏢','NGO / INGO'],['mosque','🕌','Mosque / Madrasa'],['charity','❤️','Charity Trust'],['corporate','🏦','Corporate Foundation'],['community','👥','Community Group'],['other','📋','Other Institution']] as $t)
                            <div class="col-4 col-md-2">
                                <div class="org-type-card {{ old('org_type') == $t[0] ? 'selected' : '' }}" onclick="selectOrgType('{{ $t[0] }}', this)">
                                    <span class="type-icon">{{ $t[1] }}</span>
                                    {{ $t[2] }}
                                    <input type="radio" name="org_type" value="{{ $t[0] }}" style="display:none;" {{ old('org_type') == $t[0] ? 'checked' : '' }}>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Organization Name (English) *</label>
                                <input type="text" name="name_en" class="pub-form-control" value="{{ old('name_en') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">সংগঠনের নাম (বাংলা)</label>
                                <input type="text" name="name_bn" class="pub-form-control" value="{{ old('name_bn') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Registration Details --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-file-earmark-text-fill"></i> Registration & Legal Info</div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Registration Number</label>
                                <input type="text" name="registration_no" class="pub-form-control" value="{{ old('registration_no') }}" placeholder="Govt. registration no.">
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Trade License No.</label>
                                <input type="text" name="trade_license_no" class="pub-form-control" value="{{ old('trade_license_no') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">NGO Registration No.</label>
                                <input type="text" name="ngo_registration_no" class="pub-form-control" value="{{ old('ngo_registration_no') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Year Established</label>
                                <input type="number" name="year_established" class="pub-form-control" value="{{ old('year_established') }}" placeholder="e.g. 2005" min="1900" max="{{ date('Y') }}">
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label">Organization Website</label>
                                <input type="url" name="website" class="pub-form-control" value="{{ old('website') }}" placeholder="https://yourorg.org">
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label">Organization Description</label>
                                <textarea name="description" class="pub-form-control" rows="3" placeholder="Briefly describe your organization, its mission, and Zakat-related activities.">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-person-badge-fill"></i> Contact Person & Location</div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Contact Person Name *</label>
                                <input type="text" name="contact_person_name" class="pub-form-control" value="{{ old('contact_person_name') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Contact Mobile *</label>
                                <input type="tel" name="contact_mobile" class="pub-form-control" value="{{ old('contact_mobile') }}" placeholder="01XXXXXXXXX" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Contact Email *</label>
                                <input type="email" name="contact_email" class="pub-form-control" value="{{ old('contact_email') }}" required>
                            </div>
                            <div class="col-12">
                                <x-location-picker :division-required="true" :district-required="true" />
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label">Full Address</label>
                                <textarea name="address" class="pub-form-control" rows="2" placeholder="Office address">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Admin Account --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-lock-fill"></i> Admin Account Credentials</div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Admin Email *</label>
                                <input type="email" name="email" class="pub-form-control" value="{{ old('email') }}" required>
                                <div class="pub-form-hint">This will be your login email for the organization dashboard</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Admin Name *</label>
                                <input type="text" name="admin_name" class="pub-form-control" value="{{ old('admin_name') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Password *</label>
                                <input type="password" name="password" class="pub-form-control" placeholder="Min. 8 characters" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="pub-form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="mb-3" style="display:flex;align-items:flex-start;gap:10px;">
                            <input type="checkbox" id="orgTerms" required style="margin-top:3px;accent-color:#8b5cf6;">
                            <label for="orgTerms" style="font-size:0.875rem;color:#374151;cursor:pointer;">
                                I agree to the <a href="#" style="color:#8b5cf6;font-weight:600;">Partnership Agreement</a> and <a href="#" style="color:#8b5cf6;font-weight:600;">CZM Code of Ethics</a>. I certify that all information is accurate and the organization is legally registered and Shariah-compliant in its operations.
                            </label>
                        </div>
                        <button type="submit" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);color:white;border:none;width:100%;padding:16px;border-radius:10px;font-weight:700;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                            <i class="bi bi-building-fill"></i> Register Organization
                        </button>
                        <p style="text-align:center;font-size:0.875rem;color:#9ca3af;margin-top:16px;">
                            Already registered? <a href="{{ url('/login') }}" style="color:#8b5cf6;font-weight:600;">Sign in to your dashboard</a>
                        </p>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div style="background:#f5f3ff;border:1px solid #ddd6fe;border-radius:16px;padding:24px;margin-bottom:20px;">
                    <h5 style="font-weight:700;color:#4c1d95;margin-bottom:16px;"><i class="bi bi-stars me-2" style="color:#8b5cf6;"></i>Partnership Benefits</h5>
                    @foreach(['Access to CZM beneficiary matching','Shared fund management tools','Field volunteer support network','Shariah audit & compliance reports','Monthly partner newsletter & updates','Public recognition on CZM portal','Capacity building workshops'] as $b)
                    <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;font-size:0.875rem;">
                        <i class="bi bi-check-circle-fill" style="color:#8b5cf6;flex-shrink:0;margin-top:2px;"></i>
                        <span>{{ $b }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="pub-card">
                    <h6 style="font-weight:700;margin-bottom:16px;">Approval Timeline</h6>
                    @foreach([['Application Review','2–3 business days','#8b5cf6'],['Document Verification','3–5 business days','#3b82f6'],['Field Assessment','5–7 business days','#f59e0b'],['Partnership Activation','Same day','#10b981']] as $step)
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                        <div style="width:10px;height:10px;border-radius:50%;background:{{ $step[2] }};flex-shrink:0;"></div>
                        <div>
                            <div style="font-weight:600;font-size:0.875rem;color:#111827;">{{ $step[0] }}</div>
                            <div style="font-size:0.75rem;color:#9ca3af;">Typical: {{ $step[1] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pub-card mt-4">
                    <h6 style="font-weight:700;margin-bottom:12px;"><i class="bi bi-envelope me-2" style="color:#8b5cf6;"></i>Partnership Inquiries</h6>
                    <div style="font-weight:700;color:#8b5cf6;">partner@czm.gov.bd</div>
                    <div style="font-size:0.82rem;color:#9ca3af;margin-top:4px;">or call: 16789 (Ext. 3)</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectOrgType(value, el) {
        document.querySelectorAll('.org-type-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input[type="radio"]').checked = true;
    }
    function applyPageTranslations(lang) {}
</script>
@endpush
