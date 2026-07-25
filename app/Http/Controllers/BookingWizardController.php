<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Instructor;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookingWizardController extends Controller
{
    public function index()
    {
        $categories = Service::select('category')->distinct()->pluck('category');

        return view('Themes.wizard.category', compact('categories'));
    }

    public function selectService($category)
    {
        $services = Service::where('category', $category)->orderBy('title')->get();

        if ($services->isEmpty()) {
            return redirect()->route('wizard.index')->with('error', 'No services found in this category.');
        }

        return view('Themes.wizard.service', compact('services', 'category'));
    }

    public function selectInstructor($serviceId)
    {
        $service = Service::findOrFail($serviceId);

        $instructors = Instructor::where('is_active', true)
            ->whereHas('services', fn ($q) => $q->where('services.id', $service->id))
            ->with('schedules')
            ->get();

        if ($instructors->isEmpty()) {
            return back()->with('error', 'No instructors available for this service yet. Please try another service.');
        }

        return view('Themes.wizard.instructor', compact('service', 'instructors'));
    }

    public function selectSchedule($serviceId, $instructorId)
    {
        $service = Service::findOrFail($serviceId);
        $anyInstructor = $instructorId === 'any';

        if ($anyInstructor) {
            $instructors = Instructor::where('is_active', true)
                ->whereHas('services', fn ($q) => $q->where('services.id', $service->id))
                ->with('schedules')
                ->get();

            $allSchedules = $instructors->pluck('schedules')->flatten()->where('is_active', true);

            $instructor = (object) ['id' => null, 'name' => 'Any Available', 'schedules' => $allSchedules];
            $instructorIds = $instructors->pluck('id')->toArray();
        } else {
            $instructor = Instructor::with('schedules')->findOrFail($instructorId);
            $allSchedules = $instructor->schedules->where('is_active', true);
            $instructorIds = [$instructorId];
        }

        $today = now()->startOfDay();
        $dates = collect();
        for ($i = 0; $i < 14; $i++) {
            $date = $today->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek;
            $daySchedules = $allSchedules->where('day_of_week', $dayOfWeek);

            if ($daySchedules->isNotEmpty()) {
                $mergedSlots = $daySchedules->map(function ($s) use ($date) {
                    return [
                        'start_time' => $s->start_time,
                        'end_time' => $s->end_time,
                        'display' => $this->formatTime($s->start_time) . ' — ' . $this->formatTime($s->end_time),
                    ];
                })->unique('start_time')->values();

                $dates->push([
                    'date' => $date->toDateString(),
                    'display' => $date->format('l, M j'),
                    'day_name' => $date->format('l'),
                    'slots' => $mergedSlots,
                ]);
            }
        }

        $bookedSlots = Booking::whereIn('instructor_id', $instructorIds)
            ->whereIn('booking_date', $dates->pluck('date')->toArray())
            ->whereNotIn('booking_status', ['cancelled'])
            ->get()
            ->groupBy('booking_date')
            ->map(fn ($bookings) => $bookings->pluck('booking_time')->map(fn ($t) => substr($t, 0, 5))->toArray());

        return view('Themes.wizard.schedule', compact('service', 'instructor', 'dates', 'bookedSlots', 'anyInstructor'));
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'instructor_id' => 'required',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
        ]);

        $service = Service::findOrFail($request->service_id);

        if ($request->instructor_id === 'any') {
            $instructor = (object) ['id' => 'any', 'name' => 'Any Available'];
        } else {
            $instructor = Instructor::findOrFail($request->instructor_id);
        }

        return view('Themes.wizard.confirm', compact('service', 'instructor'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'instructor_id' => 'required',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:50',
            'phone_country' => 'required|string|size:2',
            'gender' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:100',
            'ethnic_origin' => 'nullable|string|max:255',
            'age' => 'nullable|string|max:20',
            'is_first_appointment' => 'nullable|string|max:50',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'string|max:255',
            'symptoms_other' => 'nullable|string|max:1000',
            'found_via' => 'nullable|array',
            'found_via.*' => 'string|max:255',
            'consent_updates' => 'nullable|boolean',
            'guardian_gender' => 'nullable|string|max:50',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:100',
            'guardian_phone' => 'nullable|string|max:50',
            'inquiry_description' => 'nullable|string|max:2000',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        if ($validated['instructor_id'] === 'any') {
            $dayOfWeek = \Carbon\Carbon::parse($validated['booking_date'])->dayOfWeek;
            $bookingTime = substr($validated['booking_time'], 0, 5);

            $matchingInstructor = Instructor::where('is_active', true)
                ->whereHas('services', fn ($q) => $q->where('services.id', $service->id))
                ->whereHas('schedules', function ($q) use ($dayOfWeek, $bookingTime) {
                    $q->where('day_of_week', $dayOfWeek)
                      ->where('is_active', true)
                      ->where('start_time', '<=', $bookingTime)
                      ->where('end_time', '>', $bookingTime);
                })
                ->whereDoesntHave('bookings', function ($q) use ($validated) {
                    $q->where('booking_date', $validated['booking_date'])
                      ->where('booking_time', $validated['booking_time'])
                      ->whereNotIn('booking_status', ['cancelled']);
                })
                ->first();

            $validated['instructor_id'] = $matchingInstructor?->id;
        }

        $finalPrice = 0;
        $paymentStatus = 'paid';
        $bookingStatus = 'new';

        switch ($service->price_type) {
            case 'FIXED':
                $finalPrice = $service->price_value;
                $paymentStatus = 'pending';
                break;
            case 'DONATION':
                $finalPrice = $service->min_donation;
                $paymentStatus = 'pending';
                break;
            case 'RESERVATION':
                $finalPrice = 0;
                $paymentStatus = 'assessment_required';
                break;
            case 'FREE':
            default:
                $finalPrice = 0;
                $paymentStatus = 'paid';
                $bookingStatus = 'confirmed';
                break;
        }

        $validated['consent_updates'] = $request->boolean('consent_updates');

        $booking = Booking::create([
            'booking_id' => 'BKG-' . time(),
            'customer_id' => null,
            'service_id' => $validated['service_id'],
            'instructor_id' => $validated['instructor_id'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'full_name' => $fullName,
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'phone_country' => $validated['phone_country'],
            'gender' => $validated['gender'] ?? null,
            'language' => $validated['language'] ?? null,
            'ethnic_origin' => $validated['ethnic_origin'] ?? null,
            'age' => $validated['age'] ?? null,
            'is_first_appointment' => $validated['is_first_appointment'] ?? null,
            'symptoms' => $validated['symptoms'] ?? null,
            'symptoms_other' => $validated['symptoms_other'] ?? null,
            'found_via' => $validated['found_via'] ?? null,
            'consent_updates' => $validated['consent_updates'],
            'guardian_gender' => $validated['guardian_gender'] ?? null,
            'guardian_name' => $validated['guardian_name'] ?? null,
            'guardian_relationship' => $validated['guardian_relationship'] ?? null,
            'guardian_phone' => $validated['guardian_phone'] ?? null,
            'inquiry_description' => $validated['inquiry_description'] ?? null,
            'service_price' => $finalPrice,
            'price_type' => $service->price_type,
            'payment_status' => $paymentStatus,
            'booking_status' => $bookingStatus,
        ]);

        try {
            Mail::to($booking->email)->send(new \App\Mail\ServiceBooked($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send booking email: ' . $e->getMessage());
        }

        session()->flash('booking_id', $booking->id);

        if ($paymentStatus === 'pending') {
            return redirect()->route('wizard.payment.checkout', ['booking' => $booking->id])
                ->with('success', 'Booking created! Please complete payment.');
        }

        if ($paymentStatus === 'assessment_required') {
            return redirect()->route('wizard.pending', ['booking_id' => $booking->id]);
        }

        return redirect()->route('wizard.confirmation', ['booking_id' => $booking->id]);
    }

    public function confirmation(Request $request)
    {
        $booking = Booking::findOrFail($request->booking_id);
        return view('Themes.wizard.completed', compact('booking'));
    }

    public function pending(Request $request)
    {
        $booking = Booking::findOrFail($request->booking_id);
        return view('Themes.wizard.pending', compact('booking'));
    }

    private function formatTime($time): string
    {
        return \Carbon\Carbon::parse($time)->format('g:i A');
    }
}
