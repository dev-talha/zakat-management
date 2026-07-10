<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatCategory extends Model
{
    protected $fillable = [
        'code', 'arabic_name', 'name_bn', 'name_en',
        'description_bn', 'description_en', 'eligibility_criteria_bn',
        'icon_class', 'color_hex', 'sort_order', 'is_active',
        'requires_field_visit', 'requires_shariah_review',
    ];

    protected function casts(): array
    {
        return [
            'is_active'               => 'boolean',
            'requires_field_visit'    => 'boolean',
            'requires_shariah_review' => 'boolean',
        ];
    }

    public function formFields()
    {
        return $this->hasMany(ZakatCategoryForm::class)->orderBy('sort_order');
    }

    public function requiredDocuments()
    {
        return $this->hasMany(ZakatCategoryDocument::class)->orderBy('sort_order');
    }

    public function activeFormFields()
    {
        return $this->formFields()->where('is_active', true);
    }

    public function activeRequiredDocuments()
    {
        return $this->requiredDocuments()->where('is_active', true);
    }

    public function beneficiaries()
    {
        return $this->hasManyThrough(Beneficiary::class, BeneficiaryCategoryData::class,
            'zakat_category_id', 'id', 'id', 'beneficiary_id');
    }

    public function categoryData()
    {
        return $this->hasMany(BeneficiaryCategoryData::class);
    }

    /** Active categories available for public registration (excluding Al-Amilina system role) */
    public static function forRegistration()
    {
        return static::where('is_active', true)
            ->where('code', '!=', 'amilina') // Al-Amilina is system role only
            ->orderBy('sort_order')
            ->get();
    }
}
