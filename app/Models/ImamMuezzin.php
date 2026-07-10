<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImamMuezzin extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'imam_code', 'user_id', 'mosque_id', 'organization_id',
        'role', 'nid_no', 'name_bn', 'name_en', 'mobile',
        'qualification_bn', 'years_of_service', 'address_bn',
        'coverage_area_id', 'coverage_level', 'coverage_village',
        'status', 'validated_by', 'validated_at', 'rejection_reason',
        'total_verifications', 'total_followups', 'total_misuse_reports'
    ];

    protected function casts(): array
    {
        return [
            'validated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function coverageArea()
    {
        return $this->belongsTo(GeographicArea::class, 'coverage_area_id');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public static function generateCode(): string
    {
        $prefix = 'IMAM-' . date('Y') . '-';
        $last = static::where('imam_code', 'like', $prefix . '%')->orderBy('imam_code', 'desc')->value('imam_code');
        $num  = $last ? (int) substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
    }
}
