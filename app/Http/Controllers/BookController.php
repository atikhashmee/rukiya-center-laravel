<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index($service, Request $request)
    {
        $service = Service::where('id', $service)->first();
        if (! $service) {
            abort(404);
        }

        return view('Themes.service-book-preview', [
            'service' => $service,
        ]);
    }

    public function store(BookingRequest $request)
    {
        // Use a database transaction to ensure data integrity
        return DB::transaction(function () use ($request) {

            // 1. Fetch Service Details SECURELY from the database
            // We use the service_id passed from the form to retrieve the actual price,
            // NOT a price field submitted by the client-side form.
            $serviceOption = Service::where('id', $request->service_id)->firstOrFail();

            // 2. Determine Final Price and Status based on price_type
            $finalPrice = 0.00;
            $paymentStatus = 'pending';
            $bookingStatus = 'new';

            if ($serviceOption->price_type === 'FIXED') {
                $finalPrice = $serviceOption->price_value; // Assuming 'price_value' holds the fixed price
            } elseif ($serviceOption->price_type === 'DONATION') {
                // For donation, you might accept a user-submitted donation amount.
                // For this example, we'll set it to the minimum required donation (if applicable).
                $finalPrice = $serviceOption->min_donation ?? 0.00;
            } elseif ($serviceOption->price_type === 'FREE') {
                $finalPrice = 0.00;
                $paymentStatus = 'paid'; // Automatically 'paid' since it's free
            } elseif ($serviceOption->price_type === 'RESERVATION') {
                // For deep healing, price is assessed later
                $paymentStatus = 'assessment_required';
            }

            // 3. Create the Booking Record
            $booking = Booking::create([
                'customer_id' => $request->customer_id, // Nullable, populated if authenticated
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'mother_name' => $request->mother_name,
                'inquiry_description' => $request->inquiry_description,
                'service_id' => $serviceOption->service_id,
                'price_type' => $serviceOption->price_type,
                'service_price' => $finalPrice,
                'payment_status' => $paymentStatus,
                'booking_status' => $bookingStatus,
            ]);

            // 4. Handle Redirection based on payment status
            if ($paymentStatus === 'pending') {
                // Redirect to a payment gateway (e.g., Stripe Checkout)
                // You would typically generate a payment link here using the $booking object.
                return redirect()->route('payment.checkout', ['booking' => $booking->id])
                    ->with('success', 'Booking created. Please complete your payment.');

            } elseif ($paymentStatus === 'assessment_required') {
                // Redirect to a specific page informing the user they will be contacted
                return redirect()->route('booking.pending')
                    ->with('success', 'Your assessment request has been submitted. We will contact you shortly.');
            }

            // Default for FREE services
            return redirect()->route('booking.confirmed')
                ->with('success', 'Your free session is booked! Check your email for confirmation.');
        });

    }

    public function bookConfirm()
    {
        return view('Themes.booking-confirm');
    }

    public function bookFailed()
    {
        return view('Themes.booking-failed');
    }

    public function bookPending()
    {
        return view('Themes.booking-pending');
    }
}
