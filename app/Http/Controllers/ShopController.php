<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Theme;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images'])
            ->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->orderBy('name')->get();
        $categories = ProductCategory::orderBy('name')->get();

        $minPrice = Product::where('is_active', true)->min('price') ?? 0;
        $maxPrice = Product::where('is_active', true)->max('price') ?? 100;

        return view(Theme::resolveViewName('shop'), compact('products', 'categories', 'minPrice', 'maxPrice'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images']);

        if (!$product->is_active) {
            abort(404);
        }

        $related = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view(Theme::resolveViewName('shop-show'), compact('product', 'related'));
    }
}
