<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ModuleSetting extends Model
{
    protected $fillable = [
        'module_code', 'setting_key', 'setting_value', 'data_type',
        'label_bn', 'label_en', 'description_bn', 'group_label_bn',
        'is_sensitive', 'is_public', 'sort_order', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'setting_value' => 'json',
            'is_sensitive'  => 'boolean',
            'is_public'     => 'boolean',
        ];
    }

    // ─── Static Helpers ────────────────────────────────────────────────────────

    /** কোনো মডিউল চালু আছে কিনা দেখুন */
    public static function isEnabled(string $module): bool
    {
        return (bool) static::get($module, 'enabled', false);
    }

    /** একটি সেটিং মান পড়ুন, cache-সহ */
    public static function get(string $module, string $key, mixed $default = null): mixed
    {
        $cacheKey = "module_setting:{$module}:{$key}";
        return Cache::remember($cacheKey, 300, function () use ($module, $key, $default) {
            $row = static::where('module_code', $module)->where('setting_key', $key)->first();
            return $row ? $row->setting_value : $default;
        });
    }

    /** একটি সেটিং মান সংরক্ষণ করুন এবং cache পরিষ্কার করুন */
    public static function set(string $module, string $key, mixed $value, ?int $updatedBy = null): void
    {
        static::updateOrCreate(
            ['module_code' => $module, 'setting_key' => $key],
            ['setting_value' => $value, 'updated_by' => $updatedBy]
        );
        Cache::forget("module_setting:{$module}:{$key}");
    }

    /** কোনো মডিউলের সব সেটিংস key=>value array হিসেবে পড়ুন */
    public static function allFor(string $module): array
    {
        return static::where('module_code', $module)
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->setting_key => $row->setting_value])
            ->toArray();
    }

    /** Admin UI: কোনো মডিউলের সব row পড়ুন (display meta সহ) */
    public static function rowsFor(string $module)
    {
        return static::where('module_code', $module)->orderBy('sort_order')->get();
    }

    /** একটি মডিউল enable/disable করুন (shorthand) */
    public static function toggleModule(string $module, bool $enabled, ?int $updatedBy = null): void
    {
        static::set($module, 'enabled', $enabled, $updatedBy);
    }

    // ─── Relationships ──────────────────────────────────────────────────────────

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
