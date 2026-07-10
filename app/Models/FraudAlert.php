<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FraudAlert extends Model
{
    protected $fillable = [
        'alert_code', 'severity', 'subject_type', 'subject_id',
        'description_bn', 'description_en', 'evidence',
        'is_resolved', 'resolved_by', 'resolved_at',
        'resolution_note', 'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'evidence'    => 'array',
            'is_resolved' => 'boolean',
            'resolved_at' => 'datetime',
        ];
    }

    public function subject(): MorphTo { return $this->morphTo(); }
    public function resolvedBy() { return $this->belongsTo(User::class, 'resolved_by'); }
    public function assignedTo() { return $this->belongsTo(User::class, 'assigned_to'); }

    public function scopeUnresolved($q) { return $q->where('is_resolved', false); }
    public function scopeCritical($q)   { return $q->where('severity', 'critical'); }
}
