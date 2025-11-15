<?php

namespace App\Http\Controllers;

use App\ServiceType;
use Inertia\Inertia;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\ServiceStoreRequest;

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
    public function store(ServiceStoreRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $service = Service::create($validatedData);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create service option: ' . $e->getMessage());
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
