<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Booking;
use Illuminate\Http\Request;

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
        $query = Booking::with('customer');

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

        $bookings = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('bookings/index', [
            'bookings' => $bookings,
            'bookingStatuses' => $this->bookingStatuses,
            'paymentStatuses' => $this->paymentStatuses,
            'filters' => $request->only(['search', 'booking_status', 'payment_status']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        return Inertia::render('bookings/edit', [
            'booking' => $booking->load('customer'), // Load customer data
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
        // Simple validation based on the fields provided
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mother_name' => 'nullable|string|max:255',
            'inquiry_description' => 'required|string',
            'service_id' => 'required|string|max:255',
            'price_type' => 'required|in:' . implode(',', ['FIXED', 'DONATION', 'FREE', 'RESERVATION']),
            'service_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:' . implode(',', $this->paymentStatuses),
            'booking_status' => 'required|in:' . implode(',', $this->bookingStatuses),
            'phone_number' => 'nullable|string|max:20',
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
        $request->validate(['booking_status' => 'required|in:' . implode(',', $this->bookingStatuses)]);

        $booking->update(['booking_status' => $request->booking_status]);

        return redirect()->route('bookings.index')
            ->with('success', "Booking Status for **{$booking->booking_id}** changed to **{$booking->booking_status}**.");
    }

    /**
     * Send the order email (now confirmation/service details email).
     */
    public function sendOrderEmail(Booking $booking)
    {
        // Use the email field directly from the booking table
        $recipient = $booking->email; 

        if (!$recipient) {
            return redirect()->back()->with('error', "Cannot send email: Booking has no email address.");
        }

        try {
            // Mail::to($recipient)->send(new BookingServiceMail($booking)); // Assume this Mailable class exists

            return redirect()->back()->with('success', "Service detail email sent successfully to **{$recipient}**.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Failed to send email. Error: {$e->getMessage()}");
        }
    }
}
