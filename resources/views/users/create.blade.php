@extends('layouts.app')
@section('title', 'Add Staff Member')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Add Staff Member</h2>
        <p class="page-subtitle">নতুন প্রশাসনিক কর্মকর্তা যুক্ত করুন</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back to Users</a>
</div>

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="glass-card">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-person-plus me-2 text-primary"></i>Staff Registration Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
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
                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Mohammad Rahman" value="{{ old('name') }}" required>
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="e.g., rahman@czm.bd" value="{{ old('email') }}" required>
                        </div>

                        <!-- Mobile Number -->
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile" class="form-control" placeholder="e.g., +8801700000000" value="{{ old('mobile') }}">
                        </div>

                        <!-- Assigned Role -->
                        <div class="col-md-6">
                            <label class="form-label">Assigned Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="" disabled selected>-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Password -->
                        <div class="col-md-12">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password (defaults to 'password' if left blank)">
                            <div class="form-text text-muted">ডিফল্ট পাসওয়ার্ড হবে <b>password</b>। পরবর্তীতে পরিবর্তন করা যাবে।</div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                        <a href="{{ route('users.index') }}" class="btn btn-czm-outline">Cancel</a>
                        <button type="submit" class="btn btn-czm-primary"><i class="bi bi-person-check me-1"></i>Register User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
