<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'ticket_no', 'complainant_type', 'complainant_id', 'complainant_name',
        'complainant_contact', 'channel', 'category', 'severity',
        'description', 'sla_due_at', 'status', 'assigned_to', 'resolution',
    ];
    protected function casts(): array { return ['sla_due_at' => 'datetime']; }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }
}
