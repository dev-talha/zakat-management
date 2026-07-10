<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PorichoyVerification extends Model
{
    protected $fillable = [
        'verifiable_type', 'verifiable_id', 'nid_no', 'dob', 'status',
        'api_response', 'matched_name', 'matched_dob',
        'photo_matched', 'api_request_id', 'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'api_response'  => 'array',
            'photo_matched' => 'boolean',
            'verified_at'   => 'datetime',
        ];
    }

    public function verifiable(): MorphTo { return $this->morphTo(); }

    public function isMatched(): bool   { return $this->status === 'matched'; }
    public function isSkipped(): bool   { return $this->status === 'skipped'; }
    public function hasMismatch(): bool { return $this->status === 'mismatch'; }
    public function notFound(): bool    { return $this->status === 'not_found'; }
}
