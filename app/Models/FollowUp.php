<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = [
        'case_id', 'agent_id', 'follow_up_date', 'notes',
        'impact_rating', 'funds_utilized_properly', 'next_follow_up_date'
    ];

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
            'next_follow_up_date' => 'date',
            'funds_utilized_properly' => 'boolean',
        ];
    }

    public function caseRecord()
    {
        return $this->belongsTo(CaseRecord::class, 'case_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
