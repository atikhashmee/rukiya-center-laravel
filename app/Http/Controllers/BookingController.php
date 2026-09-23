<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Mail\ServiceBooked;
use App\Models\Booking;
use App\Models\Instructor;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    //
    // Define the possible statuses based on your migration comments
    protected array $bookingStatuses = ['new', 'confirmed', 'in_progress', 'completed', 'cancelled'];
    protected array $paymentStatuses = ['pending', 'paid', 'failed', 'assessment_required'];

    /**
     * Display a listing of the resource (Index).
     */
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'service:id,title', 'instructor:id,name']);

        // Instructor accounts only ever see their own bookings.
        if ($instructorId = $request->user()?->instructor_id) {
            $query->where('instructor_id', $instructorId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('booking_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('service_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->booking_status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('instructor')) {
            $request->instructor === 'unassigned'
                ? $query->whereNull('instructor_id')
                : $query->where('instructor_id', $request->instructor);
        }

        $bookings = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('bookings/index', [
            'bookings' => $bookings,
            'bookingStatuses' => $this->bookingStatuses,
            'paymentStatuses' => $this->paymentStatuses,
            'instructors' => $this->assignableInstructors(),
            'canAssignInstructor' => ! $request->user()?->instructor_id,
            'filters' => $request->only(['search', 'booking_status', 'payment_status', 'instructor']),
        ]);
    }

    /**
     * Active instructors with the services they cover, so the admin screens can offer
     * only the people who actually provide the booked service.
     */
    private function assignableInstructors()
    {
        return Instructor::where('is_active', true)
            ->with('services:id')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($instructor) => [
                'id' => $instructor->id,
                'name' => $instructor->name,
                'service_ids' => $instructor->services->pluck('id'),
            ]);
    }

    /**
     * Assign (or clear) the instructor on a booking. Used for bookings made with
     * "Any Available", which are saved without an instructor when nobody was free.
     */
    public function assignInstructor(Request $request, Booking $booking)
    {
        // An instructor account manages its own bookings but cannot hand them to someone else.
        abort_if((bool) $request->user()?->instructor_id, 403);

        $validated = $request->validate([
            'instructor_id' => 'nullable|exists:instructors,id',
        ]);

        $booking->update(['instructor_id' => $validated['instructor_id'] ?: null]);

        $name = $booking->fresh()->instructor?->name;

        return back()->with('success', $name
            ? "Booking **{$booking->booking_id}** assigned to **{$name}**."
            : "Instructor cleared for booking **{$booking->booking_id}**.");
    }

    /** An instructor account may only touch bookings assigned to its own instructor record. */
    private function authorizeBooking(Booking $booking): void
    {
        $instructorId = auth()->user()?->instructor_id;

        abort_if($instructorId && $booking->instructor_id !== $instructorId, 403);
    }

    /**
     * Display every detail of a booking: client, guardian, customer account and payments.
     */
    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);

        // order_type has been stored both as "App\Models\Booking" and with its backslashes stripped.
        $payments = Payment::where('order_id', $booking->id)
            ->where('order_type', 'like', '%Booking')
            ->latest()
            ->get(['id', 'payment_intent_id', 'amount', 'currency', 'status', 'created_at']);

        return Inertia::render('bookings/show', [
            'booking' => $booking->load(['customer', 'service', 'instructor']),
            'payments' => $payments,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $this->authorizeBooking($booking);

        return Inertia::render('bookings/edit', [
            'booking' => $booking,
            'services' => Service::orderBy('title')->get(['id', 'title']),
            'instructors' => $this->assignableInstructors(),
            'bookingStatuses' => $this->bookingStatuses,
            'paymentStatuses' => $this->paymentStatuses,
        ]);
    }

    /**
     * Update the specified resource in storage (Edit/Update).
     * NOTE: You should create a BookingUpdateRequest class for proper validation.
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'instructor_id' => 'nullable|exists:instructors,id',
            'booking_date' => 'nullable|date',
            'booking_time' => 'nullable|date_format:H:i,H:i:s',
            'booking_status' => 'required|in:' . implode(',', $this->bookingStatuses),
            'payment_status' => 'required|in:' . implode(',', $this->paymentStatuses),
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_country' => 'nullable|string|max:10',
            'phone_number' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:20',
            'age' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:100',
            'ethnic_origin' => 'nullable|string|max:100',
            'is_first_appointment' => 'nullable|string|max:50',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'string|max:255',
            'symptoms_other' => 'nullable|string',
            'inquiry_description' => 'nullable|string',
            'found_via' => 'nullable|array',
            'found_via.*' => 'string|max:255',
            'consent_updates' => 'boolean',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:100',
            'guardian_gender' => 'nullable|string|max:20',
            'guardian_phone' => 'nullable|string|max:20',
            'price_type' => 'required|in:' . implode(',', ['FIXED', 'DONATION', 'FREE', 'RESERVATION']),
            'service_price' => 'required|numeric|min:0',
            'donation_addon' => 'nullable|numeric|min:0',
        ]);

        $booking->update($validated);

        return redirect()->route('bookings.index')
            ->with('success', "Booking **{$booking->booking_id}** updated successfully.");
    }

    // --- CUSTOM ACTIONS ---

    /**
     * Update the booking_status (e.g., 'new', 'confirmed').
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $request->validate(['booking_status' => 'required|in:' . implode(',', $this->bookingStatuses)]);

        $booking->update(['booking_status' => $request->booking_status]);

        return back()
            ->with('success', "Booking Status for **{$booking->booking_id}** changed to **{$booking->booking_status}**.");
    }

    /**
     * Send the booking confirmation email to the booking's email address.
     */
    public function sendOrderEmail(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $recipient = $booking->email;

        if (!$recipient) {
            return redirect()->back()->with('error', "Cannot send email: Booking has no email address.");
        }

        try {
            Mail::to($recipient)->send(new ServiceBooked($booking));

            return redirect()->back()->with('success', "Service detail email sent successfully to **{$recipient}**.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Failed to send email. Error: {$e->getMessage()}");
        }
    }
}
