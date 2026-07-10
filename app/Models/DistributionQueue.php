<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionQueue extends Model
{
    protected $fillable = [
        'beneficiary_id', 'case_id', 'zakat_category_id', 'distribution_round_id',
        'requested_amount', 'minimum_acceptable_amount', 'approved_amount',
        'priority_score', 'priority_factors', 'vulnerability_score',
        'category_weight', 'waiting_time_score', 'urgency_score',
        'recommendation_score', 'repeat_receipt_penalty', 'misuse_history_penalty',
        'previously_received_count', 'last_received_date', 'queue_position',
        'queue_status', 'added_to_queue_at', 'distributed_at', 'skip_reason'
    ];

    protected function casts(): array
    {
        return [
            'priority_factors' => 'array',
            'last_received_date' => 'date',
            'added_to_queue_at' => 'datetime',
            'distributed_at' => 'datetime',
        ];
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function caseRecord()
    {
        return $this->belongsTo(CaseRecord::class, 'case_id');
    }

    public function round()
    {
        return $this->belongsTo(DistributionRound::class, 'distribution_round_id');
    }
}
