@extends('layouts.app')
@section('title', 'New Application')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('beneficiaries.index') }}">Beneficiaries</a></li><li class="breadcrumb-item active">New</li>@endsection
@section('content')
<div class="page-header"><div><h2>Beneficiary Application</h2><p class="page-subtitle">সুবিধাভোগী আবেদন</p></div></div>
<div class="glass-card" style="max-width:800px;">
    <div class="card-body">
        @if($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
        <form method="POST" action="{{ route('beneficiaries.store') }}">
            @csrf
            <h6 class="mb-3"><i class="bi bi-person me-2"></i>Personal Information</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6"><label class="form-label">Full Name (English) *</label><input type="text" name="primary_person_name" class="form-control" required value="{{ old('primary_person_name') }}"></div>
                <div class="col-md-6"><label class="form-label">Full Name (বাংলা)</label><input type="text" name="primary_person_name_bn" class="form-control" value="{{ old('primary_person_name_bn') }}"></div>
                <div class="col-md-4"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="">Select</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
                <div class="col-md-4"><label class="form-label">Date of Birth</label><input type="date" name="dob" class="form-control" value="{{ old('dob') }}"></div>
                <div class="col-md-4"><label class="form-label">Mobile</label><input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="01XXXXXXXXX"></div>
                <div class="col-md-4"><label class="form-label">ID Type</label><select name="identity_type" class="form-select"><option value="nid">NID</option><option value="birth_cert">Birth Cert</option><option value="none">None</option></select></div>
                <div class="col-md-4"><label class="form-label">ID Number</label><input type="text" name="identity_no" class="form-control" value="{{ old('identity_no') }}"></div>
                <div class="col-md-4"><label class="form-label">Monthly Income (৳)</label><input type="number" name="monthly_income" class="form-control" value="{{ old('monthly_income', 0) }}"></div>
            </div>
            <h6 class="mb-3"><i class="bi bi-house me-2"></i>Household & Location</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4"><label class="form-label">Division</label><input type="text" name="division" class="form-control" value="{{ old('division') }}"></div>
                <div class="col-md-4"><label class="form-label">District *</label><input type="text" name="district" class="form-control" required value="{{ old('district') }}"></div>
                <div class="col-md-4"><label class="form-label">Upazila</label><input type="text" name="upazila" class="form-control" value="{{ old('upazila') }}"></div>
                <div class="col-md-4"><label class="form-label">Ward</label><input type="text" name="ward" class="form-control" value="{{ old('ward') }}"></div>
                <div class="col-md-4"><label class="form-label">Housing Type</label><select name="housing_type" class="form-select"><option value="own">Own</option><option value="rented">Rented</option><option value="government">Government</option><option value="shelter">Shelter</option><option value="homeless">Homeless</option></select></div>
                <div class="col-md-12"><label class="form-label">Full Address</label><textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea></div>
            </div>
            <h6 class="mb-3"><i class="bi bi-tags me-2"></i>Zakat Category</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6"><label class="form-label">Category</label>
                    <select name="zakat_category" class="form-select">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\Beneficiary::ZAKAT_CATEGORIES as $key => $cat)
                        <option value="{{ $key }}" {{ old('zakat_category') == $key ? 'selected' : '' }}>{{ $cat['en'] }} ({{ $cat['bn'] }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-czm-primary"><i class="bi bi-check-circle me-1"></i>Submit Application</button>
            <a href="{{ route('beneficiaries.index') }}" class="btn btn-czm-outline ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
