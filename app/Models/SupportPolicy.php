<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportPolicy extends Model
{
    protected $fillable = [
        'policy_code', 'name_bn', 'name_en', 'description_bn', 'support_type',
        'min_interval_days', 'max_times_per_year', 'max_consecutive_years',
        'requires_followup_before_reapply', 'requires_imam_recommendation',
        'requires_new_verification', 'auto_schedule_followup', 'followup_after_days',
        'auto_review_interval_days', 'applicable_zakat_categories',
        'priority_penalty_per_receipt', 'priority_penalty_max',
        'is_active', 'is_default', 'sort_order', 'created_by'
    ];

    protected function casts(): array
    {
        return [
            'applicable_zakat_categories' => 'array',
            'requires_followup_before_reapply' => 'boolean',
            'requires_imam_recommendation' => 'boolean',
            'requires_new_verification' => 'boolean',
            'auto_schedule_followup' => 'boolean',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
