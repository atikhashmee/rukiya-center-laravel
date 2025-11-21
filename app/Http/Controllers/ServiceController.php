<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceStoreRequest;
use App\Models\Service;
use App\ServiceType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('order', 'asc')->paginate(10);

        return Inertia::render('services/index', [
            'services' => $services,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceTypes = ServiceType::cases();

        return Inertia::render('services/create', [
            'serviceTypes' => $serviceTypes,
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
        return Inertia::render('Services/Show', [
            'service' => $service,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $serviceTypes = ServiceType::cases();

        $serviceData = $service->toArray();
        $serviceData['features'] = is_string($service->features)
            ? json_decode($service->features, true)
            : $service->features;
        $serviceData['required_form_fields'] = is_string($service->required_form_fields)
            ? json_decode($service->required_form_fields, true)
            : $service->required_form_fields;

        return Inertia::render('services/edit', [
            'service' => $serviceData,
            'serviceTypes' => $serviceTypes,
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
            'category' => 'required|string|max:255',
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

        // Encode arrays to JSON for storage
        if (isset($validated['features'])) {
            $validated['features'] = json_encode($validated['features']);
        }
        if (isset($validated['required_form_fields'])) {
            $validated['required_form_fields'] = json_encode($validated['required_form_fields']);
        }

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
}
