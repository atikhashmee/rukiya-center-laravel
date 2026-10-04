<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRegion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    use BelongsToRegion;

    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'region'];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}
