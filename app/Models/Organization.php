<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'org_code', 'referral_code', 'name_bn', 'name_en', 'type', 'registration_no',
        'trade_license_no', 'ngo_registration_no', 'contact_person_name',
        'contact_mobile', 'contact_email', 'website', 'description_bn',
        'logo_path', 'division', 'district', 'upazila', 'union_name', 'address',
        'coverage_area_ids', 'branch_id', 'status', 'verified_by',
        'verified_at', 'rejection_reason', 'suspension_reason',
        'can_manage_own_fund', 'field_visit_completed', 'created_by',
        'total_collected_via_referral', 'total_donors_via_referral',
    ];

    protected function casts(): array
    {
        return [
            'coverage_area_ids' => 'array',
            'verified_at' => 'datetime',
            'can_manage_own_fund' => 'boolean',
            'field_visit_completed' => 'boolean',
        ];
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function volunteers()
    {
        return $this->hasMany(Volunteer::class);
    }

    public function imamMuezzins()
    {
        return $this->hasMany(ImamMuezzin::class);
    }

    // A helper function to generate org code
    public static function generateCode(): string
    {
        $prefix = 'ORG-' . date('Y') . '-';
        $last = static::where('org_code', 'like', $prefix . '%')->orderBy('org_code', 'desc')->value('org_code');
        $num  = $last ? (int) substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    // Generate a unique referral code for this organization
    public static function generateReferralCode(): string
    {
        do {
            $code = 'O' . strtoupper(substr(md5(uniqid()), 0, 7));
        } while (static::where('referral_code', $code)->exists());
        return $code;
    }

    // Get the full referral URL
    public function getReferralUrlAttribute(): string
    {
        return url('/r/' . $this->referral_code);
    }
}
