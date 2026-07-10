<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-moon-stars-fill"></i>
            </div>
            <div class="brand-text">
                <h1 class="brand-name">CZM</h1>
                <small class="brand-tagline">যাকাত ব্যবস্থাপনা</small>
            </div>
        </div>
        <button class="sidebar-toggle d-lg-none" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-section">প্রধান / Main</li>

            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @canany(['donors.view', 'donors.create'])
            <li class="nav-section">দাতা / Donors</li>
            <li class="nav-item">
                <a href="{{ route('donors.index') }}" class="nav-link {{ request()->routeIs('donors.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Donor Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('campaigns.index') }}" class="nav-link {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone-fill"></i>
                    <span>Campaigns</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('collections.index') }}" class="nav-link {{ request()->routeIs('collections.*') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack"></i>
                    <span>Collections</span>
                </a>
            </li>
            @endcanany

            @canany(['beneficiaries.view', 'beneficiaries.create'])
            <li class="nav-section">সুবিধাভোগী / Beneficiaries</li>
            <li class="nav-item">
                <a href="{{ route('beneficiaries.index') }}" class="nav-link {{ request()->routeIs('beneficiaries.*') ? 'active' : '' }}">
                    <i class="bi bi-person-hearts"></i>
                    <span>Beneficiaries</span>
                </a>
            </li>
            @endcanany

            @canany(['cases.view', 'cases.create'])
            <li class="nav-item">
                <a href="{{ route('cases.index') }}" class="nav-link {{ request()->routeIs('cases.*') ? 'active' : '' }}">
                    <i class="bi bi-folder2-open"></i>
                    <span>Case Management</span>
                </a>
            </li>
            @endcanany

            @canany(['funds.view', 'distributions.view'])
            <li class="nav-section">তহবিল / Finance</li>
            <li class="nav-item">
                <a href="{{ route('funds.index') }}" class="nav-link {{ request()->routeIs('funds.*') ? 'active' : '' }}">
                    <i class="bi bi-safe2-fill"></i>
                    <span>Fund Ledger</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('distributions.index') }}" class="nav-link {{ request()->routeIs('distributions.*') ? 'active' : '' }}">
                    <i class="bi bi-send-check-fill"></i>
                    <span>Distributions</span>
                </a>
            </li>
            @endcanany

            @canany(['reports.view'])
            <li class="nav-section">রিপোর্ট / Reports</li>
            <li class="nav-item">
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Reports & Analytics</span>
                </a>
            </li>
            @endcanany

            {{-- Assuming super admins or specific roles can manage partners, we use a generic check or add it for all admins for now --}}
            <li class="nav-section">অংশীদার / Partners</li>
            <li class="nav-item">
                <a href="{{ route('admin.organizations.index') }}" class="nav-link {{ request()->routeIs('admin.organizations.*') ? 'active' : '' }}">
                    <i class="bi bi-building-fill"></i>
                    <span>Organizations</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.volunteers.index') }}" class="nav-link {{ request()->routeIs('admin.volunteers.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Volunteers</span>
                </a>
            </li>

            @canany(['users.view', 'settings.view', 'branches.view'])
            <li class="nav-section">প্রশাসন / Admin</li>
            @can('users.view')
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span>User Management</span>
                </a>
            </li>
            @endcan
            @can('branches.view')
            <li class="nav-item">
                <a href="{{ route('branches.index') }}" class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    <span>Branches</span>
                </a>
            </li>
            @endcan
            @can('settings.view')
            <li class="nav-item">
                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i>
                    <span>Settings</span>
                </a>
            </li>
            @endcan
            @can('audit.view')
            <li class="nav-item">
                <a href="{{ route('audit.index') }}" class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i>
                    <span>Audit Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('blockchain.ledger') }}" class="nav-link {{ request()->routeIs('blockchain.*') ? 'active' : '' }}">
                    <i class="bi bi-link-45deg"></i>
                    <span>Blockchain Ledger</span>
                </a>
            </li>
            @endcan
            @endcanany

            @canany(['complaints.view'])
            <li class="nav-item">
                <a href="{{ route('complaints.index') }}" class="nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-square-text-fill"></i>
                    <span>Complaints</span>
                </a>
            </li>
            @endcanany
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="user-mini">
            <div class="user-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="user-role">{{ auth()->user()->roles->first()?->name ?? 'User' }}</span>
            </div>
        </div>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
