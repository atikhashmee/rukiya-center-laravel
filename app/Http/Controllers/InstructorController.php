<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $query = Instructor::withCount('services');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $instructors = $query->orderBy('name')->paginate(12)->withQueryString();

        return Inertia::render('instructors/index', [
            'instructors' => $instructors,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create()
    {
        $services = Service::orderBy('category')->orderBy('title')->get();

        return Inertia::render('instructors/create', [
            'services' => $services,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:1000',
            'languages' => 'nullable|string|max:500',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
        ]);

        $serviceIds = $validated['service_ids'];
        unset($validated['service_ids']);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['languages'] = $validated['languages']
            ? array_map('trim', explode(',', $validated['languages']))
            : null;

        $instructor = Instructor::create($validated);
        $instructor->services()->sync($serviceIds);

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor created successfully.');
    }

    public function edit(Instructor $instructor)
    {
        $instructor->load('services', 'schedules');
        $services = Service::orderBy('category')->orderBy('title')->get();

        return Inertia::render('instructors/edit', [
            'instructor' => $instructor,
            'services' => $services,
        ]);
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:1000',
            'languages' => 'nullable|string|max:500',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
        ]);

        $serviceIds = $validated['service_ids'];
        unset($validated['service_ids']);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['languages'] = $validated['languages']
            ? array_map('trim', explode(',', $validated['languages']))
            : null;

        $instructor->update($validated);
        $instructor->services()->sync($serviceIds);

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor updated successfully.');
    }

    public function destroy(Instructor $instructor)
    {
        $instructor->delete();

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor deleted successfully.');
    }

    public function storeSchedule(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $validated['is_active'] = true;

        $instructor->schedules()->create($validated);

        return back()->with('success', 'Schedule added successfully.');
    }

    public function destroySchedule(Instructor $instructor, $scheduleId)
    {
        $instructor->schedules()->where('id', $scheduleId)->delete();

        return back()->with('success', 'Schedule removed successfully.');
    }
}
