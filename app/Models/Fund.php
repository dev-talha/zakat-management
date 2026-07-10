<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $fillable = ['code', 'name', 'name_bn', 'type', 'restricted_flag', 'branch_scoped_flag', 'description', 'status'];
    protected function casts(): array { return ['restricted_flag' => 'boolean', 'branch_scoped_flag' => 'boolean']; }
    public function ledgers() { return $this->hasMany(FundLedger::class); }
    public function getBalanceAttribute(): float
    {
        return $this->ledgers()->sum('credit') - $this->ledgers()->sum('debit');
    }
}
