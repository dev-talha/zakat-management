@extends('layouts.app')
@section('title', 'Edit Case')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('cases.index') }}">Cases</a></li><li class="breadcrumb-item active">Edit</li>@endsection
@section('content')
<div class="page-header"><div><h2>Edit Case</h2></div></div>
<div class="glass-card" style="max-width:700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('cases.update', $case) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Case Type</label><select name="case_type" class="form-select"><option value="medical" {{ $case->case_type==='medical'?'selected':'' }}>Medical</option><option value="education" {{ $case->case_type==='education'?'selected':'' }}>Education</option><option value="livelihood" {{ $case->case_type==='livelihood'?'selected':'' }}>Livelihood</option></select></div>
                <div class="col-md-6"><label class="form-label">Priority</label><select name="priority" class="form-select"><option value="low" {{ $case->priority==='low'?'selected':'' }}>Low</option><option value="medium" {{ $case->priority==='medium'?'selected':'' }}>Medium</option><option value="high" {{ $case->priority==='high'?'selected':'' }}>High</option><option value="urgent" {{ $case->priority==='urgent'?'selected':'' }}>Urgent</option></select></div>
                <div class="col-md-6"><label class="form-label">Approved Amount</label><input type="number" name="approved_amount" class="form-control" value="{{ $case->approved_amount }}"></div>
                <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ $case->description }}</textarea></div>
                <div class="col-12 mt-3"><button class="btn btn-czm-primary">Update</button><a href="{{ route('cases.show', $case) }}" class="btn btn-czm-outline ms-2">Cancel</a></div>
            </div>
        </form>
    </div>
</div>
@endsection
