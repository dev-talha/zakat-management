@extends('layouts.public')

@section('title', 'Blockchain Transparency | ব্লকচেইন স্বচ্ছতা')
@section('meta_description', 'Every Zakat transaction is anchored to the Ethereum blockchain — publicly verifiable and tamper-proof.')

@push('styles')
<style>
    .tr-hero { background: linear-gradient(135deg, #0f766e 0%, #065f46 100%); padding: 48px 0; color:#fff; }
    .tr-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.05); }
    .tr-row { display:flex; align-items:center; gap:14px; padding:16px 20px; border-bottom:1px solid #f3f4f6; }
    .tr-row:last-child { border-bottom:0; }
    .tr-badge { font-size:0.7rem; font-weight:700; padding:3px 10px; border-radius:20px; }
    .tr-hash { font-family:monospace; font-size:0.78rem; color:#6b7280; word-break:break-all; }
</style>
@endpush

@section('content')
<div class="tr-hero">
    <div class="container">
        <div class="section-badge mb-3" style="background:rgba(255,255,255,0.15);color:#fff;"><i class="bi bi-shield-fill-check"></i> On-chain Verified</div>
        <h1 class="mb-2" style="font-weight:800;">Blockchain Transparency Ledger</h1>
        <p style="opacity:0.9;max-width:680px;">প্রতিটি যাকাত লেনদেনের একটি tamper-proof প্রমাণ Ethereum ({{ ucfirst(config('blockchain.network')) }}) নেটওয়ার্কে সংরক্ষিত হয়। যে কেউ Etherscan-এ গিয়ে স্বাধীনভাবে যাচাই করতে পারেন — কোনো ব্যক্তিগত তথ্য চেইনে যায় না, শুধু রেকর্ডের cryptographic hash।</p>
        <a href="{{ $explorer }}/address/{{ $account }}" target="_blank" class="btn btn-light btn-sm mt-2"><i class="bi bi-box-arrow-up-right me-1"></i>View our account on Etherscan</a>
    </div>
</div>

<div style="background:#f8faf9;padding:40px 0;min-height:50vh;">
    <div class="container">
        <div class="tr-card">
            @forelse($anchors as $a)
                @php
                    $type = $a->anchorable_type ? class_basename($a->anchorable_type) : ($a->payload['type'] ?? 'record');
                    $url = $a->explorer_url ?: ($a->tx_hash ? rtrim($explorer,'/').'/tx/'.$a->tx_hash : null);
                    $label = ['Collection'=>'Donation','Disbursement'=>'Disbursement'][$type] ?? ucfirst(str_replace('_',' ',$type));
                @endphp
                <div class="tr-row">
                    <div style="width:44px;height:44px;border-radius:12px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">
                        {{ $type === 'Disbursement' ? '🤲' : '💚' }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;color:#111827;">
                            {{ $label }} @if($a->amount_major) — {{ $a->amount_major }} {{ $a->currency }} @endif
                            @if($a->status === 'confirmed')
                            <span class="tr-badge" style="background:#dcfce7;color:#065f46;"><i class="bi bi-patch-check-fill"></i> Verified</span>
                            @else
                            <span class="tr-badge" style="background:#fef9c3;color:#854d0e;">On-chain</span>
                            @endif
                        </div>
                        <div class="tr-hash">tx: {{ $a->reference }} · hash: {{ \Illuminate\Support\Str::limit($a->payload_hash, 30) }}</div>
                        <div style="font-size:0.75rem;color:#9ca3af;">{{ $a->created_at->format('d M Y, h:i A') }} @if($a->block_number) · block {{ number_format($a->block_number) }} @endif</div>
                    </div>
                    @if($url)
                    <a href="{{ $url }}" target="_blank" class="btn btn-sm" style="background:#0f766e;color:#fff;font-weight:600;border:none;flex-shrink:0;"><i class="bi bi-box-arrow-up-right"></i> Verify</a>
                    @endif
                </div>
            @empty
                <div style="text-align:center;padding:60px 20px;color:#9ca3af;">
                    <div style="font-size:3rem;">🔗</div>
                    <h5 style="font-weight:700;color:#111827;margin-top:10px;">No on-chain records yet</h5>
                    <p style="font-size:0.9rem;">Verified transactions will appear here once blockchain anchoring is active.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-3">{{ $anchors->links() }}</div>
    </div>
</div>
@endsection
