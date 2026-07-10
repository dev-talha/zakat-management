<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerAreaRestriction extends Model
{
    protected $fillable = [
        'volunteer_id', 'geographic_area_id', 'level', 'can_verify', 'can_followup'
    ];

    protected function casts(): array
    {
        return [
            'can_verify' => 'boolean',
            'can_followup' => 'boolean',
        ];
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function geographicArea()
    {
        return $this->belongsTo(GeographicArea::class);
    }
}
