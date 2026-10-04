<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRegion;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BelongsToRegion;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'sku',
        'price',
        'stock_quantity',
        'is_active',
        'region',
    ];

    protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id')->orderBy('sort_order');
    }
}
