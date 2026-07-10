<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distribution extends Model
{
    use SoftDeletes;
    protected $fillable = ['beneficiary_id', 'case_id', 'fund_id', 'batch_id', 'category_code', 'approved_amount', 'distribution_type', 'status'];
    public function beneficiary() { return $this->belongsTo(Beneficiary::class); }
    public function caseRecord() { return $this->belongsTo(CaseRecord::class, 'case_id'); }
    public function fund() { return $this->belongsTo(Fund::class); }
    public function batch() { return $this->belongsTo(DistributionBatch::class, 'batch_id'); }
    public function disbursement() { return $this->hasOne(Disbursement::class); }
}
