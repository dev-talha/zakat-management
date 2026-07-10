<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatCategoryForm extends Model
{
    protected $fillable = [
        'zakat_category_id', 'field_key', 'label_bn', 'label_en',
        'placeholder_bn', 'field_type', 'field_options', 'is_required',
        'validation_rules', 'help_text_bn', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'field_options' => 'array',
            'is_required'   => 'boolean',
            'is_active'     => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(ZakatCategory::class, 'zakat_category_id');
    }

    /** Build Laravel validation rules array from stored string */
    public function getValidationRulesArray(): array
    {
        if (empty($this->validation_rules)) {
            return $this->is_required ? ['required'] : ['nullable'];
        }
        return array_filter(explode('|', $this->validation_rules));
    }
}
