@extends('layouts.app')
@section('title', 'Audit Logs')
@section('breadcrumb')<li class="breadcrumb-item active">Audit</li>@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>System Audit Trails</h2>
        <p class="page-subtitle">সিস্টেমের কার্যক্রম ও নিরাপত্তা নিরীক্ষা লগ</p>
    </div>
</div>

<div class="glass-card">
    <div class="card-header">
        <h5 class="text-white mb-0"><i class="bi bi-shield-check me-2 text-primary"></i>Security & Operations Timeline</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="czm-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Actor</th>
                        <th>Event</th>
                        <th>Target Component</th>
                        <th>Description</th>
                        <th>Subject ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <small class="d-block text-white fw-semibold">{{ $log->created_at->format('Y-m-d H:i:s') }}</small>
                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-icon bg-secondary text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:30px; height:30px; font-size:0.9rem;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <span class="fw-semibold">{{ $log->causer?->name ?: 'System Job / Guest' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge text-uppercase @if($log->event === 'created') bg-success @elseif($log->event === 'deleted') bg-danger @else bg-info @endif">
                                {{ $log->event ?: 'Action' }}
                            </span>
                        </td>
                        <td class="font-monospace text-muted" style="font-size:0.75rem;">
                            {{ $log->subject_type ? class_basename($log->subject_type) : 'System Context' }}
                        </td>
                        <td class="text-white">
                            {{ $log->description }}
                        </td>
                        <td class="fw-bold font-monospace">
                            {{ $log->subject_id ? '#' . $log->subject_id : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state py-4">
                                <i class="bi bi-shield-slash text-muted d-block" style="font-size:2.5rem;"></i>
                                <h5>No audit logs found</h5>
                                <p class="text-muted">কোনো নিরাপত্তা নিরীক্ষা রেকর্ড পাওয়া যায়নি।</p>
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
    {{ $logs->links() }}
</div>
@endsection
