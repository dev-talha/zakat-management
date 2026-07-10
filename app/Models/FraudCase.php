<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FraudCase extends Model
{
    protected $fillable = [
        'case_no', 'subject_type', 'subject_id', 'reported_by', 'fraud_type',
        'description', 'evidence', 'status', 'investigator_id', 'resolution', 'closed_at',
    ];

    protected function casts(): array
    {
        return ['evidence' => 'array', 'closed_at' => 'datetime'];
    }

    public function subject(): MorphTo     { return $this->morphTo(); }
    public function reportedBy()           { return $this->belongsTo(User::class, 'reported_by'); }
    public function investigator()         { return $this->belongsTo(User::class, 'investigator_id'); }
    public function blacklistEntries()     { return $this->hasMany(Blacklist::class); }

    public static function generateCaseNo(): string
    {
        $prefix = 'FRD-' . date('Y') . '-';
        $last = static::where('case_no', 'like', $prefix . '%')->orderBy('case_no', 'desc')->value('case_no');
        $num  = $last ? (int) substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
    }
}
