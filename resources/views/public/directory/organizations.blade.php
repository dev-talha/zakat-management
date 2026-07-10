@extends('layouts.public')

@section('title', 'Verified Organizations | Zakat Management')

@section('content')
<div class="container py-5">
    <div class="row mb-4 text-center">
        <div class="col-12">
            <h1 class="fw-bold" style="color: #0d6e3f;">Verified Organizations</h1>
            <p class="text-muted">A list of trusted organizations collecting and distributing Zakat under Shariah compliance.</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($organizations as $org)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 rounded-4" style="cursor: pointer; transition: transform 0.2s;" data-bs-toggle="modal" data-bs-target="#orgModal{{ $org->id }}" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-building-check text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold text-dark">{{ $org->name_en }}</h5>
                    <p class="text-muted small mb-2">{{ $org->type ?? 'Organization' }}</p>
                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-shield-check me-1"></i> Verified</span>
                </div>
                <div class="card-footer bg-light border-0 text-center py-3">
                    <small class="text-muted fw-bold">Collected: ৳{{ number_format($org->total_collected_via_referral) }}</small>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="orgModal{{ $org->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-success text-white border-0 rounded-top-4">
                        <h5 class="modal-title fw-bold"><i class="bi bi-building me-2"></i>Organization Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <h4 class="fw-bold text-center mb-1">{{ $org->name_en }}</h4>
                        <p class="text-center text-muted mb-4">{{ $org->name_bn }}</p>

                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted"><i class="bi bi-hash me-2"></i>Registration No.</span>
                                <span class="fw-bold">{{ $org->registration_no ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted"><i class="bi bi-geo-alt me-2"></i>District</span>
                                <span class="fw-bold">{{ $org->district ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted"><i class="bi bi-people me-2"></i>Total Donors</span>
                                <span class="fw-bold">{{ $org->total_donors_via_referral }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted"><i class="bi bi-wallet2 me-2"></i>Total Collected</span>
                                <span class="fw-bold text-success">৳{{ number_format($org->total_collected_via_referral) }}</span>
                            </li>
                        </ul>
                        <div class="text-center">
                            <a href="{{ route('payment.show', ['ref' => $org->referral_code, 'rtype' => 'org']) }}" class="btn btn-success rounded-pill px-4 py-2 w-100 fw-bold">
                                <i class="bi bi-heart-fill me-1"></i> Donate via this Organization
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">No verified organizations found yet.</h4>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $organizations->links() }}
    </div>
</div>
@endsection
