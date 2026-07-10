@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="brand-icon"><i class="bi bi-key-fill"></i></div>
            <h2>Reset Password</h2>
            <p>পাসওয়ার্ড পুনরুদ্ধার করুন</p>
        </div>
        @if(session('status'))
            <div class="alert alert-success" style="border-radius:12px;font-size:0.85rem;">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <button type="submit" class="btn btn-czm-primary w-100 py-2">
                <i class="bi bi-send me-2"></i>Send Reset Link
            </button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small"><i class="bi bi-arrow-left me-1"></i>Back to login</a>
        </div>
    </div>
</div>
@endsection
