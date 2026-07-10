@extends('layouts.app')

@section('title', 'ভলান্টিয়ার ব্যবস্থাপনা | Volunteer Management')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1"><i class="bi bi-people-fill me-2 text-primary"></i>ভলান্টিয়ার ব্যবস্থাপনা (Volunteer Management)</h2>
            <p class="text-muted mb-0">অংশীদার সংগঠনের স্বেচ্ছাসেবকদের পরিচালনা করুন।</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.volunteers.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, Code, Phone..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-czm-primary w-100"><i class="bi bi-search me-2"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="glass-card">
        <div class="table-responsive">
            <table class="czm-table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="py-3 ps-4">Volunteer Details</th>
                        <th class="py-3">Sponsoring Org</th>
                        <th class="py-3">Location & Role</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($volunteers as $vol)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $vol->name_en }}</div>
                            <div class="text-primary small mt-1"><i class="bi bi-envelope"></i> {{ $vol->email }} | <i class="bi bi-telephone"></i> {{ $vol->mobile }}</div>
                            <div class="text-muted small mt-1">Ref Code: <span class="badge bg-secondary">{{ $vol->referral_code ?? 'N/A' }}</span></div>
                        </td>
                        <td>
                            @if($vol->organization)
                            <div class="fw-bold">{{ $vol->organization->name_en }}</div>
                            <div class="small text-muted">{{ $vol->organization->org_code }}</div>
                            @else
                            <span class="text-muted">Independent</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $vol->district }}</div>
                            <div class="small"><span class="badge bg-info text-dark">{{ ucfirst($vol->coverage_level) }} Level</span></div>
                        </td>
                        <td class="text-center">
                            @if($vol->status === 'active')
                                <span class="badge-status active">Active</span>
                            @elseif($vol->status === 'pending')
                                <span class="badge-status pending">Pending</span>
                            @elseif($vol->status === 'suspended')
                                <span class="badge-status rejected">Suspended</span>
                            @else
                                <span class="badge-status draft">{{ ucfirst($vol->status) }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.volunteers.show', $vol->id) }}" class="btn btn-sm btn-info text-white" title="View Details"><i class="bi bi-eye"></i></a>
                                @if($vol->status === 'pending')
                                <form action="{{ route('admin.volunteers.status', $vol->id) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check-circle"></i></button>
                                </form>
                                @endif
                                @if($vol->status === 'active')
                                <form action="{{ route('admin.volunteers.status', $vol->id) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <input type="hidden" name="status" value="suspended">
                                    <button type="submit" class="btn btn-sm btn-warning" title="Suspend"><i class="bi bi-pause-circle"></i></button>
                                </form>
                                @endif
                                @if($vol->status === 'suspended')
                                <form action="{{ route('admin.volunteers.status', $vol->id) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="btn btn-sm btn-success" title="Reactivate"><i class="bi bi-play-circle"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No volunteers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($volunteers->hasPages())
        <div class="card-footer pt-3 pb-2 px-4">
            {{ $volunteers->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
