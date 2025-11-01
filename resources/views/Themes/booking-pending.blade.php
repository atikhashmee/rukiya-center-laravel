
    @extends('themes.layouts.app')

    @section('content')
 <header class="bg-indigo-700 py-16 text-white text-center">
    @include('themes.layouts.nav')
 </header>

    
    <!-- Main Content: Pending Message -->
    <main class="py-20 md:py-28 px-4 sm:px-8">
        <div class="max-w-3xl mx-auto bg-white p-8 md:p-12 rounded-xl shadow-3xl text-center border-t-8 border-yellow-500">
            
            <i class="fas fa-hourglass-half text-yellow-500 text-7xl mb-6 animate-pulse"></i>

            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                Booking Received, Review Required
            </h1>

            <p class="text-xl text-gray-600 mb-8">
                Your request for **[Service Name]** has been successfully submitted! It is now under specialist review.
            </p>
            
            <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200 mb-10 text-left">
                <h2 class="text-2xl font-bold text-yellow-700 mb-3 flex items-center">
                    <i class="fas fa-search-dollar mr-3"></i>
                    Important Next Steps
                </h2>
                <ul class="space-y-4 text-gray-700 text-lg">
                    <li class="flex items-start">
                        <i class="fas fa-user-shield text-yellow-500 mr-3 mt-1 flex-shrink-0"></i>
                        A spiritual guru will personally **assess your request** and the information you provided to determine the complexity and precise requirements for **Deep Healing** (or similar service).
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-envelope text-yellow-500 mr-3 mt-1 flex-shrink-0"></i>
                        We will contact you via email within **48 hours** with a personalized follow-up and the **finalized price/assessment** for your session.
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-credit-card text-yellow-500 mr-3 mt-1 flex-shrink-0"></i>
                        **No payment is due yet.** You will receive a secure payment link in the follow-up email.
                    </li>
                </ul>
            </div>
            
            <p class="text-lg text-gray-700 mb-6">
                Thank you for your patience as we prepare your personalized healing path.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route("customer.mybooking") }}" class="w-full sm:w-auto px-8 py-3 bg-theme-primary text-white font-bold rounded-lg hover:bg-indigo-700 transition duration-300 shadow-md transform hover:scale-105">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    View Pending Booking
                </a>
                <a href="{{ route("home") }}" class="w-full sm:w-auto px-8 py-3 bg-white text-theme-primary border-2 border-theme-primary font-bold rounded-lg hover:bg-theme-accent transition duration-300 shadow-md transform hover:scale-105">
                    <i class="fas fa-home mr-2"></i>
                    Return to Homepage
                </a>
            </div>
        </div>
    </main>

    @endsection 

@push('scripts')
     <script>
        document.addEventListener('DOMContentLoaded', () => {
            // In a real application, the service name would be passed via a URL parameter or session data.
            // For now, we simulate a service name here.
            const serviceName = "Deep Rukiya Healing";
            const serviceNameElement = document.querySelector('p.text-xl.text-gray-600 strong');
            if (serviceNameElement) {
                serviceNameElement.textContent = serviceName;
            }
        });
    </script>
@endpush
