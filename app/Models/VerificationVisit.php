<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationVisit extends Model
{
    protected $fillable = [
        'case_id', 'agent_id', 'visit_at', 'gps_lat', 'gps_lng',
        'summary', 'summary_bn', 'interview_data_json', 'risk_flag', 'risk_reason',
        'photo_paths', 'document_paths', 'supervisor_status',
        'supervisor_id', 'supervisor_notes', 'supervisor_reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'visit_at' => 'datetime',
            'supervisor_reviewed_at' => 'datetime',
            'risk_flag' => 'boolean',
            'interview_data_json' => 'array',
            'photo_paths' => 'array',
            'document_paths' => 'array',
        ];
    }

    public function caseRecord()
    {
        return $this->belongsTo(CaseRecord::class, 'case_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
