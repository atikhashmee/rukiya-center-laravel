    @extends('themes.layouts.app')

    @section('content')
     <header class="relative bg-indigo-900 pt-8 pb-16 overflow-hidden">
        @include('themes.layouts.nav') 
     </header>
    <main class="py-12 md:py-16 px-4 sm:px-8 bg-gradient-to-br from-indigo-50 via-gray-100 to-white">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-extrabold text-gray-900 text-center mb-12 leading-tight">
                Secure Your Path to <span class="text-theme-primary">Inner Harmony</span>
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- LEFT COLUMN: Professional Order Summary/Cart --><div class="lg:col-span-1 space-y-8">
                    <div class="bg-white p-8 rounded-xl shadow-3xl border-t-8 border-theme-gold transform hover:-translate-y-1 transition duration-300 relative overflow-hidden">
                        <!-- Background graphic --><div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-theme-primary opacity-10 rounded-full mix-blend-multiply filter blur-sm"></div>
                        <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-32 h-32 bg-theme-gold opacity-10 rounded-full mix-blend-multiply filter blur-sm"></div>

                        <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-shopping-basket text-theme-primary mr-3 text-2xl"></i>
                            Your Chosen Service
                        </h2>
                        
                        <!-- Dynamic Service Details --><div id="service-summary" class="border-b border-gray-200 pb-6 mb-6">
                            <p class="text-sm font-semibold text-theme-primary uppercase mb-1" id="summary-category">
                                Category: {{ $service->category }}
                            </p>
                            <h3 class="text-2xl font-extrabold text-gray-900 mb-2" id="summary-title">
                                {{ $service->title }}
                            </h3>
                            <p class="text-gray-700 mb-4 text-justify" id="summary-description">
                                {{ $service->description }}
                            </p>
                            
                            <div class="flex justify-between items-center py-3 bg-indigo-50 px-4 rounded-lg border border-indigo-200 mt-5">
                                <span class="font-semibold text-gray-700 text-lg">Service Value:</span>
                                <span class="text-2xl font-extrabold text-theme-primary" id="summary-price">£{{ $service->price_value }}</span>
                            </div>

                            <ul class="mt-6 space-y-3 text-md text-gray-700">
                                @foreach ($service->features as $item)
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route("service", ["name" => $service->category]) }}" class="w-full inline-flex justify-center items-center px-4 py-3 border-2 border-theme-primary shadow-lg text-lg font-bold rounded-xl text-theme-primary bg-white hover:bg-theme-primary hover:text-white transition duration-200 transform hover:scale-105">
                            <i class="fas fa-redo-alt mr-2"></i>
                            Change Service Option
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">
    
                <!-- Booking Details Form -->
                <form id="booking-form" method="POST" action="{{ route('customer.book.store') }}" class="bg-white p-8 rounded-xl shadow-3xl border-t-8 border-indigo-400">
                    @csrf
                    
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-file-alt text-indigo-600 mr-3 text-2xl"></i>
                        Your Booking Details
                    </h2>

                    <!-- Hidden Service ID (Crucial for backend logic) -->
                    <input type="hidden" name="service_id" value="{{ $service->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="full-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <div class="relative">
                                <input type="text" id="full-name" name="full_name" required value="{{ auth()->user()->name }}" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                             @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" required value="{{ auth()->user()->email }}" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Dynamic Field Area - RENDERED BY BLADE -->
                        <div id="custom-fields-area" class="md:col-span-2 space-y-4">
                            
                            {{-- Conditional field: Mother's Name for Istekhara --}}
                            @if ($service->id === 'ISTEKHARA_DEFINITIVE' || $service->id === 'ISTEKHARA_GUIDANCE')
                            <div id="mother-name-group">
                                <label for="mother-name" class="block text-sm font-medium text-gray-700 mb-1">Mother's Name (Required for {{ $service->category }})</label>
                                <div class="relative">
                                    <input type="text" id="mother-name" name="motherName" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    <i class="fas fa-female absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                            @endif

                            {{-- Conditional field: Phone Number for Rukiya Intensive --}}
                            @if ($service->id === 'RUKIYA_INTENSIVE')
                            <div id="phone-group">
                                <label for="phone-number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number (Required for Assessment)</label>
                                <div class="relative">
                                    <input type="tel" id="phone-number" name="phoneNumber" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Describe Your Inquiry (Briefly)</label>
                            <div class="relative">
                                <textarea id="description" name="inquiry_description" rows="3" required class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                <i class="fas fa-comment-dots absolute left-3 top-4 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 italic mt-8 text-center">
                        Exact session timing and further instructions will be confirmed via email after successful payment.
                    </p>
                    @if ($errors->any())
                        <div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-900">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                <!-- Payment Details and Buttons (Conditional Display) -->
                <div class="bg-indigo-900 text-white p-8 rounded-xl shadow-3xl border-t-8 border-theme-gold relative overflow-hidden">
                    <h2 class="text-3xl font-bold mb-6 flex items-center">
                        <i class="fas fa-credit-card text-theme-gold mr-3 text-2xl"></i>
                        Finalize Payment
                    </h2>
                    
                    <!-- Final Total Display -->
                    <div class="flex justify-between items-center border-b border-indigo-700 pb-4 mb-6">
                        <span class="text-xl font-semibold">Total Amount Due:</span>
                        
                        @if ($service->price_type === 'FIXED')
                            <span class="text-4xl font-extrabold text-theme-gold">£{{ number_format($service->price, 2) }}</span>
                        @elseif ($service->price_type === 'DONATION')
                            <span class="text-4xl font-extrabold text-theme-gold">Min. Donation: £{{ number_format($service->min_donation, 2) }}</span>
                        @elseif ($service->price_type === 'RESERVATION')
                            <span class="text-4xl font-extrabold text-theme-gold text-yellow-400">Assessment Required</span>
                        @else
                            <span class="text-4xl font-extrabold text-theme-gold">Free</span>
                        @endif
                    </div>

                    <!-- Payment Buttons -->
                    @if ($service->price_type !== 'FREE' && $service->price_type !== 'RESERVATION')
                        <div class="space-y-4">
                            <!-- PayPal Button -->
                            <button id="paypal-button" type="submit" form="booking-form" class="w-full py-4 text-lg font-bold text-white bg-[#0070BA] rounded-xl hover:bg-[#003087] transition duration-150 shadow-md flex items-center justify-center">
                                <i class="fab fa-paypal mr-3 text-2xl"></i> Pay Securely with PayPal
                            </button>

                            <!-- Stripe Button -->
                            <button id="stripe-button" type="submit" form="booking-form" class="w-full py-4 text-lg font-bold text-white bg-[#635BFF] rounded-xl hover:bg-[#4a45cb] transition duration-150 shadow-md flex items-center justify-center">
                                <i class="fab fa-stripe-s mr-3 text-2xl"></i> Pay with Card (via Stripe)
                            </button>
                        </div>
                    @elseif ($service->price_type === 'RESERVATION')
                        <button type="submit" form="booking-form" class="w-full py-4 text-lg font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition duration-150 shadow-md flex items-center justify-center">
                            <i class="fas fa-calendar-check mr-3 text-2xl"></i> Request Assessment & Book
                        </button>
                    @else
                        <button type="submit" form="booking-form" class="w-full py-4 text-lg font-bold text-white bg-green-600 rounded-xl hover:bg-green-700 transition duration-150 shadow-md flex items-center justify-center">
                            <i class="fas fa-calendar-check mr-3 text-2xl"></i> Book Free Consultation
                        </button>
                    @endif
                </div>
                </form>
                </div>

            </div>
        </div>
    </main>

    @endsection

@push('scripts')
    <script>

        
    </script> 
@endpush