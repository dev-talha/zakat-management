@extends('layouts.app')
@section('title', 'File Complaint')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('complaints.index') }}">Complaints</a></li>
    <li class="breadcrumb-item active">File Ticket</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>File Complaint or Grievance</h2>
        <p class="page-subtitle">নতুন অভিযোগ বা সহায়তা টিকিট দায়ের করুন</p>
    </div>
    <a href="{{ route('complaints.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back to Complaints</a>
</div>

<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="glass-card">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-chat-square-text me-2 text-primary"></i>Grievance Intake Form</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('complaints.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row g-4">
                        <!-- Complainant Name -->
                        <div class="col-md-6">
                            <label class="form-label">Your Name / Complainant Name</label>
                            <input type="text" name="complainant_name" class="form-control" placeholder="e.g., Abdur Rahman" value="{{ old('complainant_name') }}">
                        </div>

                        <!-- Complainant Contact -->
                        <div class="col-md-6">
                            <label class="form-label">Contact Details (Mobile / Email)</label>
                            <input type="text" name="complainant_contact" class="form-control" placeholder="e.g., +8801700000000" value="{{ old('complainant_contact') }}">
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label class="form-label">Grievance Category</label>
                            <select name="category" class="form-select">
                                <option value="zakat_calculation" {{ old('category') === 'zakat_calculation' ? 'selected' : '' }}>Zakat Calculation Dispute (যাকাত হিসাব সংক্রান্ত)</option>
                                <option value="distribution_delay" {{ old('category') === 'distribution_delay' ? 'selected' : '' }}>Distribution Delay (সাহায্য বিতরণে বিলম্ব)</option>
                                <option value="agent_conduct" {{ old('category') === 'agent_conduct' ? 'selected' : '' }}>Agent Conduct / Behavior (মাঠ প্রতিনিধির আচরণ)</option>
                                <option value="technical_issue" {{ old('category') === 'technical_issue' ? 'selected' : '' }}>Technical Issue / Portal Bug (প্রযুক্তিগত সমস্যা)</option>
                                <option value="shariah_inquiry" {{ old('category') === 'shariah_inquiry' ? 'selected' : '' }}>Shariah Compliance Inquiry (শরীয়াহ্ বিষয়ক জিজ্ঞাসা)</option>
                                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Other (অন্যান্য)</option>
                            </select>
                        </div>

                        <!-- Severity -->
                        <div class="col-md-6">
                            <label class="form-label">Severity Level</label>
                            <select name="severity" class="form-select">
                                <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>Low (সাধারণ)</option>
                                <option value="medium" {{ old('severity') === 'medium' ? 'selected' : '' }} selected>Medium (মধ্যম)</option>
                                <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }}>High (উচ্চতর)</option>
                                <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>Critical / Urgent (জরুরি)</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label class="form-label">Detailed Description <span class="text-danger">*</span></label>
                            <textarea name="description" rows="5" class="form-control" placeholder="Please elaborate the issue in detail. State any facts, dates, or relevant transaction receipt numbers..." required>{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                        <a href="{{ route('complaints.index') }}" class="btn btn-czm-outline">Cancel</a>
                        <button type="submit" class="btn btn-czm-primary"><i class="bi bi-check-circle me-1"></i>Submit Complaint</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
