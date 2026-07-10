@extends('layouts.app')
@section('title', 'Edit Donor')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('donors.index') }}">Donors</a></li><li class="breadcrumb-item active">Edit</li>@endsection

@section('content')
<div class="page-header"><div><h2>Edit Donor</h2></div></div>
<div class="glass-card" style="max-width:700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('donors.update', $donor) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="{{ $donor->user?->name }}"></div>
                <div class="col-md-6"><label class="form-label">Display Name</label><input type="text" name="display_name" class="form-control" value="{{ $donor->display_name }}" required></div>
                <div class="col-md-6"><label class="form-label">Mobile</label><input type="text" name="mobile" class="form-control" value="{{ $donor->user?->mobile }}"></div>
                <div class="col-md-6"><label class="form-label">Donor Type</label><select name="donor_type" class="form-select">@foreach(['individual','corporate','mosque','institutional'] as $t)<option value="{{ $t }}" {{ $donor->donor_type === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Legal Name</label><input type="text" name="legal_name" class="form-control" value="{{ $donor->legal_name }}"></div>
                <div class="col-md-6"><label class="form-label">Tax ID</label><input type="text" name="tax_id" class="form-control" value="{{ $donor->tax_id }}"></div>
                <div class="col-12 mt-3"><button class="btn btn-czm-primary">Update</button> <a href="{{ route('donors.show', $donor) }}" class="btn btn-czm-outline ms-2">Cancel</a></div>
            </div>
        </form>
    </div>
</div>
@endsection
