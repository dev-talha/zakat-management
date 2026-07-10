<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatFollowup extends Model
{
    protected $fillable = [
        'followup_no', 'disbursement_id', 'beneficiary_id', 'distribution_id',
        'reporter_id', 'reporter_type', 'organization_id', 'imam_muezzin_id',
        'followup_date', 'due_date', 'next_followup_date', 'fund_usage_type',
        'fund_usage_description_bn', 'current_condition', 'condition_notes_bn',
        'needs_further_support', 'further_support_reason_bn', 'misuse_suspected',
        'misuse_description_bn', 'photo_evidence_paths', 'status',
        'reviewed_by', 'review_notes_bn', 'reviewed_at'
    ];

    protected function casts(): array
    {
        return [
            'photo_evidence_paths' => 'array',
            'needs_further_support' => 'boolean',
            'misuse_suspected' => 'boolean',
            'followup_date' => 'date',
            'due_date' => 'date',
            'next_followup_date' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public static function generateCode(): string
    {
        $prefix = 'FOL-' . date('Y') . '-';
        $last = static::where('followup_no', 'like', $prefix . '%')->orderBy('followup_no', 'desc')->value('followup_no');
        $num  = $last ? (int) substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
    }
}
