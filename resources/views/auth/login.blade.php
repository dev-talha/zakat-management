@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="auth-wrapper py-5" style="overflow-y: auto; align-items: flex-start;">
    <div class="container my-auto">
        <div class="row justify-content-center align-items-stretch g-4">
            
            {{-- Left: Login Card --}}
            <div class="col-lg-5 col-md-6 d-flex">
                <div class="auth-card w-100 m-0">
                    <div class="auth-logo">
                        <div class="brand-icon">
                            <i class="bi bi-moon-stars-fill"></i>
                        </div>
                        <h2>Central Zakat Management</h2>
                        <p>কেন্দ্রীয় যাকাত ব্যবস্থাপনা প্ল্যাটফর্ম</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger mb-3" style="border-radius: 12px; font-size: 0.85rem;">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--czm-bg-tertiary);border-color:var(--czm-border);color:var(--czm-text-muted);">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" id="loginEmail" name="email" class="form-control" placeholder="admin@czm.bd"
                                       value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--czm-bg-tertiary);border-color:var(--czm-border);color:var(--czm-text-muted);">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" id="loginPassword" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small" for="remember">Remember me</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn btn-czm-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </button>
                    </form>
                </div>
            </div>

            @if(\App\Models\Setting::getValue('general', 'show_quick_test_accounts', '1') == '1')
            {{-- Right: Quick Credentials Card --}}
            <div class="col-lg-5 col-md-6 d-flex">
                <div class="auth-card w-100 m-0 d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="mb-3" style="font-weight: 800; color: var(--czm-primary);"><i class="bi bi-shield-lock-fill me-2"></i>Quick-Test Accounts</h4>
                        <p class="text-muted small">Click any of the roles below to automatically fill the login form and enter their custom dashboard view instantly.</p>
                        
                        <div class="d-flex flex-column gap-2 mt-4">
                            @foreach([
                                ['super_admin', 'admin@czm.bd', 'Super Administrator (Admin)', '👔'],
                                ['zakat_officer', 'officer@czm.bd', 'Zakat Officer (Staff)', '📋'],
                                ['donor', 'donor@czm.bd', 'Zakat Donor (Donor Portal)', '💝'],
                                ['organization', 'org@czm.bd', 'Partner Organization Admin', '🏢'],
                                ['volunteer', 'volunteer@czm.bd', 'Verification Volunteer', '🙋‍♂️'],
                                ['beneficiary', 'beneficiary@czm.bd', 'Zakat Beneficiary (Dashboard)', '🤲']
                            ] as $cred)
                            <button type="button" class="btn btn-czm-outline text-start d-flex align-items-center gap-3 py-2 w-100" onclick="quickFill('{{ $cred[1] }}', 'password')">
                                <span style="font-size: 1.4rem;">{{ $cred[3] }}</span>
                                <div style="flex-grow:1;">
                                    <div class="fw-bold" style="font-size:0.875rem; color:var(--czm-text-primary);">{{ $cred[2] }}</div>
                                    <div style="font-size:0.75rem; color:var(--czm-text-muted);">Email: {{ $cred[1] }} | Pass: password</div>
                                </div>
                                <i class="bi bi-chevron-right text-muted" style="font-size:0.8rem;"></i>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <div style="margin-top:20px; font-size:0.75rem; color:var(--czm-text-muted);" class="text-center">
                        <i class="bi bi-info-circle me-1"></i> Default password for all test accounts is <code>password</code>.
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<script>
function quickFill(email, password) {
    document.getElementById('loginEmail').value = email;
    document.getElementById('loginPassword').value = password;
    
    // Add brief animation focus
    const btn = document.querySelector('button[type="submit"]');
    btn.classList.add('btn-pulse');
    setTimeout(() => btn.classList.remove('btn-pulse'), 1000);
}
</script>

<style>
@keyframes btnPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.03); box-shadow: 0 0 0 10px rgba(var(--czm-primary-rgb), 0.2); }
    100% { transform: scale(1); }
}
.btn-pulse {
    animation: btnPulse 0.6s ease;
}
</style>
@endsection
