<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mosque extends Model
{
    protected $fillable = [
        'branch_id', 'name', 'name_bn', 'committee_contact',
        'address', 'geo_lat', 'geo_lng', 'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
