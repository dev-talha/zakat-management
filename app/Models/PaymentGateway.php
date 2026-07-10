<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = ['code', 'name', 'mode', 'config_json', 'active', 'sort_order'];

    protected function casts(): array
    {
        return ['config_json' => 'array', 'active' => 'boolean'];
    }
}
