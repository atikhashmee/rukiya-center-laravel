<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRegion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use BelongsToRegion;

    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = ['name', 'slug', 'region'];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
