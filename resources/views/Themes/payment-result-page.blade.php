
    @extends('themes.layouts.app')
    @section('content')
    <header class="relative bg-indigo-900 pt-8 pb-16 overflow-hidden">
        @include('Themes.layouts.nav') 
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="w-full max-w-lg">
            <!-- Success Content -->
            <div id="success-view" class="bg-white p-8 md:p-10 rounded-xl shadow-2xl border-t-8 border-theme-success hidden">
                <div class="flex flex-col items-center text-center">
                    <!-- Success Icon -->
                    <div class="w-20 h-20 bg-theme-success/10 text-theme-success rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-check-circle text-5xl"></i>
                    </div>
                    
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Payment Successful!</h1>
                    <p class="text-lg text-gray-600 mb-8">Your booking is **Confirmed**.</p>
                    
                    <!-- Confirmation Details -->
                    <div class="w-full space-y-3 p-4 bg-green-50 rounded-lg border border-theme-success/30 mb-8">
                        <div class="flex justify-between font-medium text-gray-800">
                            <span>Service Paid:</span>
                            <span class="text-gray-900">Personalized Rukiya Package</span>
                        </div>
                        <div class="flex justify-between font-medium text-gray-800">
                            <span>Total Amount:</span>
                            <span class="text-theme-success font-extrabold">£150.00</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 border-t pt-2 mt-2">
                            <span>Transaction ID:</span>
                            <span>TXN-5901-ABCDE12345</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col space-y-3 w-full">
                        <a href="{{ route("customer.mybooking") }}" class="px-6 py-3 bg-theme-success text-white rounded-xl font-bold shadow-md hover:bg-green-700 transition duration-150">
                            <i class="fas fa-calendar-check mr-2"></i> View Confirmed Booking
                        </a>
                        <a href="{{ route("home") }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-100 transition duration-150">
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- Failure Content -->
            <div id="fail-view" class="bg-white p-8 md:p-10 rounded-xl shadow-2xl border-t-8 border-theme-fail hidden">
                 <div class="flex flex-col items-center text-center">
                    <!-- Failure Icon -->
                    <div class="w-20 h-20 bg-theme-fail/10 text-theme-fail rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-exclamation-triangle text-5xl"></i>
                    </div>
                    
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Payment Failed</h1>
                    <p class="text-lg text-red-600 mb-6 font-semibold">We couldn't process your transaction.</p>
                    
                    <!-- Error Message -->
                    <div class="w-full space-y-3 p-4 bg-red-50 rounded-lg border border-theme-fail/30 mb-8 text-left">
                        <p class="font-bold text-red-800">Reason:</p>
                        <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                            <li><i class="fas fa-times-circle text-red-500 mr-2"></i> Card declined by the bank.</li>
                            <li><i class="fas fa-times-circle text-red-500 mr-2"></i> Incorrect CVC or expiry date.</li>
                            <li><i class="fas fa-times-circle text-red-500 mr-2"></i> Insufficient funds.</li>
                        </ul>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col space-y-3 w-full">
                        <a href="payment_checkout_secure.html" class="px-6 py-3 bg-theme-fail text-white rounded-xl font-bold shadow-md hover:bg-red-700 transition duration-150">
                            <i class="fas fa-redo mr-2"></i> Try Payment Again
                        </a>
                        <a href="contact.html" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-100 transition duration-150">
                            <i class="fas fa-headset mr-2"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </main>

    @endsection

     @push('css')
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
     @endpush

     @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const status = urlParams.get('status');
                
                const successView = document.getElementById('success-view');
                const failView = document.getElementById('fail-view');

                // Default to failure if status is missing or not 'success'
                if (status === 'success') {
                    successView.classList.remove('hidden');
                    document.title = "Payment Confirmed";
                } else {
                    failView.classList.remove('hidden');
                    document.title = "Payment Failed";
                }
            });
        </script>
     @endpush
