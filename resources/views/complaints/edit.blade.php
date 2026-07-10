@extends('layouts.app')
@section('title', 'Resolve Ticket')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('complaints.index') }}">Complaints</a></li>
    <li class="breadcrumb-item"><a href="{{ route('complaints.show', $complaint) }}">Ticket #{{ $complaint->ticket_no }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Resolve Support Ticket #{{ $complaint->ticket_no }}</h2>
        <p class="page-subtitle">অভিযোগ বা সহায়তা টিকিটের সমাধান বা অগ্রগতি আপডেট করুন</p>
    </div>
    <a href="{{ route('complaints.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back to Complaints</a>
</div>

<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="glass-card mb-4">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-chat-right-dots me-2 text-primary"></i>Original Complaint Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <span class="text-muted small d-block">Complainant:</span>
                        <span class="text-white fw-semibold">{{ $complaint->complainant_name ?: 'Anonymous' }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small d-block">Contact:</span>
                        <span class="text-white fw-semibold">{{ $complaint->complainant_contact ?: 'None' }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small d-block">Category:</span>
                        <span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $complaint->category) }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small d-block">Severity Level:</span>
                        <span class="badge text-uppercase @if($complaint->severity === 'critical') bg-danger @elseif($complaint->severity === 'high') bg-warning text-dark @else bg-secondary @endif">{{ $complaint->severity }}</span>
                    </div>
                    <div class="col-12">
                        <span class="text-muted small d-block">Description:</span>
                        <p class="text-secondary bg-black bg-opacity-25 p-3 rounded" style="white-space: pre-wrap;">{{ $complaint->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-shield-check me-2 text-primary"></i>Resolution & Assignment Updating</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('complaints.update', $complaint) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row g-4">
                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label">Workflow Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="open" {{ old('status', $complaint->status) === 'open' ? 'selected' : '' }}>Open (উন্মুক্ত)</option>
                                <option value="assigned" {{ old('status', $complaint->status) === 'assigned' ? 'selected' : '' }}>Assigned (বরাদ্দকৃত)</option>
                                <option value="investigating" {{ old('status', $complaint->status) === 'investigating' ? 'selected' : '' }}>Investigating (তদন্তাধীন)</option>
                                <option value="resolved" {{ old('status', $complaint->status) === 'resolved' ? 'selected' : '' }}>Resolved (সমাধানকৃত)</option>
                                <option value="closed" {{ old('status', $complaint->status) === 'closed' ? 'selected' : '' }}>Closed (বন্ধকৃত)</option>
                                <option value="escalated" {{ old('status', $complaint->status) === 'escalated' ? 'selected' : '' }}>Escalated (উচ্চস্তরে প্রেরিত)</option>
                            </select>
                        </div>

                        <!-- Assigned To (User ID) -->
                        <div class="col-md-6">
                            <label class="form-label">Assign Agent (User ID)</label>
                            <input type="number" name="assigned_to" class="form-control" placeholder="e.g., 1" value="{{ old('assigned_to', $complaint->assigned_to) }}">
                            <div class="form-text text-muted">প্রশাসনিক কর্মকর্তার ইউজার আইডি লিখুন।</div>
                        </div>

                        <!-- Resolution Notes -->
                        <div class="col-12">
                            <label class="form-label">Resolution Details / Actions Taken</label>
                            <textarea name="resolution" rows="5" class="form-control" placeholder="Summarize the action taken, investigation outcome, or fatwa clarification sent to the complainant...">{{ old('resolution', $complaint->resolution) }}</textarea>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                        <a href="{{ route('complaints.index') }}" class="btn btn-czm-outline">Cancel</a>
                        <button type="submit" class="btn btn-czm-primary"><i class="bi bi-save me-1"></i>Update Resolution</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
