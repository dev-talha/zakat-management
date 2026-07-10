<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DuplicateCandidate extends Model
{
    protected $fillable = [
        'primary_beneficiary_id', 'duplicate_beneficiary_id',
        'match_types', 'confidence_score', 'match_details',
        'review_status', 'reviewed_by', 'reviewed_at', 'review_notes',
    ];

    protected function casts(): array
    {
        return [
            'match_types'  => 'array',
            'match_details'=> 'array',
            'reviewed_at'  => 'datetime',
        ];
    }

    public function primaryBeneficiary()   { return $this->belongsTo(Beneficiary::class, 'primary_beneficiary_id'); }
    public function duplicateBeneficiary() { return $this->belongsTo(Beneficiary::class, 'duplicate_beneficiary_id'); }
    public function reviewedBy()           { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function scopePending($q) { return $q->where('review_status', 'pending'); }
    public function isHighConfidence(): bool { return $this->confidence_score >= 70; }
}
