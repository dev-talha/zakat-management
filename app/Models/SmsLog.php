<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SmsLog extends Model
{
    protected $fillable = [
        'recipient_type', 'recipient_id', 'to_number', 'message',
        'template_code', 'sender_id', 'status', 'gateway_ref',
        'gateway_response', 'cost_unit', 'sent_at',
    ];

    protected function casts(): array
    {
        return ['gateway_response' => 'array', 'sent_at' => 'datetime'];
    }

    public function recipient(): MorphTo { return $this->morphTo(); }
    public function scopeSent($q)   { return $q->where('status', 'sent'); }
    public function scopeFailed($q) { return $q->where('status', 'failed'); }
}
