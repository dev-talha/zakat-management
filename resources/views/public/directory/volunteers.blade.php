@extends('layouts.public')

@section('title', 'Verified Volunteers | Zakat Management')

@section('content')
<div class="container py-5">
    <div class="row mb-4 text-center">
        <div class="col-12">
            <h1 class="fw-bold" style="color: #0d6e3f;">Verified Volunteers</h1>
            <p class="text-muted">Dedicated individuals on the ground ensuring your Zakat reaches the right hands.</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($volunteers as $vol)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 rounded-4" style="cursor: pointer; transition: transform 0.2s;" data-bs-toggle="modal" data-bs-target="#volModal{{ $vol->id }}" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-person-badge text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title fw-bold text-dark">{{ $vol->name_en }}</h5>
                    <p class="text-muted small mb-2">{{ $vol->district ?? 'Volunteer' }}</p>
                    <span class="badge bg-primary rounded-pill px-3 py-2"><i class="bi bi-check2-circle me-1"></i> Active</span>
                </div>
                <div class="card-footer bg-light border-0 text-center py-3">
                    <small class="text-muted fw-bold">Verifications: {{ $vol->total_verifications }}</small>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="volModal{{ $vol->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                        <h5 class="modal-title fw-bold"><i class="bi bi-person-vcard me-2"></i>Volunteer Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <h4 class="fw-bold text-center mb-1">{{ $vol->name_en }}</h4>
                        <p class="text-center text-muted mb-4">{{ $vol->name_bn }}</p>

                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted"><i class="bi bi-briefcase me-2"></i>Occupation</span>
                                <span class="fw-bold">{{ $vol->occupation ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted"><i class="bi bi-geo-alt me-2"></i>District/Area</span>
                                <span class="fw-bold">{{ $vol->district ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted"><i class="bi bi-file-earmark-check me-2"></i>Field Verifications</span>
                                <span class="fw-bold">{{ $vol->total_verifications }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted"><i class="bi bi-wallet2 me-2"></i>Referred Collections</span>
                                <span class="fw-bold text-success">৳{{ number_format($vol->total_collected_via_referral) }}</span>
                            </li>
                        </ul>
                        <div class="text-center">
                            <a href="{{ route('payment.show', ['ref' => $vol->referral_code, 'rtype' => 'volunteer']) }}" class="btn btn-primary rounded-pill px-4 py-2 w-100 fw-bold">
                                <i class="bi bi-heart-fill me-1"></i> Donate via this Volunteer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">No active volunteers found yet.</h4>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $volunteers->links() }}
    </div>
</div>
@endsection
