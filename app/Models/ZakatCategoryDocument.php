<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatCategoryDocument extends Model
{
    protected $fillable = [
        'zakat_category_id', 'doc_key', 'label_bn', 'label_en',
        'description_bn', 'is_required', 'accepted_mime_types',
        'max_size_kb', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'is_active'   => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(ZakatCategory::class, 'zakat_category_id');
    }

    public function getAcceptedMimeArray(): array
    {
        return explode(',', $this->accepted_mime_types);
    }
}
