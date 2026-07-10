<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficiaryCategoryData extends Model
{
    protected $fillable = [
        'beneficiary_id', 'zakat_category_id', 'form_data',
        'verification_status', 'verification_notes', 'verified_by', 'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'form_data'   => 'array',
            'verified_at' => 'datetime',
        ];
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function category()
    {
        return $this->belongsTo(ZakatCategory::class, 'zakat_category_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /** Get a specific field value from the dynamic form data */
    public function getField(string $key, mixed $default = null): mixed
    {
        return data_get($this->form_data, $key, $default);
    }
}
