<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficiaryDocument extends Model
{
    protected $fillable = [
        'beneficiary_id', 'doc_type', 'original_name', 'file_path',
        'mime_type', 'file_size', 'verification_status',
        'verified_by', 'verified_at', 'verification_notes',
    ];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime'];
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
