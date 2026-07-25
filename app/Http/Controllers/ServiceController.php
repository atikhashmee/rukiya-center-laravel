<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceStoreRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('tagline', 'like', "%{$search}%")
                  ->orWhere('id_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('price_type')) {
            $query->where('price_type', $request->price_type);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('assessment')) {
            $query->where('requires_custom_assessment', $request->assessment === 'required');
        }

        $services = $query->orderBy('order', 'asc')->paginate(12)->withQueryString();

        $categories = ServiceCategory::orderBy('name')->get(['id', 'name']);

        return Inertia::render('services/index', [
            'services' => $services,
            'categories' => $categories,
            'filters' => $request->only(['search', 'price_type', 'category', 'assessment']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceCategories = ServiceCategory::orderBy('name')->get();

        return Inertia::render('services/create', [
            'serviceCategories' => $serviceCategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceStoreRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $service = Service::create($validatedData);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create service option: '.$e->getMessage());
        }

        return redirect()->route('services.index')
            ->with('success', 'Service option created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return Inertia::render('services/show', [
            'service' => $service,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $serviceCategories = ServiceCategory::orderBy('name')->get();

        $serviceData = $service->toArray();
        $serviceData['features'] = is_string($service->features)
            ? json_decode($service->features, true)
            : $service->features;
        $serviceData['required_form_fields'] = is_string($service->required_form_fields)
            ? json_decode($service->required_form_fields, true)
            : $service->required_form_fields;
        $serviceData['schedules'] = $service->schedules()->orderBy('day_of_week')->get();

        return Inertia::render('services/edit', [
            'service' => $serviceData,
            'serviceCategories' => $serviceCategories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $features = explode(',', $request->input('features', ''));
        $requiredFormFields = explode(',', $request->input('required_form_fields', ''));
        $request->merge([
            'features' => $features,
            'required_form_fields' => $requiredFormFields,
        ]);
        $validated = $request->validate([
            'id_code' => 'required|string|max:255|unique:services,id_code,'.$service->id,
            'category_id' => 'required|exists:service_categories,id',
            'title' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'card_color' => 'nullable|string|max:100',
            'features' => 'nullable|array',
            'order' => 'required|integer|min:1',
            'price_type' => 'required|in:FREE,DONATION,FIXED,RESERVATION',
            'price_value' => 'nullable|numeric|min:0',
            'min_donation' => 'nullable|numeric|min:0',
            'requires_custom_assessment' => 'boolean',
            'required_form_fields' => 'nullable|array',
            'submit_button_text' => 'nullable|string|max:100',
        ]);

        // Service::features / required_form_fields are already cast to 'array' on the
        // model, so Eloquent encodes them to JSON on save automatically. Encoding them
        // here too would double-encode: the column would store a JSON string of a JSON
        // string, which decodes back to a plain string (not an array) on every read.
        $service->update($validated);

        return to_route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return to_route('services.index')
            ->with('success', 'Service deleted successfully.');
    }

    public function storeSchedule(Request $request, Service $service)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $validated['is_active'] = true;

        $service->schedules()->create($validated);

        return back()->with('success', 'Schedule added successfully.');
    }

    public function destroySchedule(Service $service, $scheduleId)
    {
        $service->schedules()->where('id', $scheduleId)->delete();

        return back()->with('success', 'Schedule removed successfully.');
    }
}
