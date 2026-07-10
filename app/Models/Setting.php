<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['group_code', 'key', 'value_json', 'description'];
    protected function casts(): array { return ['value_json' => 'array']; }

    public static function getValue(string $group, string $key, mixed $default = null): mixed
    {
        $setting = static::where('group_code', $group)->where('key', $key)->first();
        return $setting ? $setting->value_json : $default;
    }

    public static function setValue(string $group, string $key, mixed $value, ?string $description = null): void
    {
        static::updateOrCreate(
            ['group_code' => $group, 'key' => $key],
            ['value_json' => $value, 'description' => $description]
        );
    }
}
