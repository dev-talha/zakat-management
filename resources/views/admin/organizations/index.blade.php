@extends('layouts.app')

@section('title', 'সংগঠন ব্যবস্থাপনা | Organization Management')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1"><i class="bi bi-building-fill me-2 text-primary"></i>সংগঠন ব্যবস্থাপনা (Partner Organizations)</h2>
            <p class="text-muted mb-0">অংশীদার সংগঠনসমূহ পরিচালনা ও অনুমোদন করুন।</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.organizations.index') }}" class="row g-3 align-items-end">
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
                        <th class="py-3 ps-4">Organization Details</th>
                        <th class="py-3">Location</th>
                        <th class="py-3">Referral Info</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizations as $org)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $org->name_en }}</div>
                            <div class="text-muted small">{{ $org->name_bn }}</div>
                            <div class="text-primary small mt-1"><i class="bi bi-envelope"></i> {{ $org->contact_email }} | <i class="bi bi-telephone"></i> {{ $org->contact_mobile }}</div>
                            <div class="text-muted small"><i class="bi bi-person"></i> {{ $org->contact_person_name }}</div>
                        </td>
                        <td>
                            <div>{{ $org->district }}</div>
                            <div class="small text-muted">{{ $org->division }}</div>
                        </td>
                        <td>
                            @if($org->referral_code)
                            <div><span class="badge bg-secondary">{{ $org->referral_code }}</span></div>
                            <div class="small mt-1 text-success">৳{{ number_format($org->total_collected_via_referral ?? 0) }} raised</div>
                            <div class="small text-muted">{{ $org->total_donors_via_referral ?? 0 }} donors</div>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($org->status === 'active')
                                <span class="badge-status active">Active</span>
                            @elseif($org->status === 'pending')
                                <span class="badge-status pending">Pending</span>
                            @elseif($org->status === 'suspended')
                                <span class="badge-status rejected">Suspended</span>
                            @else
                                <span class="badge-status draft">{{ ucfirst($org->status) }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.organizations.show', $org->id) }}" class="btn btn-sm btn-info text-white" title="View Details"><i class="bi bi-eye"></i></a>
                                
                                @if($org->created_by)
                                <form action="{{ route('admin.organizations.impersonate', $org->id) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary" title="Impersonate"><i class="bi bi-person-fill-gear"></i></button>
                                </form>
                                @endif

                                @if($org->status === 'pending')
                                <form action="{{ route('admin.organizations.status', $org->id) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check-circle"></i></button>
                                </form>
                                @endif
                                @if($org->status === 'active')
                                <form action="{{ route('admin.organizations.status', $org->id) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <input type="hidden" name="status" value="suspended">
                                    <button type="submit" class="btn btn-sm btn-warning" title="Suspend"><i class="bi bi-pause-circle"></i></button>
                                </form>
                                @endif
                                @if($org->status === 'suspended')
                                <form action="{{ route('admin.organizations.status', $org->id) }}" method="POST" class="d-inline ms-1">
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
                            No organizations found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($organizations->hasPages())
        <div class="card-footer pt-3 pb-2 px-4">
            {{ $organizations->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
