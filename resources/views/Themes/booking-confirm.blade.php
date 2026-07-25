@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <main class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-12 rounded-2xl text-center space-y-6">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto text-3xl font-bold">
                    ✓
                </div>

                <h1 class="text-3xl font-serif font-bold text-brand-teal">Booking Confirmed!</h1>

                <p class="text-sm text-slate-600">
                    Thank you for choosing DK Healing Centre. Your journey to spiritual well-being has begun.
                </p>

                <div class="bg-white border border-brand-gold/20 p-6 rounded-2xl text-left space-y-4">
                    <h2 class="text-lg font-serif font-bold text-brand-teal">What Happens Next?</h2>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <span class="text-brand-gold mt-0.5">✓</span>
                            You will receive an immediate email confirmation with your booking details, including the service summary and any pre-session requirements.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-brand-gold mt-0.5">✓</span>
                            For Counseling sessions, a member of our team will contact you within 24 hours to schedule your 30-minute free consultation.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-brand-gold mt-0.5">✓</span>
                            You can view and manage this booking anytime on your Profile Dashboard.
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('customer.mybooking') }}" class="w-full sm:w-auto bg-brand-teal hover:bg-brand-navy text-white px-6 py-3 rounded-full text-sm font-semibold transition shadow">
                        Go to My Bookings
                    </a>
                    <a href="{{ route('wizard.index') }}" class="w-full sm:w-auto border-2 border-brand-teal text-brand-teal hover:bg-brand-teal hover:text-white px-6 py-3 rounded-full text-sm font-semibold transition">
                        Book Another Appointment
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection