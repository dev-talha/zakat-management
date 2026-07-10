@extends('layouts.app')
@section('title', 'Cases')
@section('breadcrumb')<li class="breadcrumb-item active">Cases</li>@endsection
@section('content')
<div class="page-header">
    <div><h2>Case Management</h2><p class="page-subtitle">কেস ব্যবস্থাপনা</p></div>
    <a href="{{ route('cases.create') }}" class="btn btn-czm-primary"><i class="bi bi-plus-circle me-1"></i>New Case</a>
</div>
<div class="glass-card">
    <div class="card-header">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Search case no..." value="{{ request('search') }}" style="max-width:250px;">
            <select name="stage" class="form-select" style="max-width:160px;" onchange="this.form.submit()">
                <option value="">All Stages</option>
                @foreach(['assessment','field_verification','supervisor_review','shariah_review','finance_review','approved','disbursement','closed'] as $s)
                    <option value="{{ $s }}" {{ request('stage') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <button class="btn btn-czm-outline"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="czm-table">
            <thead><tr><th>Case #</th><th>Beneficiary</th><th>Type</th><th>Stage</th><th>Priority</th><th>Req. Amount</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($cases as $c)
                <tr>
                    <td class="fw-semibold">{{ $c->case_no }}</td>
                    <td>{{ $c->beneficiary?->primary_person_name }}</td>
                    <td>{{ ucfirst($c->case_type) }}</td>
                    <td><span class="badge-status {{ in_array($c->stage, ['approved','closed']) ? 'active' : 'pending' }}">{{ str_replace('_',' ',$c->stage) }}</span></td>
                    <td><span class="badge-status {{ $c->priority === 'urgent' ? 'rejected' : ($c->priority === 'high' ? 'pending' : 'active') }}">{{ ucfirst($c->priority) }}</span></td>
                    <td>৳{{ number_format($c->requested_amount) }}</td>
                    <td>
                        <a href="{{ route('cases.show', $c) }}" class="btn btn-sm btn-czm-outline"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7"><div class="empty-state"><i class="bi bi-folder2-open d-block"></i><h5>No cases found</h5></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $cases->links() }}</div>
@endsection
