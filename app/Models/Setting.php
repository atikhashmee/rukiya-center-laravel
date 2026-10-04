<?php

namespace App\Models;

use App\Support\Region;
use Illuminate\Database\Eloquent\Model;

/**
 * Key/value site settings, stored per region so each country can hold its own
 * value for the same key (a UK WhatsApp number and a Bangladeshi one, say).
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value', 'region'];

    public static function get(string $key, ?string $default = null, ?string $region = null): ?string
    {
        $region ??= Region::current() ?? Region::UK;

        return static::where('key', $key)->where('region', $region)->value('value') ?? $default;
    }

    public static function set(string $key, ?string $value, ?string $region = null): void
    {
        $region ??= Region::current() ?? Region::UK;

        static::updateOrCreate(['key' => $key, 'region' => $region], ['value' => $value]);
    }
}
