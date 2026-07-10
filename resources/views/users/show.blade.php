@extends('layouts.app')
@section('title', $user->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($user->name, 25) }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $user->name }}</h2>
        <p class="page-subtitle">প্রশাসনিক কর্মকর্তার প্রোফাইল বিবরণ</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('users.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <a href="{{ route('users.edit', $user) }}" class="btn btn-czm-primary"><i class="bi bi-pencil me-1"></i>Edit Profile</a>
    </div>
</div>

<div class="row g-4">
    <!-- Left column: Profile Summary Card -->
    <div class="col-lg-5">
        <div class="glass-card">
            <div class="card-header text-center py-4 border-bottom border-secondary">
                <div class="avatar-icon bg-secondary text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:90px; height:90px; font-size:3rem; border: 3px solid var(--czm-primary);">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h4 class="text-white mb-1">{{ $user->name }}</h4>
                <p class="text-muted small mb-3">{{ $user->email }}</p>
                <span class="badge-status {{ $user->status }}">{{ ucfirst($user->status) }}</span>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush bg-transparent border-0">
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>User Type:</span>
                        <span class="text-white text-capitalize fw-semibold">{{ $user->user_type }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Mobile Contact:</span>
                        <span class="text-white fw-semibold">{{ $user->mobile ?: 'Not Specified' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Language Locale:</span>
                        <span class="text-white text-uppercase">{{ $user->locale ?: 'bn' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Account Created:</span>
                        <span class="text-white">{{ $user->created_at->format('F d, Y') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right column: Role and Access Logs Card -->
    <div class="col-lg-7">
        <!-- Security Roles -->
        <div class="glass-card mb-4">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-shield-lock me-2 text-primary"></i>Assigned Security Roles</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @forelse($user->roles as $role)
                        <span class="badge bg-primary text-uppercase px-3 py-2 fs-6">{{ $role->name }}</span>
                    @empty
                        <span class="text-muted">No security roles are currently assigned to this account.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Login and connection parameters -->
        <div class="glass-card">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Access Logs & Metadata</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush bg-transparent border-0">
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Last Login Time:</span>
                        <span class="text-white">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('Y-m-d H:i') : 'No records' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Last Known IP:</span>
                        <span class="text-white font-monospace">{{ $user->last_login_ip ?: 'Not recorded' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
