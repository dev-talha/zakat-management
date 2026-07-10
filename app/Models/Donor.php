<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'donor_type', 'display_name', 'legal_name',
        'tax_id', 'anonymous_default', 'kyc_status', 'kyc_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addresses()
    {
        return $this->hasMany(DonorAddress::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function zakatCalculations()
    {
        return $this->hasMany(ZakatCalculation::class);
    }
}
