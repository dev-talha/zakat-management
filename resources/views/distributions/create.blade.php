@extends('layouts.app')
@section('title', 'Create Distribution')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('distributions.index') }}">Distributions</a></li>
    <li class="breadcrumb-item active">New</li>
@endsection
@section('content')
<div class="page-header">
    <div><h2>Disburse Zakat (যাকাত প্রদান)</h2></div>
</div>

<div class="glass-card" style="max-width:800px;">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('distributions.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Select Beneficiary (সুবিধাভোগী নির্বাচন করুন) *</label>
                    <select name="beneficiary_id" class="form-select form-select-lg" required>
                        <option value="">Choose...</option>
                        @foreach($beneficiaries as $b)
                            <option value="{{ $b->id }}">{{ $b->application_no }} - {{ $b->primary_person_name }} ({{ $b->zakat_category_label }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Source Fund (তহবিল) *</label>
                    <select name="fund_id" class="form-select form-select-lg" required>
                        <option value="">Choose...</option>
                        @foreach($funds as $f)
                            <option value="{{ $f->id }}">{{ $f->name }} (Bal: ৳{{ number_format($f->balance) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Approved Amount ৳ (অনুমোদিত পরিমাণ) *</label>
                    <input type="number" name="approved_amount" class="form-control form-control-lg" required min="1">
                </div>

                <h6 class="mt-4 border-bottom pb-2 text-primary"><i class="bi bi-wallet2 me-2"></i>Payout Channel (টাকা পাঠানোর মাধ্যম)</h6>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Select Channel *</label>
                    <select name="distribution_type" class="form-select form-select-lg" required>
                        <option value="bkash">bKash (বিকাশ)</option>
                        <option value="nagad">Nagad (নগদ)</option>
                        <option value="rocket">Rocket (রকেট)</option>
                        <option value="bank_transfer">Bank Transfer (ব্যাংক ট্রান্সফার)</option>
                        <option value="cash">Cash via Agent (নগদ)</option>
                        <option value="kind">In-Kind (সামগ্রী)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Account / Reference No. *</label>
                    <input type="text" name="category_code" class="form-control form-control-lg" placeholder="01XXXXXXXXX" required>
                    <div class="form-text">For bKash/Nagad, enter mobile number.</div>
                </div>

                <div class="col-12 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-czm-primary fs-5 py-2 px-4 shadow-sm">
                        <i class="bi bi-send-check me-2"></i>Process Disbursement
                    </button>
                    <a href="{{ route('distributions.index') }}" class="btn btn-czm-outline ms-2 py-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
