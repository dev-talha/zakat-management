@extends('layouts.app')
@section('title', 'Complaints')
@section('breadcrumb')<li class="breadcrumb-item active">Complaints</li>@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Grievance Redressal & Support Tickets</h2>
        <p class="page-subtitle">অভিযোগ প্রতিকার ও সহায়তা টিকিট ব্যবস্থাপনা</p>
    </div>
    <a href="{{ route('complaints.create') }}" class="btn btn-czm-primary"><i class="bi bi-chat-left-text me-1"></i>File Complaint</a>
</div>

<div class="glass-card">
    <div class="card-header">
        <h5 class="text-white mb-0"><i class="bi bi-chat-square-text me-2 text-primary"></i>Grievances & Support Tickets</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="czm-table">
                <thead>
                    <tr>
                        <th>Ticket No</th>
                        <th>Complainant</th>
                        <th>Category</th>
                        <th>Severity</th>
                        <th>SLA Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $complaint)
                    <tr>
                        <td class="font-monospace fw-bold text-white">#{{ $complaint->ticket_no }}</td>
                        <td>
                            <span class="d-block fw-semibold text-white">{{ $complaint->complainant_name ?: 'Anonymous' }}</span>
                            <small class="text-muted">{{ $complaint->complainant_contact ?: 'No contact' }}</small>
                        </td>
                        <td class="text-capitalize">{{ $complaint->category ?: 'General' }}</td>
                        <td>
                            <span class="badge text-uppercase @if($complaint->severity === 'critical') bg-danger @elseif($complaint->severity === 'high') bg-warning text-dark @else bg-secondary @endif">
                                {{ $complaint->severity }}
                            </span>
                        </td>
                        <td>
                            <small class="text-white fw-semibold">{{ $complaint->sla_due_at ? \Carbon\Carbon::parse($complaint->sla_due_at)->format('Y-m-d') : 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="badge-status @if($complaint->status === 'resolved' || $complaint->status === 'closed') active @else pending @endif text-capitalize">
                                {{ $complaint->status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-czm-outline" title="Show Details"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-sm btn-czm-outline" title="Edit Ticket"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('complaints.destroy', $complaint) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?')" class="d-inline">
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
                                <i class="bi bi-chat-left-text text-muted d-block" style="font-size:2.5rem;"></i>
                                <h5>No complaints found</h5>
                                <p class="text-muted">কোনো অভিযোগ রেকর্ড পাওয়া যায়নি।</p>
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
    {{ $complaints->links() }}
</div>
@endsection
