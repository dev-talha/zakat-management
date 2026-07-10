<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsStatusQuery extends Model
{
    protected $fillable = [
        'from_number', 'raw_message', 'query_type',
        'reference_no', 'was_resolved', 'sms_log_id', 'received_at',
    ];

    protected function casts(): array
    {
        return ['was_resolved' => 'boolean', 'received_at' => 'datetime'];
    }

    public function smsLog() { return $this->belongsTo(SmsLog::class); }
}
