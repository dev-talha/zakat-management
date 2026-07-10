@extends('layouts.app')
@section('title', 'User Management')
@section('breadcrumb')<li class="breadcrumb-item active">Users</li>@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Staff & User Management</h2>
        <p class="page-subtitle">কর্মকর্তা ও প্রশাসনিক ব্যবহারকারী ব্যবস্থাপনা</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-czm-primary"><i class="bi bi-person-plus me-1"></i>Add Staff Member</a>
</div>

<div class="glass-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <form class="d-flex gap-2" method="GET" style="max-width:350px;">
            <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
            <button class="btn btn-czm-outline"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="czm-table">
                <thead>
                    <tr>
                        <th>Staff Member</th>
                        <th>Mobile</th>
                        <th>Assigned Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-icon bg-secondary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:40px; height:40px; font-size:1.2rem;">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div>
                                    <span class="d-block fw-semibold">{{ $user->name }}</span>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->mobile ?: 'N/A' }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary text-uppercase">{{ $role->name }}</span>
                            @endforeach
                            @if($user->roles->isEmpty())
                                <span class="text-muted small">No role assigned</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-status {{ $user->status }}">{{ ucfirst($user->status) }}</span>
                        </td>
                        <td>
                            <small class="text-white fw-semibold">{{ $user->created_at->format('Y-m-d') }}</small>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-czm-outline" title="Show Details"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-czm-outline" title="Edit User"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-czm-outline text-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state py-4">
                                <i class="bi bi-person-fill-lock text-muted d-block" style="font-size:2.5rem;"></i>
                                <h5>No staff users found</h5>
                                <p class="text-muted">তালিকায় কোনো কর্মকর্তা খুঁজে পাওয়া যায়নি।</p>
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
    {{ $users->links() }}
</div>
@endsection
