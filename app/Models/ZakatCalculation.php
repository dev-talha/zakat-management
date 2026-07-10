<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatCalculation extends Model
{
    protected $fillable = [
        'donor_id', 'user_id', 'rule_pack', 'nisab_basis', 'nisab_value',
        'asset_snapshot_json', 'liability_snapshot_json', 'total_assets',
        'total_liabilities', 'net_zakatable', 'zakat_due', 'zakat_rate', 'is_eligible',
    ];

    protected function casts(): array
    {
        return [
            'asset_snapshot_json' => 'array',
            'liability_snapshot_json' => 'array',
            'is_eligible' => 'boolean',
        ];
    }

    public function donor() { return $this->belongsTo(Donor::class); }
}
