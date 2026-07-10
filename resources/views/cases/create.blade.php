@extends('layouts.app')
@section('title', 'New Case')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('cases.index') }}">Cases</a></li><li class="breadcrumb-item active">New</li>@endsection
@section('content')
<div class="page-header"><div><h2>Create Case</h2></div></div>
<div class="glass-card" style="max-width:700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('cases.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Beneficiary *</label><select name="beneficiary_id" class="form-select" required><option value="">Select Beneficiary...</option>@foreach($beneficiaries as $b)<option value="{{ $b->id }}">{{ $b->application_no }} - {{ $b->primary_person_name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Case Type *</label><select name="case_type" class="form-select" required><option value="medical">Medical</option><option value="education">Education</option><option value="livelihood">Livelihood</option><option value="debt">Debt Relief</option><option value="housing">Housing</option><option value="emergency">Emergency</option></select></div>
                <div class="col-md-6"><label class="form-label">Priority *</label><select name="priority" class="form-select" required><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option><option value="urgent">Urgent</option></select></div>
                <div class="col-md-6"><label class="form-label">Requested Amount (৳)</label><input type="number" name="requested_amount" class="form-control" value="0"></div>
                <div class="col-md-6"><label class="form-label">Assign Agent</label><select name="assigned_agent_id" class="form-select"><option value="">Unassigned</option>@foreach($agents as $a)<option value="{{ $a->id }}">{{ $a->user?->name }} ({{ $a->area_code }})</option>@endforeach</select></div>
                <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                <div class="col-12 mt-3"><button class="btn btn-czm-primary">Create Case</button><a href="{{ route('cases.index') }}" class="btn btn-czm-outline ms-2">Cancel</a></div>
            </div>
        </form>
    </div>
</div>
@endsection
