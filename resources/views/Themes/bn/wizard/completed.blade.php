@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">Booking Confirmed!</h1>
        </div>
    </section>

    <main class="py-16 bg-white">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </div>

            <h2 class="text-2xl font-serif font-bold text-brand-teal mb-3">Thank You, {{ $booking->full_name }}!</h2>
            <p class="text-slate-600 mb-8">Your booking has been confirmed. A confirmation email has been sent to <strong>{{ $booking->email }}</strong>.</p>

            @if($booking)
                <div class="bg-brand-cream/50 border border-brand-gold/20 rounded-2xl p-6 text-left space-y-3 mb-8">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Booking Reference:</span>
                        <span class="font-bold text-brand-teal">{{ $booking->booking_id }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Service:</span>
                        <span class="font-semibold text-brand-teal">{{ $booking->service->title ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Instructor:</span>
                        <span class="font-semibold text-brand-teal">{{ $booking->instructor->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Date:</span>
                        <span class="font-semibold text-brand-teal">{{ \Carbon\Carbon::parse($booking->booking_date)->format('l, M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Time:</span>
                        <span class="font-semibold text-brand-teal">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-brand-navy text-white rounded-2xl p-6 mb-8">
                <h3 class="font-serif font-bold text-lg mb-2">What Happens Next?</h3>
                <ul class="text-sm text-slate-300 space-y-2 text-left">
                    <li class="flex items-start gap-2">
                        <span class="text-brand-gold mt-0.5">1.</span>
                        You will receive a confirmation email with your booking details.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-brand-gold mt-0.5">2.</span>
                        Our team will review your inquiry and may contact you for further information.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-brand-gold mt-0.5">3.</span>
                        Session details and instructions will be sent to you prior to your appointment.
                    </li>
                </ul>
            </div>

            <a href="{{ route('wizard.index') }}" class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3 rounded-full font-semibold text-sm transition shadow">
                Book Another Appointment
            </a>
        </div>
    </main>
@endsection
