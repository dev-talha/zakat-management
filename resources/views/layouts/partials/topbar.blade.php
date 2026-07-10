<header class="topbar">
    @if(session()->has('impersonated_by'))
    <div style="width:100%; background-color:#ffc107; color:#000; text-align:center; padding:5px; font-weight:bold; position:absolute; top:0; left:0; z-index:1000;">
        You are currently impersonating {{ auth()->user()->name }}. 
        <form action="{{ route('impersonate.leave') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-dark ms-2">Leave Impersonation</button>
        </form>
    </div>
    @endif
    <div class="topbar-left" @if(session()->has('impersonated_by')) style="margin-top:40px;" @endif>
        <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
    </div>
    <div class="topbar-right">
        <div class="topbar-item">
            <button class="theme-toggle" id="themeToggle" title="Toggle theme">
                <i class="bi bi-sun-fill"></i>
            </button>
        </div>
        <div class="topbar-item">
            <button class="locale-toggle" id="localeToggle" title="Toggle language">
                <span class="locale-badge">{{ app()->getLocale() === 'bn' ? 'EN' : 'বাং' }}</span>
            </button>
        </div>
        <div class="topbar-item dropdown">
            <button class="notification-btn dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-bell-fill"></i>
                <span class="notification-badge">3</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                <h6 class="dropdown-header">Notifications</h6>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-check2-circle text-success"></i>
                    <div><strong>Payment received</strong><small class="text-muted d-block">CZM-2026-00000001 - ৳5,000</small></div>
                </a>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-person-plus text-info"></i>
                    <div><strong>New application</strong><small class="text-muted d-block">BEN-2026-000001 submitted</small></div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center small" href="#">View all notifications</a>
            </div>
        </div>
        <div class="topbar-item dropdown">
            <button class="user-dropdown-btn dropdown-toggle" data-bs-toggle="dropdown">
                <div class="user-avatar-sm">
                    <i class="bi bi-person-circle"></i>
                </div>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <h6 class="dropdown-header">{{ auth()->user()->name }}</h6>
                <a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a>
                <a class="dropdown-item" href="#"><i class="bi bi-key me-2"></i>Change Password</a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
