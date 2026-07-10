@extends('layouts.public')

@section('title', 'Volunteer Registration | স্বেচ্ছাসেবী নিবন্ধন')
@section('meta_description', 'Join as a CZM volunteer in Bangladesh. Help verify beneficiary applications, conduct field visits, and be part of the Zakat mission.')

@push('styles')
<style>
    .reg-hero { background: linear-gradient(135deg, #fffbeb 0%, #ffffff 60%); padding: 60px 0 40px; border-bottom: 1px solid #e5e7eb; }
    .reg-hero h1 { font-size: clamp(1.8rem,3vw,2.4rem); font-weight: 800; color: #111827; }
    .form-section { background: white; border: 1px solid #e5e7eb; border-radius: 20px; padding: 36px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); margin-bottom: 24px; }
    .form-section-title { font-size: 1rem; font-weight: 700; color: #111827; padding-bottom: 14px; border-bottom: 2px solid #f3f4f6; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
    .form-section-title i { color: #f59e0b; font-size: 1.1rem; }
    .skill-check { display: flex; align-items: center; gap: 8px; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; cursor: pointer; font-size: 0.875rem; transition: all 0.2s; }
    .skill-check:hover { border-color: #f59e0b; background: #fffbeb; }
    .skill-check input:checked + span { color: #d97706; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="reg-hero">
    <div class="container">
        <div class="d-flex align-items-center gap-2 mb-3" style="font-size:0.85rem;color:#9ca3af;">
            <a href="{{ url('/') }}" style="color:#f59e0b;text-decoration:none;">Home</a>
            <i class="bi bi-chevron-right"></i> Volunteer Registration
        </div>
        <div class="section-badge mb-3" style="background:rgba(245,158,11,0.08);color:#d97706;"><i class="bi bi-people-fill"></i> Join Our Team</div>
        <h1 class="mb-3">Become a CZM Volunteer<br><span style="color:#f59e0b;">স্বেচ্ছাসেবী হিসেবে যোগ দিন</span></h1>
        <p style="color:#6b7280;">Help verify beneficiary applications, conduct field visits, and make a real difference in your community.</p>
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

                <form method="POST" action="{{ route('volunteer.register.store') }}">
                    @csrf
                    {{-- Personal Info --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-person-fill"></i> Personal Information</div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Full Name (English) *</label>
                                <input type="text" name="name" class="pub-form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">পুরো নাম (বাংলা)</label>
                                <input type="text" name="name_bn" class="pub-form-control" value="{{ old('name_bn') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Mobile Number *</label>
                                <input type="tel" name="mobile" class="pub-form-control" value="{{ old('mobile') }}" placeholder="01XXXXXXXXX" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Email Address *</label>
                                <input type="email" name="email" class="pub-form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">NID Number *</label>
                                <input type="text" name="nid_no" class="pub-form-control" value="{{ old('nid_no') }}" required>
                                <div class="pub-form-hint">Required for field volunteer identity verification</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Date of Birth</label>
                                <input type="date" name="dob" class="pub-form-control" value="{{ old('dob') }}">
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label">Occupation</label>
                                <input type="text" name="occupation" class="pub-form-control" value="{{ old('occupation') }}" placeholder="e.g. Teacher, Student, Retired Officer">
                            </div>
                        </div>
                    </div>

                    {{-- Organization Assignment --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-building-fill"></i> Sponsoring Organization</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="pub-form-label">Which organization are you volunteering for? *</label>
                                <select name="organization_id" class="pub-form-control form-select" required>
                                    <option value="">-- Select Partner Organization --</option>
                                    @if(isset($organizations))
                                        @foreach($organizations as $org)
                                        <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                                            {{ $org->name_en }} {{ $org->name_bn ? ' ('.$org->name_bn.')' : '' }} - {{ $org->district }}
                                        </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="pub-form-hint">Volunteers must be registered under a recognized partner organization.</div>
                            </div>
                        </div>
                    </div>

                    {{-- Location & Coverage --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-geo-alt-fill"></i> Location & Coverage Area</div>
                        <x-location-picker :division-required="true" :district-required="true" :emit-ids="true" />
                        <div class="row g-3 mt-1">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Coverage Level</label>
                                <select name="coverage_level" class="pub-form-control">
                                    <option value="ward" {{ old('coverage_level') == 'ward' ? 'selected' : '' }}>Ward Level</option>
                                    <option value="union" {{ old('coverage_level') == 'union' ? 'selected' : '' }}>Union Level</option>
                                    <option value="upazila" {{ old('coverage_level') == 'upazila' ? 'selected' : '' }}>Upazila Level</option>
                                    <option value="district" {{ old('coverage_level') == 'district' ? 'selected' : '' }}>District Level</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label">Full Address</label>
                                <textarea name="address" class="pub-form-control" rows="2" placeholder="Village/Road, House no.">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Skills --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-star-fill"></i> Skills & Availability</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="pub-form-label mb-3">Select your skills (choose all that apply)</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach(['Field Verification','Home Visits','Data Entry','Report Writing','Photography','Community Outreach','Social Media','Translation (EN/BN)','Medical Knowledge','Legal Aid'] as $skill)
                                    <label class="skill-check">
                                        <input type="checkbox" name="skills[]" value="{{ $skill }}" style="accent-color:#f59e0b;" {{ in_array($skill, old('skills', [])) ? 'checked' : '' }}>
                                        <span>{{ $skill }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Weekly Availability</label>
                                <select name="weekly_hours" class="pub-form-control">
                                    <option value="2-5">2–5 hours/week</option>
                                    <option value="5-10">5–10 hours/week</option>
                                    <option value="10-20">10–20 hours/week</option>
                                    <option value="20+">20+ hours/week (Full-time)</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Do you have a vehicle?</label>
                                <select name="has_vehicle" class="pub-form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes – Motorcycle</option>
                                    <option value="2">Yes – Car/CNG</option>
                                    <option value="3">Yes – Bicycle</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="pub-form-label">Why do you want to volunteer? (Optional)</label>
                                <textarea name="motivation" class="pub-form-control" rows="3" placeholder="Share your motivation for joining...">{{ old('motivation') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Account --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-lock-fill"></i> Account Password</div>
                        <div class="row g-3">
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
                            <input type="checkbox" id="vTerms" required style="margin-top:3px;accent-color:#f59e0b;">
                            <label for="vTerms" style="font-size:0.875rem;color:#374151;cursor:pointer;">
                                I agree to the <a href="#" style="color:#f59e0b;font-weight:600;">Volunteer Code of Conduct</a> and <a href="#" style="color:#f59e0b;font-weight:600;">Terms of Service</a>. I understand that field visits may be required and that all beneficiary information must be kept confidential.
                            </label>
                        </div>
                        <button type="submit" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:white;border:none;width:100%;padding:16px;border-radius:10px;font-weight:700;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                            <i class="bi bi-people-fill"></i> Register as Volunteer
                        </button>
                        <p style="text-align:center;font-size:0.875rem;color:#9ca3af;margin-top:16px;">
                            Already registered? <a href="{{ url('/login') }}" style="color:#f59e0b;font-weight:600;">Sign in to your dashboard</a>
                        </p>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:16px;padding:24px;margin-bottom:20px;">
                    <h5 style="font-weight:700;color:#92400e;margin-bottom:16px;"><i class="bi bi-award-fill me-2" style="color:#f59e0b;"></i>Volunteer Benefits</h5>
                    @foreach(['Official CZM Volunteer Certificate','Monthly performance-based stipend','Free field training program','Priority hiring for full-time roles','Annual volunteer recognition awards','Access to exclusive volunteer community'] as $b)
                    <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:12px;font-size:0.875rem;">
                        <i class="bi bi-check-circle-fill" style="color:#f59e0b;flex-shrink:0;margin-top:2px;"></i>
                        <span>{{ $b }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="pub-card">
                    <h6 style="font-weight:700;margin-bottom:16px;">Onboarding Process</h6>
                    @foreach([['Submit Application', 'Fill this form', '#f59e0b'],['Background Check', 'NID & reference verification', '#3b82f6'],['Training', '2-day orientation program', '#8b5cf6'],['Field Assignment', 'Assigned to your area', '#10b981']] as $i => $step)
                    <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:{{ $i < 3 ? '16px' : '0' }};">
                        <div style="width:28px;height:28px;background:{{ $step[2] }};border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.78rem;flex-shrink:0;">{{ $i+1 }}</div>
                        <div>
                            <div style="font-weight:700;font-size:0.875rem;color:#111827;">{{ $step[0] }}</div>
                            <div style="font-size:0.78rem;color:#9ca3af;">{{ $step[1] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pub-card mt-4">
                    <h6 style="font-weight:700;margin-bottom:12px;"><i class="bi bi-telephone me-2" style="color:#f59e0b;"></i>Questions?</h6>
                    <p style="font-size:0.82rem;color:#6b7280;margin-bottom:12px;">Contact our Volunteer Coordinator:</p>
                    <div style="font-weight:700;color:#f59e0b;">volunteer@czm.gov.bd</div>
                    <div style="font-size:0.82rem;color:#9ca3af;">Mon–Fri, 9AM–5PM</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function applyPageTranslations(lang) {}
</script>
@endpush
