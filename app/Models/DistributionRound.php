<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionRound extends Model
{
    protected $fillable = [
        'round_no', 'fund_id', 'branch_id', 'organization_id', 'name_bn',
        'round_type', 'total_fund_available', 'total_distributed',
        'reserved_for_emergency', 'total_beneficiaries_planned',
        'total_beneficiaries_served', 'round_start_date', 'round_end_date',
        'status', 'prioritize_never_received', 'allow_repeat_recipients',
        'max_per_beneficiary', 'category_allocation_percent',
        'started_at', 'completed_at', 'created_by'
    ];

    protected function casts(): array
    {
        return [
            'category_allocation_percent' => 'array',
            'prioritize_never_received' => 'boolean',
            'allow_repeat_recipients' => 'boolean',
            'round_start_date' => 'date',
            'round_end_date' => 'date',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateCode(): string
    {
        $prefix = 'RND-' . date('Y') . '-';
        $last = static::where('round_no', 'like', $prefix . '%')->orderBy('round_no', 'desc')->value('round_no');
        $num  = $last ? (int) substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }
}
