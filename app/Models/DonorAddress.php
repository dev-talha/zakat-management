<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonorAddress extends Model
{
    protected $fillable = [
        'donor_id', 'country', 'division', 'district',
        'upazila', 'union_name', 'address_line', 'postal_code',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}
