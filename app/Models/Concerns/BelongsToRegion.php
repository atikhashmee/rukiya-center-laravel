<?php

namespace App\Models\Concerns;

use App\Support\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;

/**
 * Catalog content (services, instructors, products, posts, themes) belongs to one region.
 * Public pages only ever see the current region's records; admin screens clear the
 * region context (Region::withoutScope) to work across all of them.
 */
trait BelongsToRegion
{
    public static function bootBelongsToRegion(): void
    {
        static::addGlobalScope(new class implements Scope
        {
            public function apply(Builder $builder, $model): void
            {
                if ($region = Region::current()) {
                    $builder->where($model->getTable().'.region', $region);
                }
            }
        });

        static::creating(function ($model) {
            $model->region ??= Region::current() ?? Region::UK;
        });
    }

    /**
     * Admin forms send region as an optional field, so a missing value must leave the
     * record where it is rather than clearing it.
     */
    public function setRegionAttribute($value): void
    {
        if ($value !== null && $value !== '') {
            $this->attributes['region'] = $value;
        }
    }

    /** Query one specific region regardless of the current context. */
    public function scopeRegion(Builder $query, string $region): Builder
    {
        return $query->withoutGlobalScopes()->where($this->getTable().'.region', $region);
    }
}
