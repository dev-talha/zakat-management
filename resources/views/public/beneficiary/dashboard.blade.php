@extends('layouts.public')

@section('title', 'Beneficiary Dashboard | সুবিধাভোগী ড্যাশবোর্ড — CZM Bangladesh')

@push('styles')
<style>
    .dash-hero { background: linear-gradient(135deg, #0a4f2e 0%, #065f46 50%, #0d6e3f 100%); padding: 50px 0; color: white; }
    .dash-stat { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); border-radius: 14px; padding: 18px; text-align: center; }
    .dash-stat .stat-num { font-size: 1.3rem; font-weight: 800; display: block; word-break: break-all; }
    .dash-stat .stat-lbl { font-size: 0.75rem; opacity: 0.75; }
    .dash-card { background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
    .dash-card h5 { font-weight: 700; color: #111827; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #f3f4f6; }
    
    .timeline { position: relative; margin: 20px 0; padding-left: 30px; }
    .timeline::before { content: ''; position: absolute; left: 11px; top: 0; bottom: 0; width: 2px; background: #e5e7eb; }
    .timeline-item { position: relative; margin-bottom: 24px; }
    .timeline-item:last-child { margin-bottom: 0; }
    .timeline-icon { position: absolute; left: -30px; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; background: #e5e7eb; color: #6b7280; z-index: 2; }
    .timeline-item.active .timeline-icon { background: #3b82f6; color: white; box-shadow: 0 0 0 4px rgba(59,130,246,0.2); }
    .timeline-item.completed .timeline-icon { background: #10b981; color: white; }
    .timeline-item.rejected .timeline-icon { background: #ef4444; color: white; }
    .timeline-content { background: #f8faf9; border-radius: 12px; padding: 16px; border: 1px solid #e5e7eb; }
    .timeline-title { font-weight: 700; color: #111827; margin-bottom: 4px; }
    .timeline-date { font-size: 0.75rem; color: #6b7280; margin-bottom: 8px; }
    .table-custom th { font-weight: 700; color: #374151; font-size: 0.82rem; text-transform: uppercase; background: #f9fafb; }
    .table-custom td { font-size: 0.875rem; vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="dash-hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:56px;height:56px;background:rgba(255,255,255,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🤲</div>
                    <div>
                        <h2 style="font-size:1.4rem;font-weight:900;margin:0;">{{ $user->name }}</h2>
                        <div style="font-size:0.82rem;opacity:0.75;"><i class="bi bi-person-badge me-1"></i> <span id="lblAccountType">Beneficiary Account</span></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="dash-stat">
                            <span class="stat-num">{{ $beneficiary ? $beneficiary->application_no : 'N/A' }}</span>
                            <span class="stat-lbl" id="lblStatTrackId">Tracking ID</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="dash-stat">
                            <span class="stat-num">৳{{ number_format($totalAidAmount) }}</span>
                            <span class="stat-lbl" id="lblStatAidAmt">Total Aid Received</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="dash-stat">
                            <span class="stat-num">{{ $beneficiary ? ucfirst($beneficiary->status) : 'N/A' }}</span>
                            <span class="stat-lbl" id="lblStatStatus">Application Status</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="background:#f8faf9;padding:40px 0;min-height:60vh;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                
                {{-- Application Status Timeline --}}
                <div class="dash-card">
                    <h5 id="lblCardStatus"><i class="bi bi-calendar-check-fill me-2" style="color:#0d6e3f;"></i>Application Status</h5>
                    
                    @if($beneficiary && $latestCase)
                        <div class="timeline">
                            {{-- Step 1: Submitted --}}
                            <div class="timeline-item completed">
                                <div class="timeline-icon"><i class="bi bi-check-lg"></i></div>
                                <div class="timeline-content">
                                    <div class="timeline-title stepTitleSubmitted">Application Submitted</div>
                                    <div class="timeline-date">{{ $beneficiary->created_at->format('d M Y, h:i A') }}</div>
                                    <div class="stepDescSubmitted" style="font-size:0.875rem;color:#4b5563;">Your initial application and documents have been received successfully.</div>
                                </div>
                            </div>

                            {{-- Step 2: Verification --}}
                            @php
                                $step2Active = in_array($latestCase->stage, ['assessment', 'field_verification', 'supervisor_review']);
                                $step2Completed = !in_array($latestCase->stage, ['assessment', 'field_verification', 'supervisor_review']) && $latestCase->stage !== 'rejected';
                                $isRejected = $latestCase->stage === 'rejected' || $beneficiary->status === 'rejected';
                            @endphp
                            <div class="timeline-item {{ $step2Active ? 'active' : ($step2Completed ? 'completed' : '') }}">
                                <div class="timeline-icon">
                                    @if($step2Completed)
                                        <i class="bi bi-check-lg"></i>
                                    @else
                                        <i class="bi bi-search"></i>
                                    @endif
                                </div>
                                <div class="timeline-content" style="{{ $step2Active ? 'border-color:#3b82f6;background:#eff6ff;' : '' }}">
                                    <div class="timeline-title stepTitleVerify" style="{{ $step2Active ? 'color:#1d4ed8;' : '' }}">Verification in Progress</div>
                                    <div class="stepDescVerify" style="font-size:0.875rem;color:#4b5563;">A CZM field volunteer is currently verifying your details. They may contact you for a home visit.</div>
                                    @if($step2Active)
                                        <div class="timeline-date mt-1 stepDateActive">In progress</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Step 3: Approval --}}
                            @php
                                $step3Active = in_array($latestCase->stage, ['shariah_review', 'finance_review', 'approved']);
                                $step3Completed = in_array($latestCase->stage, ['disbursement', 'follow_up', 'closed']);
                            @endphp
                            <div class="timeline-item {{ $step3Active ? 'active' : ($step3Completed ? 'completed' : '') }}">
                                <div class="timeline-icon">
                                    @if($step3Completed)
                                        <i class="bi bi-check-lg"></i>
                                    @else
                                        <i class="bi bi-shield-check"></i>
                                    @endif
                                </div>
                                <div class="timeline-content" style="{{ $step3Active ? 'border-color:#3b82f6;background:#eff6ff;' : '' }}">
                                    <div class="timeline-title stepTitleApproval" style="{{ $step3Active ? 'color:#1d4ed8;' : '' }}">Approval</div>
                                    <div class="stepDescApproval" style="font-size:0.875rem;color:#4b5563;">Pending final approval from the board based on the verification report.</div>
                                    @if($step3Active)
                                        <div class="timeline-date mt-1 stepDateActive">In progress</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Step 4: Disbursement --}}
                            @php
                                $step4Active = in_array($latestCase->stage, ['disbursement', 'follow_up']);
                                $step4Completed = $latestCase->stage === 'closed';
                            @endphp
                            <div class="timeline-item {{ $step4Active ? 'active' : ($step4Completed ? 'completed' : '') }} {{ $isRejected ? 'rejected' : '' }}">
                                <div class="timeline-icon">
                                    @if($isRejected)
                                        <i class="bi bi-x-lg"></i>
                                    @elseif($step4Completed)
                                        <i class="bi bi-check-lg"></i>
                                    @else
                                        <i class="bi bi-cash"></i>
                                    @endif
                                </div>
                                <div class="timeline-content" style="{{ $step4Active ? 'border-color:#3b82f6;background:#eff6ff;' : '' }}">
                                    @if($isRejected)
                                        <div class="timeline-title text-danger stepTitleRejected">Application Rejected</div>
                                        <div class="stepDescRejected" style="font-size:0.875rem;color:#4b5563;">Your application did not meet the criteria required for Zakat funds.</div>
                                    @else
                                        <div class="timeline-title stepTitleDisburse" style="{{ $step4Active ? 'color:#1d4ed8;' : '' }}">Disbursement</div>
                                        <div class="stepDescDisburse" style="font-size:0.875rem;color:#4b5563;">Funds will be transferred to your registered mobile banking account once approved.</div>
                                        @if($step4Active)
                                            <div class="timeline-date mt-1 stepDateDisbursing">Processing payout</div>
                                        @elseif($step4Completed)
                                            <div class="timeline-date mt-1 stepDateDisbursed">Completed</div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div style="text-align:center;padding:30px 20px;" id="noActiveAppBlock">
                            <i class="bi bi-inbox fs-1 text-muted mb-2 d-block"></i>
                            <h6 style="font-weight:600;color:#6b7280;" class="noAppTitle">No Active Applications</h6>
                            <p style="font-size:0.875rem;color:#9ca3af;" class="noAppDesc">You do not currently have any active Zakat applications.</p>
                            <a href="{{ url('/apply') }}" class="btn btn-sm btn-success mt-2 btnApplyAid">Apply for Aid Now</a>
                        </div>
                    @endif
                </div>

                {{-- All Applications History --}}
                <div class="dash-card">
                    <h5 id="lblCardHistory"><i class="bi bi-clock-history me-2" style="color:#0284c7;"></i>Application History</h5>
                    @if($allApplications && $allApplications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-custom">
                                <thead>
                                    <tr>
                                        <th class="thAppNo">App No.</th>
                                        <th class="thCategory">Category</th>
                                        <th class="thDate">Date</th>
                                        <th class="thStatus">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allApplications as $app)
                                        <tr>
                                            <td style="font-weight:700;">{{ $app->application_no }}</td>
                                            <td><span class="badge bg-light text-dark">{{ ucfirst($app->zakat_category) }}</span></td>
                                            <td>{{ $app->created_at->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge" style="background:{{ $app->status === 'approved' ? '#10b981' : ($app->status === 'rejected' ? '#ef4444' : '#f59e0b') }}; color:white;">
                                                    {{ ucfirst($app->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="text-align:center;padding:30px 20px;border:1px dashed #e5e7eb;border-radius:12px;background:#f9fafb;">
                            <p style="font-size:0.875rem;color:#9ca3af;margin:0;" class="noHistoryDesc">No application history found.</p>
                        </div>
                    @endif
                </div>

                {{-- Disbursement History --}}
                <div class="dash-card">
                    <h5 id="lblCardDisbursements"><i class="bi bi-wallet-fill me-2" style="color:#10b981;"></i>Disbursement History</h5>
                    @if($distributions && $distributions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-custom">
                                <thead>
                                    <tr>
                                        <th class="thDate">Date</th>
                                        <th class="thAmount">Amount</th>
                                        <th class="thChannel">Channel</th>
                                        <th class="thStatus">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($distributions as $dist)
                                        <tr>
                                            <td>{{ $dist->created_at->format('d M Y') }}</td>
                                            <td style="font-weight:700; color:#10b981;">৳{{ number_format($dist->approved_amount) }}</td>
                                            <td>{{ $dist->disbursement->payout_channel ?? 'bKash' }}</td>
                                            <td>
                                                <span class="badge bg-success">Received</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="text-align:center;padding:30px 20px;border:1px dashed #e5e7eb;border-radius:12px;background:#f9fafb;">
                            <i class="bi bi-inbox fs-1 text-muted mb-2 d-block"></i>
                            <h6 style="font-weight:600;color:#6b7280;" class="noDisbTitle">No disbursements yet</h6>
                            <p style="font-size:0.875rem;color:#9ca3af;margin:0;" class="noDisbDesc">Any funds allocated to you will appear here with transaction details.</p>
                        </div>
                    @endif
                </div>

            </div>
            
            <div class="col-lg-4">
                {{-- Beneficiary Details --}}
                <div class="dash-card">
                    <h5 id="lblCardDetails"><i class="bi bi-person-vcard me-2" style="color:#3b82f6;"></i>My Details</h5>
                    <div class="d-flex flex-column gap-3" style="font-size:0.875rem;">
                        <div>
                            <div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;" class="detailName">Name</div>
                            <div style="font-weight:600;">{{ $user->name }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;" class="detailIdentity">Identity (NID/Birth Cert)</div>
                            <div style="font-weight:600;font-family:monospace;">{{ $beneficiary ? ($beneficiary->identity_no ?? 'N/A') : 'N/A' }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;" class="detailMobile">Contact Number</div>
                            <div style="font-weight:600;">{{ $beneficiary ? ($beneficiary->mobile ?? $user->mobile) : $user->mobile }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;" class="detailAccount">Receiving Account</div>
                            <div style="background:#f3f4f6;padding:8px;border-radius:6px;margin-top:4px;">
                                <div style="font-weight:600;color:#111827;">{{ $beneficiary ? ($beneficiary->mobile_banking_provider ?? 'bKash') : 'bKash' }}</div>
                                <div style="font-family:monospace;">{{ $beneficiary ? ($beneficiary->mobile_banking_account ?? 'N/A') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Support Info --}}
                <div class="dash-card">
                    <h5 id="lblCardSupport"><i class="bi bi-info-circle-fill me-2" style="color:#f59e0b;"></i>Support</h5>
                    <p style="font-size:0.82rem;color:#6b7280;margin-bottom:12px;" class="supportText">If you have any questions about your application status:</p>
                    <div style="font-weight:700;color:#d97706;"><i class="bi bi-telephone"></i> 16789</div>
                    <div style="font-size:0.82rem;color:#9ca3af;" class="supportHours">Mon–Fri, 9AM–5PM</div>
                </div>
                
                {{-- Logout --}}
                <div class="dash-card">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="display:flex;align-items:center;gap:10px;padding:10px 12px;width:100%;border:none;background:none;color:#dc2626;font-size:0.875rem;font-weight:600;cursor:pointer;border-radius:8px;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                            <i class="bi bi-box-arrow-right" style="width:20px;text-align:center;"></i> <span class="lblSignOut">Sign Out</span>
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
    // Translations implementation for dashboard
    const dashboardTranslations = {
        lblAccountType: { en: 'Beneficiary Account', bn: 'সুবিধাভোগী অ্যাকাউন্ট' },
        lblStatTrackId: { en: 'Tracking ID', bn: 'ট্র্যাকিং আইডি' },
        lblStatAidAmt: { en: 'Total Aid Received', bn: 'মোট সাহায্য প্রাপ্তি' },
        lblStatStatus: { en: 'Application Status', bn: 'আবেদনের অবস্থা' },
        lblCardStatus: { en: 'Application Status', bn: 'আবেদনের অবস্থা' },
        lblCardHistory: { en: 'Application History', bn: 'আবেদনের ইতিহাস' },
        lblCardDisbursements: { en: 'Disbursement History', bn: 'অর্থ বিতরণের ইতিহাস' },
        lblCardDetails: { en: 'My Details', bn: 'আমার তথ্য' },
        lblCardSupport: { en: 'Support', bn: 'সহায়তা' }
    };

    function applyPageTranslations(lang) {
        // Text strings
        Object.keys(dashboardTranslations).forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                // Keep the icon if it has one
                const icon = el.querySelector('i');
                if (icon) {
                    el.innerHTML = '';
                    el.appendChild(icon);
                    el.appendChild(document.createTextNode(' ' + dashboardTranslations[id][lang]));
                } else {
                    el.textContent = dashboardTranslations[id][lang];
                }
            }
        });

        // Other elements via class query
        const translateClass = (className, enText, bnText) => {
            document.querySelectorAll('.' + className).forEach(el => {
                el.textContent = lang === 'en' ? enText : bnText;
            });
        };

        // Table headers
        translateClass('thAppNo', 'App No.', 'আবেদন নম্বর');
        translateClass('thCategory', 'Category', 'শ্রেণী');
        translateClass('thDate', 'Date', 'তারিখ');
        translateClass('thStatus', 'Status', 'অবস্থা');
        translateClass('thAmount', 'Amount', 'পরিমাণ');
        translateClass('thChannel', 'Channel', 'মাধ্যম');

        // Form & details labels
        translateClass('detailName', 'Name', 'নাম');
        translateClass('detailIdentity', 'Identity (NID/Birth Cert)', 'পরিচয়পত্র (এনআইডি/জন্ম নিবন্ধন)');
        translateClass('detailMobile', 'Contact Number', 'মোবাইল নম্বর');
        translateClass('detailAccount', 'Receiving Account', 'টাকা গ্রহণের অ্যাকাউন্ট');
        translateClass('supportText', 'If you have any questions about your application status:', 'আপনার আবেদনের অবস্থা সম্পর্কে কোনো প্রশ্ন থাকলে:');
        translateClass('supportHours', 'Mon–Fri, 9AM–5PM', 'সোম–শুক্র, সকাল ৯টা–বিকাল ৫টা');
        translateClass('lblSignOut', 'Sign Out', 'লগ আউট');

        // Empty states
        translateClass('noAppTitle', 'No Active Applications', 'কোনো সক্রিয় আবেদন নেই');
        translateClass('noAppDesc', 'You do not currently have any active Zakat applications.', 'আপনার বর্তমানে কোনো সক্রিয় যাকাত আবেদন নেই।');
        translateClass('btnApplyAid', 'Apply for Aid Now', 'সহায়তার জন্য আবেদন করুন');
        translateClass('noHistoryDesc', 'No application history found.', 'আবেদনের কোনো ইতিহাস পাওয়া যায়নি।');
        translateClass('noDisbTitle', 'No disbursements yet', 'এখনো কোনো অর্থ বিতরণ করা হয়নি');
        translateClass('noDisbDesc', 'Any funds allocated to you will appear here with transaction details.', 'আপনার জন্য বরাদ্দকৃত কোনো অর্থ লেনদেনের বিবরণ সহ এখানে প্রদর্শিত হবে।');

        // Timeline node translations
        translateClass('stepTitleSubmitted', 'Application Submitted', 'আবেদন জমা দেওয়া হয়েছে');
        translateClass('stepDescSubmitted', 'Your initial application and documents have been received successfully.', 'আপনার প্রাথমিক আবেদন এবং নথিপত্র সফলভাবে গৃহীত হয়েছে।');
        translateClass('stepTitleVerify', 'Verification in Progress', 'যাচাইকরণ চলমান');
        translateClass('stepDescVerify', 'A CZM field volunteer is currently verifying your details. They may contact you for a home visit.', 'একজন সিজেডএম মাঠ স্বেচ্ছাসেবক বর্তমানে আপনার তথ্য যাচাই করছেন। তারা গৃহ পরিদর্শনের জন্য আপনার সাথে যোগাযোগ করতে পারেন।');
        translateClass('stepTitleApproval', 'Approval', 'অনুমোদন');
        translateClass('stepDescApproval', 'Pending final approval from the board based on the verification report.', 'যাচাই রিপোর্টের ভিত্তিতে বোর্ড থেকে চূড়ান্ত অনুমোদনের অপেক্ষায় রয়েছে।');
        translateClass('stepTitleDisburse', 'Disbursement', 'অর্থ বিতরণ');
        translateClass('stepDescDisburse', 'Funds will be transferred to your registered mobile banking account once approved.', 'অনুমোদিত হওয়ার পর আপনার নিবন্ধিত মোবাইল ব্যাংকিং অ্যাকাউন্টে ফান্ড ট্রান্সফার করা হবে।');
        translateClass('stepTitleRejected', 'Application Rejected', 'আবেদনটি নামঞ্জুর করা হয়েছে');
        translateClass('stepDescRejected', 'Your application did not meet the criteria required for Zakat funds.', 'আপনার আবেদনটি যাকাত ফান্ডের জন্য প্রয়োজনীয় মানদণ্ড পূরণ করেনি।');

        translateClass('stepDateActive', 'In progress', 'চলমান');
        translateClass('stepDateDisbursing', 'Processing payout', 'পেমেন্ট প্রক্রিয়াধীন');
        translateClass('stepDateDisbursed', 'Completed', 'সম্পন্ন হয়েছে');
    }
</script>
@endpush
