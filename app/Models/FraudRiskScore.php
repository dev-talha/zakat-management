<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FraudRiskScore extends Model
{
    protected $fillable = [
        'subject_type', 'subject_id', 'score', 'risk_level',
        'factors', 'explanation', 'flagged_by',
        'review_status', 'reviewed_by', 'reviewed_at', 'reviewer_notes',
    ];

    protected function casts(): array
    {
        return [
            'factors'     => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isCritical(): bool { return $this->risk_level === 'critical'; }
    public function isHigh(): bool     { return in_array($this->risk_level, ['high', 'critical']); }
    public function needsReview(): bool{ return $this->review_status === 'pending' && $this->score >= 30; }
}
