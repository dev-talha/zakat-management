<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundLedger extends Model
{
    protected $fillable = ['fund_id', 'branch_id', 'entry_type', 'debit', 'credit', 'ref_type', 'ref_id', 'narration', 'effective_at', 'created_by'];
    protected function casts(): array { return ['effective_at' => 'datetime']; }
    public function fund() { return $this->belongsTo(Fund::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
