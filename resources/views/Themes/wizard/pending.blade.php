@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">Booking Requested</h1>
        </div>
    </section>

    <main class="py-16 bg-white">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-yellow-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            </div>

            <h2 class="text-2xl font-serif font-bold text-brand-teal mb-3">Assessment Required</h2>
            <p class="text-slate-600 mb-8">Your booking request has been received. This service requires an assessment before confirmation.</p>

            @if($booking)
                <div class="bg-brand-cream/50 border border-brand-gold/20 rounded-2xl p-6 text-left space-y-3 mb-8">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Booking Reference:</span>
                        <span class="font-bold text-brand-teal">{{ $booking->booking_id }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-brand-navy text-white rounded-2xl p-6 mb-8">
                <h3 class="font-serif font-bold text-lg mb-2">What Happens Next?</h3>
                <ul class="text-sm text-slate-300 space-y-2 text-left">
                    <li class="flex items-start gap-2">
                        <span class="text-brand-gold mt-0.5">1.</span>
                        Our team will review your booking request within 48 hours.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-brand-gold mt-0.5">2.</span>
                        We will contact you via email or phone to discuss your needs.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-brand-gold mt-0.5">3.</span>
                        Once approved, you will receive your appointment confirmation.
                    </li>
                </ul>
            </div>

            <a href="{{ route('wizard.index') }}" class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3 rounded-full font-semibold text-sm transition shadow">
                Book Another Appointment
            </a>
        </div>
    </main>
@endsection
