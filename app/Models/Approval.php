<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = ['entity_type', 'entity_id', 'step_name', 'approver_id', 'decision', 'reason', 'decided_at'];
    protected function casts(): array { return ['decided_at' => 'datetime']; }
    public function entity() { return $this->morphTo(); }
    public function approver() { return $this->belongsTo(User::class, 'approver_id'); }
}
