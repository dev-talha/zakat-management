@extends('layouts.app')
@section('title', 'Edit Beneficiary')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('beneficiaries.index') }}">Beneficiaries</a></li><li class="breadcrumb-item active">Edit</li>@endsection
@section('content')
<div class="page-header"><div><h2>Edit Beneficiary</h2></div></div>
<div class="glass-card" style="max-width:800px;">
    <div class="card-body">
        <form method="POST" action="{{ route('beneficiaries.update', $beneficiary) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Name</label><input type="text" name="primary_person_name" class="form-control" value="{{ $beneficiary->primary_person_name }}" required></div>
                <div class="col-md-6"><label class="form-label">Mobile</label><input type="text" name="mobile" class="form-control" value="{{ $beneficiary->mobile }}"></div>
                <div class="col-md-4"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="male" {{ $beneficiary->gender === 'male' ? 'selected' : '' }}>Male</option><option value="female" {{ $beneficiary->gender === 'female' ? 'selected' : '' }}>Female</option></select></div>
                <div class="col-md-4"><label class="form-label">Income</label><input type="number" name="monthly_income" class="form-control" value="{{ $beneficiary->monthly_income }}"></div>
                <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['pending','under_review','verified','approved','rejected'] as $s)<option value="{{ $s }}" {{ $beneficiary->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
                <div class="col-12 mt-3"><button class="btn btn-czm-primary">Update</button><a href="{{ route('beneficiaries.show', $beneficiary) }}" class="btn btn-czm-outline ms-2">Cancel</a></div>
            </div>
        </form>
    </div>
</div>
@endsection
