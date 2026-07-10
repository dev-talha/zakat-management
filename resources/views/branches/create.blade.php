@extends('layouts.app')
@section('title', 'Add Branch')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('branches.index') }}">Branches</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Add Regional Branch</h2>
        <p class="page-subtitle">নতুন আঞ্চলিক বা বিভাগীয় কার্যালয় যোগ করুন</p>
    </div>
    <a href="{{ route('branches.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back to Branches</a>
</div>

<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="glass-card">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-building-add me-2 text-primary"></i>Branch Information Form</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('branches.store') }}" method="POST">
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
                        <!-- Branch Code -->
                        <div class="col-md-4">
                            <label class="form-label">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="e.g., DHAKA-NORTH" value="{{ old('code') }}" required>
                        </div>

                        <!-- Branch Name EN -->
                        <div class="col-md-4">
                            <label class="form-label">Branch Name (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Dhaka North Branch" value="{{ old('name') }}" required>
                        </div>

                        <!-- Branch Name BN -->
                        <div class="col-md-4">
                            <label class="form-label">Branch Name (Bangla)</label>
                            <input type="text" name="name_bn" class="form-control" placeholder="যেমন: ঢাকা উত্তর শাখা" value="{{ old('name_bn') }}">
                        </div>

                        <!-- Region -->
                        <div class="col-md-3">
                            <label class="form-label">Region (অঞ্চল)</label>
                            <input type="text" name="region" class="form-control" placeholder="e.g., Central Division" value="{{ old('region') }}">
                        </div>

                        <!-- Division -->
                        <div class="col-md-3">
                            <label class="form-label">Division (বিভাগ)</label>
                            <input type="text" name="division" class="form-control" placeholder="e.g., Dhaka" value="{{ old('division') }}">
                        </div>

                        <!-- District -->
                        <div class="col-md-3">
                            <label class="form-label">District (জেলা)</label>
                            <input type="text" name="district" class="form-control" placeholder="e.g., Dhaka" value="{{ old('district') }}">
                        </div>

                        <!-- Upazila -->
                        <div class="col-md-3">
                            <label class="form-label">Upazila (উপজেলা)</label>
                            <input type="text" name="upazila" class="form-control" placeholder="e.g., Mirpur" value="{{ old('upazila') }}">
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label class="form-label">Phone Contact</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g., +88029999999" value="{{ old('phone') }}">
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g., dhaka.north@czm.bd" value="{{ old('email') }}">
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <label class="form-label">Physical Address</label>
                            <textarea name="address" rows="3" class="form-control" placeholder="Enter street address, building floor, etc...">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                        <a href="{{ route('branches.index') }}" class="btn btn-czm-outline">Cancel</a>
                        <button type="submit" class="btn btn-czm-primary"><i class="bi bi-check-circle me-1"></i>Create Branch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
