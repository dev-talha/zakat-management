<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiary extends Model
{
    use SoftDeletes;

    /**
     * The recognized Zakat categories (asnaf) selectable on applications.
     * Single source of truth — reused by forms, validation and display.
     * (Amil/administrators is an institutional category, not a self-applied one,
     * so it is intentionally excluded from the applicant list.)
     */
    public const ZAKAT_CATEGORIES = [
        'faqir'        => ['en' => 'Faqir',              'bn' => 'অত্যন্ত দরিদ্র',       'icon' => '😔'],
        'miskin'       => ['en' => 'Miskin',             'bn' => 'অভাবী',                'icon' => '🏚️'],
        'muallaf'      => ['en' => 'Muallafatul Qulub',  'bn' => 'অন্তর আকৃষ্টকরণ',      'icon' => '🤲'],
        'riqab'        => ['en' => 'Fir-Riqab',          'bn' => 'দাসত্ব/বন্দিত্ব মুক্তি', 'icon' => '⛓️'],
        'gharimin'     => ['en' => 'Gharimin',           'bn' => 'ঋণগ্রস্ত',              'icon' => '📉'],
        'fisabilillah' => ['en' => 'Fi Sabilillah',      'bn' => 'আল্লাহর পথে',          'icon' => '🕌'],
        'ibnussabil'   => ['en' => 'Ibnus Sabil',        'bn' => 'অসহায় মুসাফির',        'icon' => '🚶'],
    ];

    /** Valid category keys, for `Rule::in()` validation. */
    public static function zakatCategoryKeys(): array
    {
        return array_keys(self::ZAKAT_CATEGORIES);
    }

    /** Human-readable label for the stored category (handles legacy/blank values). */
    public function getZakatCategoryLabelAttribute(): string
    {
        return self::ZAKAT_CATEGORIES[$this->zakat_category]['en']
            ?? ($this->zakat_category ? ucfirst($this->zakat_category) : '—');
    }

    protected $fillable = [
        'user_id', 'application_no', 'primary_person_name', 'primary_person_name_bn',
        'gender', 'dob', 'identity_type', 'identity_no', 'mobile',
        'monthly_income', 'mobile_banking_provider', 'mobile_banking_account', 'category_specific_data_json',
        'total_assets_value', 'total_liabilities',
        'medical_status', 'disability_flag', 'disability_type',
        'education_level', 'employment_status', 'vulnerability_score', 'ai_score', 'ai_verification_status', 'ai_notes',
        'zakat_category', 'status', 'blacklist_flag', 'watchlist_flag',
        'duplicate_confidence_score', 'rejection_reason', 'branch_id', 'geo_area_id',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'disability_flag' => 'boolean',
            'blacklist_flag' => 'boolean',
            'watchlist_flag' => 'boolean',
            'category_specific_data_json' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function household()
    {
        return $this->hasOne(BeneficiaryHousehold::class);
    }

    public function geoArea()
    {
        return $this->belongsTo(GeographicArea::class, 'geo_area_id');
    }

    public function documents()
    {
        return $this->hasMany(BeneficiaryDocument::class);
    }

    public function cases()
    {
        return $this->hasMany(CaseRecord::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public static function generateApplicationNo(): string
    {
        $prefix = 'BEN-' . date('Y') . '-';
        $last = static::where('application_no', 'like', $prefix . '%')
            ->orderBy('application_no', 'desc')
            ->value('application_no');
        $num = $last ? (int)substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($num, 6, '0', STR_PAD_LEFT);
    }
}
