@extends('layouts.app')
@section('title', 'Create Campaign')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('campaigns.index') }}">Campaigns</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Create Campaign</h2>
        <p class="page-subtitle">নতুন যাকাত ও সাধারণ সংগ্রহ ক্যাম্পেইন যুক্ত করুন</p>
    </div>
    <a href="{{ route('campaigns.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back to List</a>
</div>

<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="glass-card">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Campaign Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('campaigns.store') }}" method="POST">
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
                        <!-- Campaign Title EN -->
                        <div class="col-md-6">
                            <label class="form-label">Campaign Title (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Winter Relief Zakat 2026" value="{{ old('name') }}" required>
                        </div>

                        <!-- Campaign Title BN -->
                        <div class="col-md-6">
                            <label class="form-label">Campaign Title (Bangla)</label>
                            <input type="text" name="name_bn" class="form-control" placeholder="যেমন: শীতকালীন যাকাত সহায়তা ২০২৬" value="{{ old('name_bn') }}">
                        </div>

                        <!-- Fund Type -->
                        <div class="col-md-6">
                            <label class="form-label">Fund Category <span class="text-danger">*</span></label>
                            <select name="fund_type" class="form-select" required>
                                <option value="zakat" {{ old('fund_type') === 'zakat' ? 'selected' : '' }}>Zakat (যাকাত)</option>
                                <option value="sadaqah" {{ old('fund_type') === 'sadaqah' ? 'selected' : '' }}>Sadaqah (সাদাকাহ)</option>
                                <option value="fitrah" {{ old('fund_type') === 'fitrah' ? 'selected' : '' }}>Fitrah (ফিতরা)</option>
                                <option value="waqf" {{ old('fund_type') === 'waqf' ? 'selected' : '' }}>Waqf (ওয়াকফ)</option>
                                <option value="emergency" {{ old('fund_type') === 'emergency' ? 'selected' : '' }}>Emergency Relief (জরুরি সাহায্য)</option>
                                <option value="general" {{ old('fund_type') === 'general' ? 'selected' : '' }}>General Fund (সাধারণ তহবিল)</option>
                            </select>
                        </div>

                        <!-- Target Amount -->
                        <div class="col-md-6">
                            <label class="form-label">Target Amount (BDT) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-tertiary border-secondary text-white">৳</span>
                                <input type="number" step="0.01" min="0" name="target_amount" class="form-control" placeholder="e.g., 500000" value="{{ old('target_amount', 0) }}" required>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                        </div>

                        <!-- End Date -->
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
                        </div>

                        <!-- Description EN -->
                        <div class="col-12">
                            <label class="form-label">Description (English)</label>
                            <textarea name="description" rows="4" class="form-control" placeholder="Explain the focus, objectives, and impact of this campaign...">{{ old('description') }}</textarea>
                        </div>

                        <!-- Description BN -->
                        <div class="col-12">
                            <label class="form-label">Description (Bangla)</label>
                            <textarea name="description_bn" rows="4" class="form-control" placeholder="ক্যাম্পেইনের লক্ষ্য, প্রয়োজনীয়তা ও যৌক্তিকতা বর্ণনা করুন...">{{ old('description_bn') }}</textarea>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                        <a href="{{ route('campaigns.index') }}" class="btn btn-czm-outline">Cancel</a>
                        <button type="submit" class="btn btn-czm-primary"><i class="bi bi-check-circle me-1"></i>Launch Campaign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
