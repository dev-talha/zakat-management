<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ZakatVerification extends Model
{
    use LogsActivity;

    protected $fillable = [
        'beneficiary_id', 'case_id', 'verifier_id', 'verifier_type',
        'organization_id', 'volunteer_id', 'imam_muezzin_id',
        'verified_area_id', 'is_within_authority', 'authority_override',
        'override_by', 'visit_date', 'gps_lat', 'gps_lng', 'photo_paths',
        'household_condition_bn', 'income_verified', 'identity_verified',
        'category_appropriate', 'recommendation', 'recommended_amount',
        'notes_bn', 'up_reference_name', 'up_reference_mobile',
        'imam_reference_name', 'neighbor_reference_name',
        'neighbor_reference_mobile', 'status', 'reviewed_by', 'reviewed_at'
    ];

    protected function casts(): array
    {
        return [
            'photo_paths' => 'array',
            'is_within_authority' => 'boolean',
            'authority_override' => 'boolean',
            'income_verified' => 'boolean',
            'identity_verified' => 'boolean',
            'category_appropriate' => 'boolean',
            'visit_date' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['recommendation', 'recommended_amount', 'status', 'reviewed_by'])
            ->logOnlyDirty()
            ->useLogName('verification');
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function caseRecord()
    {
        return $this->belongsTo(CaseRecord::class, 'case_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    public function verifiedArea()
    {
        return $this->belongsTo(GeographicArea::class, 'verified_area_id');
    }
}
