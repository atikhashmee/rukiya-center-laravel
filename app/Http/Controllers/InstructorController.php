<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $services = Service::with('category')->orderBy('title')->get();

        return Inertia::render('instructors/create', [
            'services' => $services,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'region' => \App\Support\Region::rule(),
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:1000',
            'languages' => 'nullable|string|max:500',
            'experience' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'appointment_type' => 'nullable|string|max:100',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_photo' => 'nullable|boolean',
        ]);

        $serviceIds = $validated['service_ids'];
        unset($validated['service_ids']);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['languages'] = ! empty($validated['languages'])
            ? array_map('trim', explode(',', $validated['languages']))
            : null;

        unset($validated['remove_photo']);
        $validated['photo'] = $request->hasFile('photo')
            ? Storage::url($request->file('photo')->store('instructors', 'public'))
            : null;

        $instructor = Instructor::create($validated);
        $instructor->services()->sync($serviceIds);

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor created successfully.');
    }

    public function edit(Instructor $instructor)
    {
        $instructor->load('services');
        $services = Service::with('category')->orderBy('title')->get();

        return Inertia::render('instructors/edit', [
            'instructor' => $instructor,
            'services' => $services,
        ]);
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'region' => \App\Support\Region::rule(),
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:1000',
            'languages' => 'nullable|string|max:500',
            'experience' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'appointment_type' => 'nullable|string|max:100',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_photo' => 'nullable|boolean',
        ]);

        $serviceIds = $validated['service_ids'];
        unset($validated['service_ids']);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['languages'] = ! empty($validated['languages'])
            ? array_map('trim', explode(',', $validated['languages']))
            : null;

        $removePhoto = (bool) ($validated['remove_photo'] ?? false);
        unset($validated['remove_photo']);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($instructor->photo);
            $validated['photo'] = Storage::url($request->file('photo')->store('instructors', 'public'));
        } elseif ($removePhoto) {
            $this->deletePhoto($instructor->photo);
            $validated['photo'] = null;
        } else {
            unset($validated['photo']);   // no file sent: leave the current one alone
        }

        $instructor->update($validated);
        $instructor->services()->sync($serviceIds);

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor updated successfully.');
    }

    /** Photos are stored as public URLs ("/storage/instructors/x.jpg"), matching the product images. */
    private function deletePhoto(?string $url): void
    {
        if (! $url) {
            return;
        }

        // Storage::url('/') returns "/storage//", so strip the prefix explicitly.
        Storage::disk('public')->delete(ltrim(Str::after($url, '/storage/'), '/'));
    }

    public function destroy(Instructor $instructor)
    {
        $this->deletePhoto($instructor->photo);
        $instructor->delete();

        return redirect()->route('instructors.index')
            ->with('success', 'Instructor deleted successfully.');
    }
}
