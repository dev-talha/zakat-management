@extends('layouts.app')
@section('title', 'Add Donor')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('donors.index') }}">Donors</a></li><li class="breadcrumb-item active">Add</li>@endsection

@section('content')
<div class="page-header"><div><h2>Add New Donor</h2><p class="page-subtitle">নতুন দাতা যোগ করুন</p></div></div>

<div class="glass-card" style="max-width:700px;">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        @endif
        <form method="POST" action="{{ route('donors.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" required value="{{ old('name') }}"></div>
                <div class="col-md-6"><label class="form-label">Display Name *</label><input type="text" name="display_name" class="form-control" required value="{{ old('display_name') }}"></div>
                <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required value="{{ old('email') }}"></div>
                <div class="col-md-6"><label class="form-label">Mobile</label><input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="01XXXXXXXXX"></div>
                <div class="col-md-6"><label class="form-label">Donor Type *</label>
                    <select name="donor_type" class="form-select" required>
                        @foreach(['individual','corporate','mosque','institutional'] as $t)<option value="{{ $t }}">{{ ucfirst($t) }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Legal Name</label><input type="text" name="legal_name" class="form-control" value="{{ old('legal_name') }}"></div>
                <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="anonymous_default" id="anon"><label class="form-check-label" for="anon">Anonymous by default</label></div></div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-czm-primary"><i class="bi bi-check-circle me-1"></i>Create Donor</button>
                    <a href="{{ route('donors.index') }}" class="btn btn-czm-outline ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
