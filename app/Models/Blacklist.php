<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Blacklist extends Model
{
    protected $fillable = [
        'identity_type', 'identity_no', 'name', 'mobile', 'district',
        'reason', 'severity', 'added_by', 'fraud_case_id', 'expires_at', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'expires_at' => 'datetime'];
    }

    public function addedBy()   { return $this->belongsTo(User::class, 'added_by'); }
    public function fraudCase() { return $this->belongsTo(FraudCase::class); }

    /** Check if a beneficiary hits the blacklist */
    public static function checkBeneficiary(Beneficiary $b): ?self
    {
        return static::where('is_active', true)
            ->where(function ($q) use ($b) {
                if ($b->identity_no) {
                    $q->orWhere(fn($sq) => $sq->where('identity_type', $b->identity_type)->where('identity_no', $b->identity_no));
                }
                if ($b->mobile) {
                    $q->orWhere('mobile', $b->mobile);
                }
            })
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->first();
    }
}
