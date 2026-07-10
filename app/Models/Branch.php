<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'name', 'name_bn', 'region', 'division', 'district', 'upazila',
        'address', 'phone', 'email', 'geo_lat', 'geo_lng', 'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function mosques()
    {
        return $this->hasMany(Mosque::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
