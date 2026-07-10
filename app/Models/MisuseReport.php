<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MisuseReport extends Model
{
    protected $fillable = [
        'report_no', 'beneficiary_id', 'disbursement_id', 'distribution_id',
        'reported_by', 'reporter_type', 'organization_id', 'mosque_id',
        'imam_muezzin_id', 'misuse_type', 'description_bn', 'evidence_paths',
        'status', 'admin_decision', 'restriction_days', 'admin_notes_bn',
        'decided_by', 'decided_at', 'notify_beneficiary'
    ];

    protected function casts(): array
    {
        return [
            'evidence_paths' => 'array',
            'notify_beneficiary' => 'boolean',
            'decided_at' => 'datetime',
        ];
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function decidedBy()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public static function generateCode(): string
    {
        $prefix = 'MIS-' . date('Y') . '-';
        $last = static::where('report_no', 'like', $prefix . '%')->orderBy('report_no', 'desc')->value('report_no');
        $num  = $last ? (int) substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
    }
}
