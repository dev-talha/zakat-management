<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'receipt_no', 'donor_id', 'campaign_id', 'branch_id',
        'fund_type', 'source_channel', 'amount', 'currency',
        'donor_preference', 'is_anonymous', 'status', 'collected_by', 'notes',
        'referral_code', 'referral_type', 'payment_gateway',
        'gateway_transaction_id', 'payment_status',
    ];

    protected function casts(): array
    {
        return ['is_anonymous' => 'boolean'];
    }

    protected static function booted(): void
    {
        // Anchor each paid donation on-chain for a public, tamper-proof trail.
        static::created(function (Collection $c) {
            $chain = app(\App\Services\BlockchainService::class);
            if (! $chain->isEnabled() || $c->payment_status !== 'paid') {
                return;
            }
            $chain->anchorModel($c, $c->receipt_no ?? ('REC-' . $c->id), [
                'type'       => 'donation',
                'receipt_no' => $c->receipt_no,
                'amount'     => (string) $c->amount,
                'currency'   => $c->currency ?? 'BDT',
                'fund'       => $c->fund_type,
                'channel'    => $c->source_channel,
                'at'         => now()->toIso8601String(),
            ], (float) $c->amount, $c->currency ?? 'BDT');
        });
    }

    public function donor() { return $this->belongsTo(Donor::class); }
    public function campaign() { return $this->belongsTo(Campaign::class); }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function collector() { return $this->belongsTo(User::class, 'collected_by'); }

    public static function generateReceiptNo(): string
    {
        $prefix = 'CZM-' . date('Y') . '-';
        $last = static::where('receipt_no', 'like', $prefix . '%')
            ->orderBy('receipt_no', 'desc')->value('receipt_no');
        $num = $last ? (int)substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 8, '0', STR_PAD_LEFT);
    }
}
