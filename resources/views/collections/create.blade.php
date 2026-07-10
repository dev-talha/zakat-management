@extends('layouts.app')
@section('title', 'New Collection')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('collections.index') }}">Collections</a></li><li class="breadcrumb-item active">New</li>@endsection
@section('content')
<div class="page-header"><div><h2>Record Collection</h2></div></div>
<div class="glass-card" style="max-width:700px;">
    <div class="card-body">
        <form method="POST" action="{{ route('collections.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Donor</label>
                    <select name="donor_id" class="form-select">
                        <option value="">Select Donor (Leave empty for guest)</option>
                        @foreach($donors as $d)<option value="{{ $d->id }}">{{ $d->display_name }} ({{ $d->user?->mobile }})</option>@endforeach
                    </select>
                </div>
                <div class="col-md-4 pt-4">
                    <div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="is_anonymous" id="anon" value="1"><label class="form-check-label" for="anon">Keep Anonymous</label></div>
                </div>
                
                <div class="col-md-6"><label class="form-label">Fund Type *</label>
                    <select name="fund_type" class="form-select" required>
                        <option value="zakat">Zakat (যাকাত)</option>
                        <option value="sadaqah">Sadaqah (সদকা)</option>
                        <option value="fitrah">Fitrah (ফিতরা)</option>
                        <option value="waqf">Waqf (ওয়াক্‌ফ)</option>
                        <option value="general">General (সাধারণ)</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Amount (BDT) *</label>
                    <div class="input-group"><span class="input-group-text">৳</span><input type="number" name="amount" class="form-control" required min="1" value="{{ request('amount') }}"></div>
                </div>
                
                <div class="col-md-6"><label class="form-label">Campaign</label>
                    <select name="campaign_id" class="form-select"><option value="">No Campaign</option>@foreach($campaigns as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select>
                </div>
                <div class="col-md-6"><label class="form-label">Payment Channel *</label>
                    <select name="source_channel" class="form-select" required><option value="cash">Cash (Manual)</option><option value="bank_transfer">Bank Transfer</option><option value="cheque">Cheque</option><option value="pos">POS Terminal</option></select>
                </div>
                
                <div class="col-12"><label class="form-label">Notes / Reference</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
                
                <div class="col-12 mt-4">
                    <button class="btn btn-czm-primary"><i class="bi bi-check-circle me-1"></i>Record Collection</button>
                    <a href="{{ route('collections.index') }}" class="btn btn-czm-outline ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
