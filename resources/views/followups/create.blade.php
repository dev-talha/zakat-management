@extends('layouts.app')
@section('title', 'Post-Disbursement Follow-Up')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cases.index') }}">Cases</a></li>
    <li class="breadcrumb-item"><a href="{{ route('cases.show', $case) }}">{{ $case->case_no }}</a></li>
    <li class="breadcrumb-item active">Follow Up</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h2>Submit Follow-Up Report</h2>
        <p class="page-subtitle">যাকাত বিতরণের পর ফলোআপ</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="glass-card mb-3 border-success border-opacity-25">
            <div class="card-body">
                <h6 class="text-success fw-bold"><i class="bi bi-info-circle me-2"></i>Case Info</h6>
                <div class="mb-2"><strong>Case No:</strong> {{ $case->case_no }}</div>
                <div class="mb-2"><strong>Beneficiary:</strong> {{ $case->beneficiary?->primary_person_name }}</div>
                <div class="mb-2"><strong>Approved Amount:</strong> ৳{{ number_format($case->approved_amount) }}</div>
                <div><strong>Zakat Category:</strong> {{ ucfirst($case->case_type) }}</div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="glass-card">
            <div class="card-body">
                <form method="POST" action="{{ route('followups.store') }}">
                    @csrf
                    <input type="hidden" name="case_id" value="{{ $case->id }}">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date of Follow-Up (ফলোআপের তারিখ)</label>
                            <input type="date" name="follow_up_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Were the funds utilized properly? (টাকা কি সঠিকভাবে ব্যবহৃত হয়েছে?)</label>
                            <select name="funds_utilized_properly" class="form-select" required>
                                <option value="1">Yes, properly utilized (হ্যাঁ)</option>
                                <option value="0">No, misused or diverted (না)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Impact Rating (প্রভাব রেটিং)</label>
                            <select name="impact_rating" class="form-select" required>
                                <option value="5">5 - Excellent (দারুণ উন্নতি)</option>
                                <option value="4">4 - Good (ভালো)</option>
                                <option value="3" selected>3 - Moderate (মোটামুটি)</option>
                                <option value="2">2 - Poor (তেমন উন্নতি নেই)</option>
                                <option value="1">1 - Very Poor (অবস্থার অবনতি)</option>
                            </select>
                            <div class="form-text">Assess the beneficiary's current state compared to before receiving Zakat.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Next Scheduled Follow-up (পরবর্তী ফলোআপ - Optional)</label>
                            <input type="date" name="next_follow_up_date" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Detailed Report / Notes (বিস্তারিত প্রতিবেদন)</label>
                            <textarea name="notes" class="form-control" rows="4" required placeholder="Describe the current living condition, how the money was spent, etc."></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-czm-primary"><i class="bi bi-save me-2"></i>Submit Report</button>
                        <a href="{{ route('cases.show', $case) }}" class="btn btn-czm-outline ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
