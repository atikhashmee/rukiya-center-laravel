<?php

namespace App\Models;

use App\Support\Region;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = ['region', 'page', 'key', 'value'];

    /** All saved values for one page, keyed by field: ['hero_title' => '...']. */
    public static function forPage(string $page, ?string $region = null): array
    {
        $region ??= Region::current() ?? Region::UK;

        return static::where('region', $region)
            ->where('page', $page)
            ->pluck('value', 'key')
            ->all();
    }

    public static function put(string $page, string $key, ?string $value, ?string $region = null): void
    {
        $region ??= Region::current() ?? Region::UK;

        static::updateOrCreate(
            ['region' => $region, 'page' => $page, 'key' => $key],
            ['value' => $value],
        );
    }
}
