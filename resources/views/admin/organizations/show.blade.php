@extends('layouts.app')

@section('title', 'Organization Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800"><i class="bi bi-building"></i> Organization Details</h2>
        <div>
            @if($organization->created_by)
            <form action="{{ route('admin.organizations.impersonate', $organization->id) }}" method="POST" class="d-inline me-2">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    <i class="bi bi-person-fill-gear"></i> Impersonate
                </button>
            </form>
            @endif
            <a href="{{ route('admin.organizations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Info</h6>
                </div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $organization->org_code ?? 'N/A' }}</p>
                    <p><strong>Name (EN):</strong> {{ $organization->name_en }}</p>
                    <p><strong>Name (BN):</strong> {{ $organization->name_bn }}</p>
                    <p><strong>Contact Person:</strong> {{ $organization->contact_person_name }}</p>
                    <p><strong>Mobile:</strong> {{ $organization->contact_mobile }}</p>
                    <p><strong>Email:</strong> {{ $organization->contact_email }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $organization->status == 'active' ? 'success' : ($organization->status == 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($organization->status) }}
                        </span>
                    </p>

                    <hr>
                    <form action="{{ route('admin.organizations.status', $organization->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Update Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $organization->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ $organization->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="suspended" {{ $organization->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="verified" {{ $organization->status == 'verified' ? 'selected' : '' }}>Verified</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Status</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activity Logs</h6>
                </div>
                <div class="card-body">
                    @if($allActivities->isEmpty())
                        <p class="text-muted">No activity logs found for this organization.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Properties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allActivities as $activity)
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $activity->created_at->format('d M Y, h:i A') }}</td>
                                        <td>{{ $activity->description }}</td>
                                        <td>
                                            @if($activity->properties->count() > 0)
                                                <pre style="font-size: 0.75rem; margin:0; max-height: 150px; overflow-y: auto;">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
