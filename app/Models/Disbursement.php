<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    protected $fillable = ['batch_id', 'distribution_id', 'payout_ref', 'amount', 'payout_channel', 'provider_status', 'acknowledged_at'];
    protected function casts(): array { return ['acknowledged_at' => 'datetime']; }
    public function distribution() { return $this->belongsTo(Distribution::class); }

    protected static function booted(): void
    {
        // Anchor each disbursement (Zakat released to a beneficiary) on-chain.
        static::created(function (Disbursement $d) {
            $chain = app(\App\Services\BlockchainService::class);
            if (! $chain->isEnabled()) {
                return;
            }
            $chain->anchorModel($d, $d->payout_ref ?? ('DISB-' . $d->id), [
                'type'         => 'disbursement',
                'payout_ref'   => $d->payout_ref,
                'amount'       => (string) $d->amount,
                'currency'     => 'BDT',
                'channel'      => $d->payout_channel,
                'distribution' => $d->distribution_id,
                'at'           => now()->toIso8601String(),
            ], (float) $d->amount, 'BDT');
        });
    }
}
