<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseholdMember extends Model
{
    protected $fillable = [
        'household_id', 'name', 'name_bn', 'relation', 'age',
        'gender', 'disability_flag', 'education_status', 'employment_status', 'income',
    ];

    protected function casts(): array
    {
        return ['disability_flag' => 'boolean'];
    }

    public function household()
    {
        return $this->belongsTo(BeneficiaryHousehold::class, 'household_id');
    }
}
