<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'collection_id', 'gateway_id', 'provider_ref', 'tran_id',
        'session_key', 'redirect_url', 'callback_status',
        'validated_status', 'risk_level', 'gateway_response',
    ];

    protected function casts(): array
    {
        return ['gateway_response' => 'array'];
    }

    public function collection() { return $this->belongsTo(Collection::class); }
    public function gateway() { return $this->belongsTo(PaymentGateway::class, 'gateway_id'); }
}
