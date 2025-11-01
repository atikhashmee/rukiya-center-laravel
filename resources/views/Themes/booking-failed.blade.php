@extends('themes.layouts.layout')

@section('content')
    <main>
      <div class="flex items-center justify-center min-h-screen p-6">
        <!-- Failure Card -->
        <div class="bg-white p-8 rounded-lg shadow-xl max-w-sm text-center">
            <!-- Cross Icon Container -->
            <div class="mx-auto bg-red-100 rounded-full h-24 w-24 flex items-center justify-center">
                <svg class="h-16 w-16 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            
            <!-- Failure Text -->
            <h1 class="text-3xl font-bold text-gray-800 mt-6">Booking Failed</h1>
            <p class="text-gray-600 mt-2">
                Unfortunately, your booking could not be completed. Please try again or contact support.
            </p>
            
            <!-- Action Button -->
            <a href="#" class="mt-6 inline-block px-6 py-3 text-sm font-semibold text-white bg-red-600 rounded-md shadow-md hover:bg-red-700 transition-colors">
                Try Again
            </a>
        </div>
    </div>
    </main>
@endsection