@extends('layouts.app')
@section('title', 'Donors')
@section('breadcrumb')<li class="breadcrumb-item active">Donors</li>@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Donor Management</h2>
        <p class="page-subtitle">দাতা ব্যবস্থাপনা</p>
    </div>
    <a href="{{ route('donors.create') }}" class="btn btn-czm-primary"><i class="bi bi-plus-circle me-1"></i>Add Donor</a>
</div>

<div class="glass-card">
    <div class="card-header">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Search donors..." value="{{ request('search') }}" style="max-width:250px;">
            <select name="type" class="form-select" style="max-width:160px;" onchange="this.form.submit()">
                <option value="">All Types</option>
                @foreach(['individual','corporate','mosque','institutional'] as $t)
                    <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
            <button class="btn btn-czm-outline"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="czm-table">
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Type</th><th>KYC</th><th>Donations</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($donors as $donor)
                    <tr>
                        <td>#{{ $donor->id }}</td>
                        <td class="fw-semibold">{{ $donor->display_name }}</td>
                        <td>{{ $donor->user?->email }}</td>
                        <td><span class="badge-status active">{{ ucfirst($donor->donor_type) }}</span></td>
                        <td><span class="badge-status {{ $donor->kyc_status }}">{{ ucfirst($donor->kyc_status) }}</span></td>
                        <td>{{ $donor->collections_count ?? $donor->collections->count() }}</td>
                        <td>
                            <a href="{{ route('donors.show', $donor) }}" class="btn btn-sm btn-czm-outline"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('donors.edit', $donor) }}" class="btn btn-sm btn-czm-outline"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-people d-block"></i><h5>No donors found</h5></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $donors->links() }}</div>
@endsection
