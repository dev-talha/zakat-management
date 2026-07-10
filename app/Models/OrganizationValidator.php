<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationValidator extends Model
{
    protected $fillable = [
        'user_id', 'scope', 'scope_value', 'is_active', 'assigned_by', 'assigned_at'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'assigned_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
