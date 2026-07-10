<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'user_id', 'branch_id', 'area_code', 'coverage_district',
        'coverage_upazila', 'onboarding_status', 'capacity_score', 'active_cases_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function visits()
    {
        return $this->hasMany(VerificationVisit::class);
    }

    public function cases()
    {
        return $this->hasMany(CaseRecord::class, 'assigned_agent_id');
    }
}
