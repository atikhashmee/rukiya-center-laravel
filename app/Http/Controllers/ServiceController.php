<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => ['required', 'string', 'max:255'],
            'service_type' => ['required', new Enum(ServiceType::class)],
            'price' => ['required', 'numeric', 'min:0'],
            'start_date_and_time' => ['required', 'date'],
            'end_date_and_time' => ['required', 'date', 'after:start_date_and_time'],
            'description' => ['nullable', 'string'],
            'duration' => ['nullable', 'integer', 'min:0'],
        ]);
        Service::create($validated);

        return to_route('services.index')
            ->with('success', 'Service created successfully.');
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

        return Inertia::render('Services/Edit', [
            'service' => $service,
            'serviceTypes' => $serviceTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $service->update($request->validated());

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
