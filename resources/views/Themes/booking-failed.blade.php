@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <main class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-12 rounded-2xl text-center space-y-6">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-3xl">
                    ✗
                </div>

                <h1 class="text-3xl font-serif font-bold text-brand-teal">Booking Failed</h1>

                <p class="text-sm text-slate-600">
                    Unfortunately, your booking could not be completed. Please try again or contact support.
                </p>

                <a href="{{ route('services') }}" class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3 rounded-full text-sm font-semibold transition shadow">
                    Try Again
                </a>
            </div>
        </div>
    </main>
@endsection