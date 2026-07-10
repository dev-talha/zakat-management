@extends('layouts.public')

@section('title', 'Apply for Zakat Aid | যাকাত সহায়তার আবেদন')
@section('meta_description', 'Apply for Zakat assistance through CZM Bangladesh. Our transparent, Shariah-compliant process ensures aid reaches those who truly need it.')

@push('styles')
<style>
    .reg-hero { background: linear-gradient(135deg, #eff6ff 0%, #ffffff 60%); padding: 60px 0 40px; border-bottom: 1px solid #e5e7eb; }
    .form-section { background: white; border: 1px solid #e5e7eb; border-radius: 20px; padding: 36px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); margin-bottom: 24px; }
    .form-section-title { font-size: 1rem; font-weight: 700; color: #111827; padding-bottom: 14px; border-bottom: 2px solid #f3f4f6; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
    .form-section-title i { color: #3b82f6; font-size: 1.1rem; }
    .category-fields { display: none; padding: 20px; background: #eff6ff; border: 1px dashed #bfdbfe; border-radius: 12px; margin-top: 12px; animation: fadeIn 0.3s ease; }
    .category-fields.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    .category-btn {
        padding: 14px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
        font-size: 0.82rem;
        font-weight: 600;
    }
    .category-btn:hover { border-color: #3b82f6; background: #eff6ff; }
    .category-btn.selected { border-color: #3b82f6; background: #eff6ff; color: #1d4ed8; }
    .category-btn .cat-icon { font-size: 1.5rem; display: block; margin-bottom: 6px; }
    .category-btn small { display: block; font-weight: 400; color: #9ca3af; font-size: 0.72rem; }
</style>
@endpush

@section('content')
<div class="reg-hero">
    <div class="container">
        <div class="d-flex align-items-center gap-2 mb-3" style="font-size:0.85rem;color:#9ca3af;">
            <a href="{{ url('/') }}" style="color:#3b82f6;text-decoration:none;">Home</a>
            <i class="bi bi-chevron-right"></i> Apply for Aid
        </div>
        <div class="section-badge mb-3" style="background:rgba(59,130,246,0.08);color:#1d4ed8;"><i class="bi bi-hand-index-fill"></i> Beneficiary Application</div>
        <h1 class="mb-3">Apply for Zakat Assistance<br><span style="color:#3b82f6;">যাকাত সহায়তার আবেদন</span></h1>
        <p style="color:#6b7280;max-width:600px;">Our Shariah-certified review process ensures that Zakat reaches those who truly need it. Applications are processed within 3–7 business days.</p>
    </div>
</div>

<div style="background:#f8faf9;padding:60px 0;">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                @if($errors->any())
                <div class="pub-alert pub-alert-error mb-4">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div><strong>Please fix the following:</strong><ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                </div>
                @endif

                <form method="POST" action="{{ route('public.beneficiary.store') }}" id="zakatForm">
                    @csrf

                    {{-- Personal Info --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-person-fill"></i> Personal Information (ব্যক্তিগত তথ্য)</div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Full Name (সম্পূর্ণ নাম) *</label>
                                <input type="text" name="primary_person_name" class="pub-form-control" value="{{ old('primary_person_name') }}" required placeholder="Your full name">
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Mobile Number (মোবাইল নম্বর) *</label>
                                <input type="tel" name="mobile" class="pub-form-control" placeholder="01XXXXXXXXX" value="{{ old('mobile') }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Identity Type (পরিচয়পত্রের ধরন)</label>
                                <select name="identity_type" class="pub-form-control">
                                    <option value="nid" {{ old('identity_type') == 'nid' ? 'selected' : '' }}>NID (জাতীয় পরিচয়পত্র)</option>
                                    <option value="birth_cert" {{ old('identity_type') == 'birth_cert' ? 'selected' : '' }}>Birth Certificate (জন্ম নিবন্ধন)</option>
                                    <option value="none" {{ old('identity_type') == 'none' ? 'selected' : '' }}>None (নেই)</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Identity Number (পরিচয়পত্র নম্বর)</label>
                                <input type="text" name="identity_no" class="pub-form-control" value="{{ old('identity_no') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-geo-alt-fill"></i> Location</div>
                        <x-location-picker :division-required="true" :district-required="true" :emit-ids="true" />
                        <div class="row g-3 mt-1">
                            <div class="col-12">
                                <label class="pub-form-label">Full Address (পূর্ণাঙ্গ ঠিকানা)</label>
                                <textarea name="address" class="pub-form-control" rows="2" placeholder="Village / Road, House no.">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile Banking --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-wallet2-fill"></i> Mobile Banking Details (মোবাইল ব্যাংকিং তথ্য) *</div>
                        <p style="font-size:0.875rem;color:#6b7280;margin-bottom:20px;">Assistance will be transferred directly to this account. Please provide accurate details. (সহায়তা সরাসরি এই একাউন্টে পাঠানো হবে।)</p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Provider (সেবাদাতা) *</label>
                                <select name="mobile_banking_provider" class="pub-form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="bkash" {{ old('mobile_banking_provider') == 'bkash' ? 'selected' : '' }}>bKash (বিকাশ)</option>
                                    <option value="nagad" {{ old('mobile_banking_provider') == 'nagad' ? 'selected' : '' }}>Nagad (নগদ)</option>
                                    <option value="rocket" {{ old('mobile_banking_provider') == 'rocket' ? 'selected' : '' }}>Rocket (রকেট)</option>
                                    <option value="upay" {{ old('mobile_banking_provider') == 'upay' ? 'selected' : '' }}>Upay (উপায়)</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Account Number (একাউন্ট নম্বর) *</label>
                                <input type="text" name="mobile_banking_account" class="pub-form-control" placeholder="01XXXXXXXXX" value="{{ old('mobile_banking_account') }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- Zakat Category --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-tags-fill"></i> Zakat Category (যাকাতের খাত) *</div>
                        <p style="font-size:0.875rem;color:#6b7280;margin-bottom:16px;">Select the category that best describes your situation:</p>

                        <div class="row g-3 mb-3">
                            @foreach(\App\Models\Beneficiary::ZAKAT_CATEGORIES as $key => $cat)
                            <div class="col-6 col-md-4">
                                <div class="category-btn {{ old('zakat_category') == $key ? 'selected' : '' }}" onclick="selectCat('{{ $key }}', this)">
                                    <span class="cat-icon">{{ $cat['icon'] }}</span>
                                    <strong>{{ $cat['en'] }}</strong>
                                    <small>{{ $cat['bn'] }}</small>
                                    <input type="radio" name="zakat_category" value="{{ $key }}" style="display:none;" {{ old('zakat_category') == $key ? 'checked' : '' }}>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Dynamic Fields --}}
                        <div id="fields_faqir_miskin" class="category-fields">
                            <h6 style="font-weight:700;color:#1d4ed8;margin-bottom:12px;">Poverty Details (দরিদ্রতার বিবরণ)</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="pub-form-label">Family Members (পরিবারের সদস্য)</label>
                                    <input type="number" name="family_members" class="pub-form-control" placeholder="Total household members" min="1">
                                </div>
                                <div class="col-sm-6">
                                    <label class="pub-form-label">Daily Average Income ৳</label>
                                    <input type="number" name="daily_income" class="pub-form-control" placeholder="0" min="0">
                                </div>
                            </div>
                        </div>
                        <div id="fields_gharimin" class="category-fields">
                            <h6 style="font-weight:700;color:#1d4ed8;margin-bottom:12px;">Debt Details (ঋণের বিবরণ)</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="pub-form-label">Total Debt Amount ৳ (মোট ঋণ)</label>
                                    <input type="number" name="debt_amount" class="pub-form-control" placeholder="0" min="0">
                                </div>
                                <div class="col-sm-6">
                                    <label class="pub-form-label">Creditor Name / Bank (কাকে ঋণী)</label>
                                    <input type="text" name="creditor_name" class="pub-form-control">
                                </div>
                                <div class="col-12">
                                    <label class="pub-form-label">Reason for Debt (ঋণ হওয়ার কারণ)</label>
                                    <input type="text" name="reason_for_debt" class="pub-form-control">
                                </div>
                            </div>
                        </div>
                        <div id="fields_ibnussabil" class="category-fields">
                            <h6 style="font-weight:700;color:#1d4ed8;margin-bottom:12px;">Travel Details (ভ্রমণ বিবরণ)</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="pub-form-label">Current Location</label>
                                    <input type="text" name="current_location" class="pub-form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label class="pub-form-label">Destination (গন্তব্য)</label>
                                    <input type="text" name="destination" class="pub-form-control">
                                </div>
                                <div class="col-12">
                                    <label class="pub-form-label">Reason / Situation (কারণ)</label>
                                    <input type="text" name="travel_reason" class="pub-form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Amount & Submit --}}
                    <div class="form-section">
                        <div class="form-section-title"><i class="bi bi-currency-dollar"></i> Aid Request (সহায়তার পরিমাণ)</div>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label class="pub-form-label">Reason for Assistance (সহায়তার কারণ)</label>
                                <select name="assistance_reason" class="pub-form-control">
                                    <option value="general" {{ old('assistance_reason') == 'general' ? 'selected' : '' }}>General / সাধারণ</option>
                                    <option value="medical" {{ old('assistance_reason') == 'medical' ? 'selected' : '' }}>Medical Emergency / চিকিৎসা জরুরি</option>
                                    <option value="food" {{ old('assistance_reason') == 'food' ? 'selected' : '' }}>Food / খাদ্য</option>
                                    <option value="education" {{ old('assistance_reason') == 'education' ? 'selected' : '' }}>Education / শিক্ষা</option>
                                    <option value="housing" {{ old('assistance_reason') == 'housing' ? 'selected' : '' }}>Housing / বাসস্থান</option>
                                    <option value="livelihood" {{ old('assistance_reason') == 'livelihood' ? 'selected' : '' }}>Livelihood / জীবিকা</option>
                                    <option value="emergency" {{ old('assistance_reason') == 'emergency' ? 'selected' : '' }}>Other Emergency / অন্যান্য জরুরি</option>
                                </select>
                                <div class="pub-form-hint">Medical emergencies are recorded here, separate from the Zakat category.</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="pub-form-label">Requested Amount ৳ (কত টাকা প্রয়োজন?)</label>
                                <input type="number" name="requested_amount" class="pub-form-control" placeholder="0" min="0">
                                <div class="pub-form-hint">Final disbursement amount is determined by the Shariah review board.</div>
                            </div>
                        </div>
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px;display:flex;align-items:flex-start;gap:12px;margin-bottom:20px;">
                            <i class="bi bi-shield-check-fill" style="color:#10b981;font-size:1.3rem;flex-shrink:0;"></i>
                            <p style="font-size:0.875rem;color:#166534;margin:0;">
                                <strong>Declaration:</strong> I testify that the information provided is true to the best of my knowledge, and I am eligible to receive Zakat according to Islamic Shariah. I understand that providing false information is a serious matter in Islam and before the law. <em>(আমি ঘোষণা করছি যে উপরের তথ্য সত্য এবং আমি শরীয়াহ অনুযায়ী যাকাত গ্রহণের যোগ্য।)</em>
                            </p>
                        </div>
                        <button type="submit" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:white;border:none;width:100%;padding:16px;border-radius:10px;font-weight:700;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                            <i class="bi bi-send-fill"></i> Submit Application (আবেদন জমা দিন)
                        </button>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:16px;padding:24px;margin-bottom:20px;">
                    <h5 style="font-weight:700;color:#1e3a8a;margin-bottom:16px;"><i class="bi bi-clock-history me-2" style="color:#3b82f6;"></i>Application Process</h5>
                    @foreach([['Submit Application','Fill this form','#3b82f6'],['AI Verification','Automated eligibility check','#8b5cf6'],['Field Visit','Volunteer field assessment','#f59e0b'],['Board Review','Shariah committee decision','#10b981'],['Disbursement','Direct mobile transfer','#059669']] as $i => $step)
                    <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:{{ $i < 4 ? '14px' : '0' }};">
                        <div style="width:28px;height:28px;border-radius:50%;background:{{ $step[2] }};color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.78rem;flex-shrink:0;">{{ $i+1 }}</div>
                        <div>
                            <div style="font-weight:700;font-size:0.875rem;color:#111827;">{{ $step[0] }}</div>
                            <div style="font-size:0.75rem;color:#6b7280;">{{ $step[1] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pub-card">
                    <h6 style="font-weight:700;margin-bottom:16px;"><i class="bi bi-question-circle me-2" style="color:#3b82f6;"></i>Who Can Apply?</h6>
                    @foreach(['Extremely poor (Faqir) individuals','Needy families (Miskin)','New/returning Muslims needing support (Muallafatul Qulub)','Those seeking freedom from bondage (Fir-Riqab)','People with overwhelming debt (Gharimin)','In the cause of Allah (Fi Sabilillah)','Stranded travelers (Ibnus Sabil)'] as $r)
                    <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;font-size:0.82rem;">
                        <i class="bi bi-check-circle-fill" style="color:#3b82f6;flex-shrink:0;margin-top:2px;"></i>
                        <span>{{ $r }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="pub-card mt-4">
                    <h6 style="font-weight:700;margin-bottom:12px;"><i class="bi bi-telephone me-2" style="color:#3b82f6;"></i>Need Help with Application?</h6>
                    <div style="font-weight:700;color:#3b82f6;">16789</div>
                    <div style="font-size:0.82rem;color:#9ca3af;margin-top:4px;">Free helpline, 7 days/week</div>
                    <div style="font-weight:600;color:#3b82f6;margin-top:8px;">beneficiary@czm.gov.bd</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectCat(value, el) {
        document.querySelectorAll('.category-btn').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input[type="radio"]').checked = true;

        // Show/hide dynamic fields
        document.querySelectorAll('.category-fields').forEach(f => f.classList.remove('active'));
        if (value === 'faqir' || value === 'miskin') {
            document.getElementById('fields_faqir_miskin').classList.add('active');
        } else if (value === 'gharimin') {
            document.getElementById('fields_gharimin').classList.add('active');
        } else if (value === 'ibnussabil') {
            document.getElementById('fields_ibnussabil').classList.add('active');
        }
    }

    // Restore selection from old()
    const preSelected = '{{ old("zakat_category") }}';
    if (preSelected) {
        const el = document.querySelector(`.category-btn input[value="${preSelected}"]`);
        if (el) selectCat(preSelected, el.closest('.category-btn'));
    }

    function applyPageTranslations(lang) {}
</script>
@endpush
