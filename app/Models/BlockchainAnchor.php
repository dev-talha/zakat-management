<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockchainAnchor extends Model
{
    protected $fillable = [
        'anchorable_type', 'anchorable_id', 'reference', 'amount_minor', 'currency',
        'payload_hash', 'payload', 'network', 'from_address', 'contract_address', 'method',
        'tx_hash', 'block_number', 'status', 'explorer_url', 'error', 'confirmed_at',
    ];

    /** Amount converted back from smallest unit (e.g. paisa → BDT). */
    public function getAmountMajorAttribute(): ?string
    {
        return $this->amount_minor === null ? null : number_format($this->amount_minor / 100, 2);
    }

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'confirmed_at' => 'datetime',
        ];
    }

    public function anchorable()
    {
        return $this->morphTo();
    }
}
