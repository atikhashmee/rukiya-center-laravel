<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductCategory::withCount('products');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('name')->paginate(12)->withQueryString();

        return Inertia::render('product-categories/index', [
            'categories' => $categories,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('product-categories/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:product_categories,name',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = ProductCategory::create($validated);

        return redirect()->route('productCategories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(ProductCategory $productCategory)
    {
        $productCategory->loadCount('products');

        return Inertia::render('product-categories/show', [
            'category' => $productCategory,
        ]);
    }

    public function edit(ProductCategory $productCategory)
    {
        return Inertia::render('product-categories/edit', [
            'category' => $productCategory,
        ]);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:product_categories,name,' . $productCategory->id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $productCategory->update($validated);

        return redirect()->route('productCategories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return redirect()->route('productCategories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
