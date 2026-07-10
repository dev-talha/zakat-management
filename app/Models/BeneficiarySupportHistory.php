<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficiarySupportHistory extends Model
{
    protected $table = 'beneficiary_support_history';

    protected $fillable = [
        'beneficiary_id', 'distribution_id', 'disbursement_id', 'policy_id',
        'zakat_category_id', 'zakat_category_code', 'amount_received',
        'distribution_date', 'payment_method', 'next_eligible_date',
        'followup_required', 'followup_due_date', 'followup_status',
        'is_restricted', 'is_permanently_blocked', 'restriction_reason',
        'restriction_expires_at'
    ];

    protected function casts(): array
    {
        return [
            'followup_required' => 'boolean',
            'is_restricted' => 'boolean',
            'is_permanently_blocked' => 'boolean',
            'distribution_date' => 'date',
            'next_eligible_date' => 'date',
            'followup_due_date' => 'date',
            'restriction_expires_at' => 'datetime',
        ];
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function distribution()
    {
        return $this->belongsTo(Distribution::class);
    }

    public function disbursement()
    {
        return $this->belongsTo(Disbursement::class);
    }

    public function policy()
    {
        return $this->belongsTo(SupportPolicy::class, 'policy_id');
    }
}
