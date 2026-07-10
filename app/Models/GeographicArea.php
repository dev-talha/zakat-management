<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeographicArea extends Model
{
    protected $table = 'geographic_areas';

    protected $fillable = [
        'parent_id', 'level', 'name_bn', 'name_en', 'bbs_code',
        'area_type', 'poverty_index', 'population_estimate',
        'geo_lat', 'geo_lng', 'is_active'
    ];

    public function parent()
    {
        return $this->belongsTo(GeographicArea::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(GeographicArea::class, 'parent_id');
    }
}
