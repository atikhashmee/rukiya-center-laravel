<x-mail::message>
# Your Service Booking Confirmation

Hi {{ $customer->name }},

Thank you for booking a service with **{{ config('app.name') }}**! We're excited to confirm your appointment.

Here are the details of your booking:

-   **Service Booked:** {{ $service->title }}
-   **Date:** {{ $booking->created_at->format('F d, Y') }}
-   **Time:** {{ $booking->created_at->format('h:i A') }}
@if(isset($service->location)) {{-- Only show location if applicable --}}
-   **Location/Address:** {{ $service->location }}
@endif
-   **Booking Reference ID:** #{{ $booking->booking_id }}
-   **Total Cost:** {{ number_format($booking->service_price, 2) }}

---

**What to expect next:**

_Our service provider will arrive promptly at the scheduled time. You will receive a reminder email 24 hours before your appointment._
{{-- You can customize the above line based on your specific process --}}

If you need to make any changes to your booking or have any questions, please don't hesitate to contact us.

<x-mail::button :url="route('customer.mybooking')">
View Your Booking Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}

@if(config('app.phone'))
<x-mail::panel>
    Need help? Call us at {{ config('app.phone') }}
</x-mail::panel>
@endif

<x-mail::subcopy>
    This is an automated email, please do not reply directly. You can manage your bookings on our website: <a href="{{ url('/') }}">{{ config('app.name') }}</a>
</x-mail::subcopy>

</x-mail::message>