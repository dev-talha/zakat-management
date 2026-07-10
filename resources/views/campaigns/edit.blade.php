@extends('layouts.app')
@section('title', 'Edit Campaign')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('campaigns.index') }}">Campaigns</a></li>
    <li class="breadcrumb-item"><a href="{{ route('campaigns.show', $campaign) }}">{{ $campaign->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Edit Campaign</h2>
        <p class="page-subtitle">ক্যাম্পেইন ও তহবিল সংগ্রহ কার্যক্রম সংশোধন করুন</p>
    </div>
    <a href="{{ route('campaigns.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back to List</a>
</div>

<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="glass-card">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Campaign details: {{ $campaign->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('campaigns.update', $campaign) }}" method="POST">
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
                        <!-- Campaign Title EN -->
                        <div class="col-md-6">
                            <label class="form-label">Campaign Title (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $campaign->name) }}" required>
                        </div>

                        <!-- Campaign Title BN -->
                        <div class="col-md-6">
                            <label class="form-label">Campaign Title (Bangla)</label>
                            <input type="text" name="name_bn" class="form-control" value="{{ old('name_bn', $campaign->name_bn) }}">
                        </div>

                        <!-- Fund Type -->
                        <div class="col-md-4">
                            <label class="form-label">Fund Category <span class="text-danger">*</span></label>
                            <select name="fund_type" class="form-select" required>
                                <option value="zakat" {{ old('fund_type', $campaign->fund_type) === 'zakat' ? 'selected' : '' }}>Zakat (যាកাত)</option>
                                <option value="sadaqah" {{ old('fund_type', $campaign->fund_type) === 'sadaqah' ? 'selected' : '' }}>Sadaqah (সাদাকাহ)</option>
                                <option value="fitrah" {{ old('fund_type', $campaign->fund_type) === 'fitrah' ? 'selected' : '' }}>Fitrah (ফিতরা)</option>
                                <option value="waqf" {{ old('fund_type', $campaign->fund_type) === 'waqf' ? 'selected' : '' }}>Waqf (ওয়াকফ)</option>
                                <option value="emergency" {{ old('fund_type', $campaign->fund_type) === 'emergency' ? 'selected' : '' }}>Emergency Relief (জরুরি সাহায্য)</option>
                                <option value="general" {{ old('fund_type', $campaign->fund_type) === 'general' ? 'selected' : '' }}>General Fund (সাধারণ তহবিল)</option>
                            </select>
                        </div>

                        <!-- Target Amount -->
                        <div class="col-md-4">
                            <label class="form-label">Target Amount (BDT) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-tertiary border-secondary text-white">৳</span>
                                <input type="number" step="0.01" min="0" name="target_amount" class="form-control" value="{{ old('target_amount', $campaign->target_amount) }}" required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <label class="form-label">Campaign Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="draft" {{ old('status', $campaign->status) === 'draft' ? 'selected' : '' }}>Draft (খসড়া)</option>
                                <option value="active" {{ old('status', $campaign->status) === 'active' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                                <option value="paused" {{ old('status', $campaign->status) === 'paused' ? 'selected' : '' }}>Paused (সাময়িক বন্ধ)</option>
                                <option value="completed" {{ old('status', $campaign->status) === 'completed' ? 'selected' : '' }}>Completed (সম্পন্ন)</option>
                                <option value="cancelled" {{ old('status', $campaign->status) === 'cancelled' ? 'selected' : '' }}>Cancelled (বাতিল)</option>
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at', $campaign->starts_at ? \Carbon\Carbon::parse($campaign->starts_at)->format('Y-m-d') : '') }}">
                        </div>

                        <!-- End Date -->
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="ends_at" class="form-control" value="{{ old('ends_at', $campaign->ends_at ? \Carbon\Carbon::parse($campaign->ends_at)->format('Y-m-d') : '') }}">
                        </div>

                        <!-- Description EN -->
                        <div class="col-12">
                            <label class="form-label">Description (English)</label>
                            <textarea name="description" rows="4" class="form-control">{{ old('description', $campaign->description) }}</textarea>
                        </div>

                        <!-- Description BN -->
                        <div class="col-12">
                            <label class="form-label">Description (Bangla)</label>
                            <textarea name="description_bn" rows="4" class="form-control">{{ old('description_bn', $campaign->description_bn) }}</textarea>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                        <a href="{{ route('campaigns.index') }}" class="btn btn-czm-outline">Cancel</a>
                        <button type="submit" class="btn btn-czm-primary"><i class="bi bi-save me-1"></i>Update Campaign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
