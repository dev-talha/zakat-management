<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class CaseRecord extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'cases';

    protected $fillable = [
        'case_no', 'beneficiary_id', 'branch_id', 'case_type', 'priority',
        'stage', 'source', 'requested_amount', 'approved_amount',
        'description', 'description_bn', 'outcome_status',
        'follow_up_date', 'assigned_agent_id', 'assigned_volunteer_id',
    ];

    protected function casts(): array
    {
        return ['follow_up_date' => 'date'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['stage', 'requested_amount', 'approved_amount', 'assigned_volunteer_id'])
            ->logOnlyDirty()
            ->useLogName('case');
    }

    public function assignedVolunteer()
    {
        return $this->belongsTo(Volunteer::class, 'assigned_volunteer_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'assigned_agent_id');
    }

    public function notes()
    {
        return $this->hasMany(CaseNote::class, 'case_id');
    }

    public function visits()
    {
        return $this->hasMany(VerificationVisit::class, 'case_id');
    }

    public function approvals()
    {
        return $this->morphMany(Approval::class, 'entity');
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class, 'case_id');
    }

    public static function generateCaseNo(): string
    {
        $prefix = 'CASE-' . date('Y') . '-';
        $last = static::where('case_no', 'like', $prefix . '%')
            ->orderBy('case_no', 'desc')
            ->value('case_no');
        $num = $last ? (int)substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 6, '0', STR_PAD_LEFT);
    }
}
