@extends('layouts.app')
@section('title', 'Branches')
@section('breadcrumb')<li class="breadcrumb-item active">Branches</li>@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Regional Branches & Mosque Networks</h2>
        <p class="page-subtitle">আঞ্চলিক শাখা ও মসজিদ নেটওয়ার্ক ব্যবস্থাপনা</p>
    </div>
    <a href="{{ route('branches.create') }}" class="btn btn-czm-primary"><i class="bi bi-building-add me-1"></i>Add New Branch</a>
</div>

<div class="glass-card">
    <div class="card-header">
        <h5 class="text-white mb-0"><i class="bi bi-building me-2 text-primary"></i>Branch Network List</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="czm-table">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Region / Division</th>
                        <th>Mosques Registered</th>
                        <th>Assigned Staff</th>
                        <th>Phone & Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-icon bg-secondary text-white rounded me-3 p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                    <i class="bi bi-building-fill"></i>
                                </div>
                                <div>
                                    <span class="d-block fw-semibold text-white">{{ $branch->name }}</span>
                                    <small class="text-muted">Code: <b>{{ $branch->code }}</b></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <small class="d-block text-white fw-semibold">{{ $branch->region ?: 'National Hub' }}</small>
                            <small class="text-muted text-capitalize">{{ $branch->district ?: 'N/A' }}</small>
                        </td>
                        <td class="fw-semibold">{{ $branch->mosques_count }} mosques</td>
                        <td class="fw-semibold">{{ $branch->users_count }} staff</td>
                        <td>
                            <small class="d-block text-white">{{ $branch->phone ?: 'No phone' }}</small>
                            <small class="text-muted">{{ $branch->email ?: 'No email' }}</small>
                        </td>
                        <td>
                            <span class="badge-status {{ $branch->status === 'active' ? 'active' : 'inactive' }}">
                                {{ ucfirst($branch->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('branches.show', $branch) }}" class="btn btn-sm btn-czm-outline" title="Show Details"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-czm-outline" title="Edit Branch"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this branch?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-czm-outline text-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state py-4">
                                <i class="bi bi-building text-muted d-block" style="font-size:2.5rem;"></i>
                                <h5>No branches found</h5>
                                <p class="text-muted">নিবন্ধিত কোনো আঞ্চলিক শাখা পাওয়া যায়নি।</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">
    {{ $branches->links() }}
</div>
@endsection
