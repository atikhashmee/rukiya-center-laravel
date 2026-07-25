<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ServiceCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceCategory::withCount('services');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('name')->paginate(12)->withQueryString();

        return Inertia::render('service-categories/index', [
            'categories' => $categories,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('service-categories/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:service_categories,name',
            'description' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        ServiceCategory::create($validated);

        return redirect()->route('serviceCategories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(ServiceCategory $serviceCategory)
    {
        $serviceCategory->loadCount('services');

        return Inertia::render('service-categories/show', [
            'category' => $serviceCategory,
        ]);
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        return Inertia::render('service-categories/edit', [
            'category' => $serviceCategory,
        ]);
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:service_categories,name,'.$serviceCategory->id,
            'description' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $serviceCategory->update($validated);

        return redirect()->route('serviceCategories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        $serviceCategory->delete();

        return redirect()->route('serviceCategories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
