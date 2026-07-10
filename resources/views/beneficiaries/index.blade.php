@extends('layouts.app')
@section('title', 'Beneficiaries')
@section('breadcrumb')<li class="breadcrumb-item active">Beneficiaries</li>@endsection
@section('content')
<div class="page-header">
    <div><h2>Beneficiary Management</h2><p class="page-subtitle">সুবিধাভোগী ব্যবস্থাপনা</p></div>
    <a href="{{ route('beneficiaries.create') }}" class="btn btn-czm-primary"><i class="bi bi-plus-circle me-1"></i>New Application</a>
</div>
<div class="glass-card">
    <div class="card-header">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Search name, mobile, app#..." value="{{ request('search') }}" style="max-width:250px;">
            <select name="status" class="form-select" style="max-width:160px;" onchange="this.form.submit()">
                <option value="">All Status</option>
                @foreach(['pending','under_review','verified','approved','rejected','graduated'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <button class="btn btn-czm-outline"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="czm-table">
            <thead><tr><th>App #</th><th>Name</th><th>Mobile</th><th>District</th><th>Category</th><th>Score</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($beneficiaries as $b)
                <tr>
                    <td class="fw-semibold">{{ $b->application_no }}</td>
                    <td>{{ $b->primary_person_name }}</td>
                    <td>{{ $b->mobile ?? 'N/A' }}</td>
                    <td>{{ $b->household?->district ?? 'N/A' }}</td>
                    <td>{{ $b->zakat_category_label }}</td>
                    <td><span class="fw-bold" style="color:var(--czm-gold);">{{ $b->vulnerability_score }}</span></td>
                    <td><span class="badge-status {{ $b->status }}">{{ ucfirst(str_replace('_',' ',$b->status)) }}</span></td>
                    <td>
                        <a href="{{ route('beneficiaries.show', $b) }}" class="btn btn-sm btn-czm-outline"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('beneficiaries.edit', $b) }}" class="btn btn-sm btn-czm-outline"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8"><div class="empty-state"><i class="bi bi-person-hearts d-block"></i><h5>No beneficiaries found</h5></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $beneficiaries->links() }}</div>
@endsection
