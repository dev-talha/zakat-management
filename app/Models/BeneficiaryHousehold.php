<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficiaryHousehold extends Model
{
    protected $fillable = [
        'beneficiary_id', 'address', 'address_bn', 'geo_lat', 'geo_lng',
        'division', 'district', 'upazila', 'ward', 'union_name', 'village',
        'housing_type', 'housing_condition',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function members()
    {
        return $this->hasMany(HouseholdMember::class, 'household_id');
    }
}
