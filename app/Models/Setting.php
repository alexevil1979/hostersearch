<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group', 'key', 'value', 'type',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = Cache::remember("setting.{$key}", 3600, function () use ($key) {
            return static::query()->where('key', $key)->first();
        });

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function setValue(string $key, mixed $value, string $type = 'string', string $group = 'general'): void
    {
        $store = match ($type) {
            'json' => is_string($value) ? $value : json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };

        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $store, 'type' => $type, 'group' => $group]
        );

        Cache::forget("setting.{$key}");
    }
}
