    @extends('themes.layouts.app')

    @section('content')

    <header class="bg-indigo-700 py-16 text-white text-center">
        @include('themes.layouts.nav')
    </header>
    
    <!-- Main Content: Confirmation Message -->
    <main class="py-20 md:py-28 px-4 sm:px-8">
        <div class="max-w-3xl mx-auto bg-white p-8 md:p-12 rounded-xl shadow-3xl text-center border-t-8 border-green-500">
            
            <i class="fas fa-check-circle text-green-500 text-7xl mb-6 animate-pulse"></i>

            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                Booking Confirmed!
            </h1>

            <p class="text-xl text-gray-600 mb-8">
                Thank you for choosing DK Healing Center. Your journey to spiritual well-being has begun.
            </p>
            
            <div class="bg-green-50 p-6 rounded-lg border border-green-200 mb-10 text-left">
                <h2 class="text-2xl font-bold text-green-700 mb-3 flex items-center">
                    <i class="fas fa-envelope-open-text mr-3"></i>
                    What Happens Next?
                </h2>
                <ul class="space-y-4 text-gray-700 text-lg">
                    <li class="flex items-start">
                        <i class="fas fa-clock text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                        You will receive an **immediate email confirmation** with your booking details, including the service summary and any pre-session requirements.
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-calendar-alt text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                        For **Counseling** sessions, a member of our team will contact you within **24 hours** to schedule your **30-minute free consultation**.
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-user-circle text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                        You can view and manage this booking anytime on your **Profile Dashboard**.
                    </li>
                </ul>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route("customer.mybooking") }}" class="w-full sm:w-auto px-8 py-3 bg-theme-primary text-white font-bold rounded-lg hover:bg-indigo-700 transition duration-300 shadow-md transform hover:scale-105">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Go to My Bookings
                </a>
                <a href="{{ route("services") }}" class="w-full sm:w-auto px-8 py-3 bg-white text-theme-primary border-2 border-theme-primary font-bold rounded-lg hover:bg-theme-accent transition duration-300 shadow-md transform hover:scale-105">
                    <i class="fas fa-home mr-2"></i>
                    Explore More Services
                </a>
            </div>
        </div>
    </main>
    @endsection
