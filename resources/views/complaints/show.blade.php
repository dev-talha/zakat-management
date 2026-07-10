@extends('layouts.app')
@section('title', 'Ticket Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('complaints.index') }}">Complaints</a></li>
    <li class="breadcrumb-item active">Ticket Details</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Support Ticket #{{ $complaint->ticket_no }}</h2>
        <p class="page-subtitle">অভিযোগ বা সহায়তা টিকিটের পূর্ণাঙ্গ বিবরণ</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('complaints.index') }}" class="btn btn-czm-outline"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-czm-primary"><i class="bi bi-pencil me-1"></i>Resolve Ticket</a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Details -->
    <div class="col-lg-7">
        <div class="glass-card mb-4">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-chat-square-text me-2 text-primary"></i>Complaint Description</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <span class="text-muted d-block small font-monospace text-uppercase mb-2">Subject / Description</span>
                    <p class="text-white bg-black bg-opacity-20 p-3 rounded" style="white-space: pre-wrap; font-size: 0.95rem; border-left: 3px solid var(--czm-primary);">
                        {{ $complaint->description }}
                    </p>
                </div>

                @if($complaint->resolution)
                <div class="mt-4 pt-3 border-top border-secondary">
                    <span class="text-success fw-bold d-block small font-monospace text-uppercase mb-2"><i class="bi bi-patch-check-fill me-1"></i>Resolution Detail</span>
                    <p class="text-secondary bg-success bg-opacity-5 p-3 rounded border border-success border-opacity-25" style="white-space: pre-wrap; font-size: 0.95rem;">
                        {{ $complaint->resolution }}
                    </p>
                </div>
                @else
                <div class="alert alert-warning border-warning border-opacity-25 bg-warning bg-opacity-5 text-warning mb-0" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>এই অভিযোগটি এখনো সমাধান করা হয়নি। (This complaint is pending resolution).
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Status Metadata -->
    <div class="col-lg-5">
        <div class="glass-card h-100">
            <div class="card-header">
                <h5 class="text-white mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Ticket Status & SLA Metadata</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4 pb-3 border-bottom border-secondary">
                    <span class="text-muted small d-block mb-2">Workflow Status</span>
                    <span class="badge-status @if($complaint->status === 'resolved' || $complaint->status === 'closed') active @else pending @endif fs-6 text-capitalize">
                        {{ $complaint->status }}
                    </span>
                </div>

                <ul class="list-group list-group-flush bg-transparent border-0">
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Ticket ID:</span>
                        <span class="text-white fw-semibold">#{{ $complaint->ticket_no }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Complainant Name:</span>
                        <span class="text-white fw-semibold">{{ $complaint->complainant_name ?: 'Anonymous' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Contact Info:</span>
                        <span class="text-white">{{ $complaint->complainant_contact ?: 'Not Provided' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Grievance Category:</span>
                        <span class="text-white text-capitalize">{{ str_replace('_', ' ', $complaint->category ?: 'general') }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Intake Channel:</span>
                        <span class="text-white text-capitalize">{{ $complaint->channel }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Severity Level:</span>
                        <span class="text-white text-capitalize">{{ $complaint->severity }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>SLA Due Date:</span>
                        <span class="text-warning fw-semibold">{{ $complaint->sla_due_at ? \Carbon\Carbon::parse($complaint->sla_due_at)->format('Y-m-d') : 'N/A' }}</span>
                    </li>
                    <li class="list-group-item bg-transparent text-secondary border-secondary px-0 d-flex justify-content-between">
                        <span>Assigned Agent ID:</span>
                        <span class="text-white">#{{ $complaint->assigned_to ?: 'Unassigned' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
