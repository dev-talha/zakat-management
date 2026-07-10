<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'name_bn', 'slug', 'description', 'description_bn', 'cover_image',
        'fund_type', 'branch_id', 'target_amount', 'collected_amount',
        'starts_at', 'ends_at', 'status', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function branch() { return $this->belongsTo(Branch::class); }
    public function collections() { return $this->hasMany(Collection::class); }

    public function getProgressPercentAttribute(): float
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, round(($this->collected_amount / $this->target_amount) * 100, 1));
    }
}
