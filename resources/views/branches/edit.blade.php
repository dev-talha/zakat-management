@extends('layouts.app')
@section('title', 'Edit Branch')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('branches.index') }}">Branches</a></li>
    <li class="breadcrumb-item"><a href="{{ route('branches.show', $branch) }}">{{ $branch->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Regional Branch</h2>
        <p class="page-subtitle">শাখা কার্যালয় তথ্য সংশোধন করুন</p>
    </div>
    <a href="{{ route('branches.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back to Branches</a>
</div>

<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="glass-card">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Branch details: {{ $branch->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('branches.update', $branch) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                            <input type="text" name="code" class="form-control" value="{{ old('code', $branch->code) }}" required>
                        </div>

                        <!-- Branch Name EN -->
                        <div class="col-md-4">
                            <label class="form-label">Branch Name (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $branch->name) }}" required>
                        </div>

                        <!-- Branch Name BN -->
                        <div class="col-md-4">
                            <label class="form-label">Branch Name (Bangla)</label>
                            <input type="text" name="name_bn" class="form-control" value="{{ old('name_bn', $branch->name_bn) }}">
                        </div>

                        <!-- Region -->
                        <div class="col-md-3">
                            <label class="form-label">Region (অঞ্চল)</label>
                            <input type="text" name="region" class="form-control" value="{{ old('region', $branch->region) }}">
                        </div>

                        <!-- Division -->
                        <div class="col-md-3">
                            <label class="form-label">Division (বিভাগ)</label>
                            <input type="text" name="division" class="form-control" value="{{ old('division', $branch->division) }}">
                        </div>

                        <!-- District -->
                        <div class="col-md-3">
                            <label class="form-label">District (জেলা)</label>
                            <input type="text" name="district" class="form-control" value="{{ old('district', $branch->district) }}">
                        </div>

                        <!-- Upazila -->
                        <div class="col-md-3">
                            <label class="form-label">Upazila (উপজেলা)</label>
                            <input type="text" name="upazila" class="form-control" value="{{ old('upazila', $branch->upazila) }}">
                        </div>

                        <!-- Phone -->
                        <div class="col-md-4">
                            <label class="form-label">Phone Contact</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $branch->phone) }}">
                        </div>

                        <!-- Email -->
                        <div class="col-md-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $branch->email) }}">
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <label class="form-label">Branch Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status', $branch->status) === 'active' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                                <option value="inactive" {{ old('status', $branch->status) === 'inactive' ? 'selected' : '' }}>Inactive (অসক্রিয়)</option>
                                <option value="suspended" {{ old('status', $branch->status) === 'suspended' ? 'selected' : '' }}>Suspended (সাময়িক স্থগিত)</option>
                            </select>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <label class="form-label">Physical Address</label>
                            <textarea name="address" rows="3" class="form-control">{{ old('address', $branch->address) }}</textarea>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                        <a href="{{ route('branches.index') }}" class="btn btn-czm-outline">Cancel</a>
                        <button type="submit" class="btn btn-czm-primary"><i class="bi bi-save me-1"></i>Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
