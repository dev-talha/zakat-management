<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistributionBatch extends Model
{
    use SoftDeletes;
    protected $fillable = ['batch_no', 'branch_id', 'fund_id', 'total_amount', 'total_beneficiaries', 'approval_status', 'payout_channel', 'prepared_by', 'approved_by', 'approved_at'];
    protected function casts(): array { return ['approved_at' => 'datetime']; }
    public function fund() { return $this->belongsTo(Fund::class); }
    public function distributions() { return $this->hasMany(Distribution::class, 'batch_id'); }
}
