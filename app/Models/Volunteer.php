<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Volunteer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'volunteer_code', 'referral_code', 'user_id', 'organization_id', 'nid_no',
        'name_bn', 'name_en', 'mobile', 'occupation', 'address_bn',
        'division', 'district', 'upazila', 'union_name',
        'primary_area_id', 'coverage_level', 'status', 'validated_by',
        'validated_at', 'rejection_reason', 'total_verifications',
        'total_followups', 'last_active_at',
        'total_collected_via_referral', 'total_donors_via_referral',
    ];

    protected function casts(): array
    {
        return [
            'validated_at' => 'datetime',
            'last_active_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function primaryArea()
    {
        return $this->belongsTo(GeographicArea::class, 'primary_area_id');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function areaRestrictions()
    {
        return $this->hasMany(VolunteerAreaRestriction::class);
    }

    public static function generateCode(): string
    {
        $prefix = 'VOL-' . date('Y') . '-';
        $last = static::where('volunteer_code', 'like', $prefix . '%')->orderBy('volunteer_code', 'desc')->value('volunteer_code');
        $num  = $last ? (int) substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    public static function generateReferralCode(): string
    {
        do {
            $code = 'V' . strtoupper(substr(md5(uniqid()), 0, 7));
        } while (static::where('referral_code', $code)->exists());
        return $code;
    }

    public function getReferralUrlAttribute(): string
    {
        return url('/v/' . $this->referral_code);
    }
}
