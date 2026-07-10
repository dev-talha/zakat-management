@extends('layouts.app')
@section('title', $branch->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('branches.index') }}">Branches</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($branch->name, 25) }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $branch->name }} Details</h2>
        <p class="page-subtitle">শাখা কার্যালয় পরিচিতি ও বিবরণ</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('branches.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <a href="{{ route('branches.edit', $branch) }}" class="btn btn-czm-primary"><i class="bi bi-pencil me-1"></i>Edit Branch</a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Details -->
    <div class="col-lg-6">
        <div class="glass-card h-100">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Branch Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4 pb-3 border-bottom border-secondary">
                    <div class="avatar-icon bg-secondary text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:70px; height:70px; font-size:2.2rem; border: 2px solid var(--czm-primary);">
                        <i class="bi bi-building-fill"></i>
                    </div>
                    <h4 class="text-white mb-1">{{ $branch->name }}</h4>
                    <span class="text-muted d-block small mb-3">Branch Code: <b>{{ $branch->code }}</b></span>
                    <span class="badge-status {{ $branch->status === 'active' ? 'active' : 'inactive' }}">{{ ucfirst($branch->status) }}</span>
                </div>

                <ul class="list-group list-group-flush bg-transparent border-0">
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Region Area:</span>
                        <span class="text-white fw-semibold">{{ $branch->region ?: 'National Hub' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Division:</span>
                        <span class="text-white text-capitalize">{{ $branch->division ?: 'N/A' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>District:</span>
                        <span class="text-white text-capitalize">{{ $branch->district ?: 'N/A' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Upazila:</span>
                        <span class="text-white text-capitalize">{{ $branch->upazila ?: 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Column: Contact & Scope -->
    <div class="col-lg-6">
        <div class="glass-card h-100">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-geo-alt me-2 text-primary"></i>Contact & Address</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="text-muted d-block small font-monospace text-uppercase">Full Address</label>
                    <p class="text-white fw-semibold" style="font-size:0.95rem;">{{ $branch->address ?: 'No address registered for this branch.' }}</p>
                </div>

                <ul class="list-group list-group-flush bg-transparent border-0 mb-4">
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Phone No:</span>
                        <span class="text-white fw-semibold">{{ $branch->phone ?: 'Not Specified' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Email:</span>
                        <span class="text-white font-monospace">{{ $branch->email ?: 'Not Specified' }}</span>
                    </li>
                </ul>

                @if($branch->geo_lat && $branch->geo_lng)
                <div class="glass-card p-3 border-secondary">
                    <h6 class="text-white small mb-2"><i class="bi bi-compass me-1"></i>Geo-Location Coordinates</h6>
                    <small class="text-muted d-block">Latitude: <b>{{ $branch->geo_lat }}</b></small>
                    <small class="text-muted d-block">Longitude: <b>{{ $branch->geo_lng }}</b></small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
