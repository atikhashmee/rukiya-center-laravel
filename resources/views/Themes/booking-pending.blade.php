@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <main class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-12 rounded-2xl text-center space-y-6">
                <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto text-3xl">
                    ⏳
                </div>

                <h1 class="text-3xl font-serif font-bold text-brand-teal">Booking Received, Review Required</h1>

                <p class="text-sm text-slate-600">
                    Your request has been successfully submitted! It is now under specialist review.
                </p>

                <div class="bg-white border border-brand-gold/20 p-6 rounded-2xl text-left space-y-4">
                    <h2 class="text-lg font-serif font-bold text-brand-teal">Important Next Steps</h2>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <span class="text-brand-gold mt-0.5">✓</span>
                            A spiritual guru will personally assess your request and the information you provided to determine the complexity and precise requirements for Deep Healing.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-brand-gold mt-0.5">✓</span>
                            We will contact you via email within 48 hours with a personalized follow-up and the finalized price/assessment for your session.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-brand-gold mt-0.5">✓</span>
                            No payment is due yet. You will receive a secure payment link in the follow-up email.
                        </li>
                    </ul>
                </div>

                <p class="text-sm text-slate-500">
                    Thank you for your patience as we prepare your personalized healing path.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('customer.mybooking') }}" class="w-full sm:w-auto bg-brand-teal hover:bg-brand-navy text-white px-6 py-3 rounded-full text-sm font-semibold transition shadow">
                        View Pending Booking
                    </a>
                    <a href="{{ route('home') }}" class="w-full sm:w-auto border-2 border-brand-teal text-brand-teal hover:bg-brand-teal hover:text-white px-6 py-3 rounded-full text-sm font-semibold transition">
                        Return to Homepage
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const serviceName = "Deep Rukiya Healing";
            const serviceNameElement = document.querySelector('p.text-sm.text-slate-600 strong');
            if (serviceNameElement) { serviceNameElement.textContent = serviceName; }
        });
    </script>
@endpush