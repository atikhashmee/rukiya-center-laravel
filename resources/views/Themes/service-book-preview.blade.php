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

                <!-- RIGHT COLUMN: Enhanced Booking Form & Payment --><div class="lg:col-span-2 space-y-8">
                    
                    <!-- Booking Details Form --><form id="booking-form" class="bg-white p-8 rounded-xl shadow-3xl border-t-8 border-indigo-400">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-file-alt text-indigo-600 mr-3 text-2xl"></i>
                            Your Booking Details
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="full-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <div class="relative">
                                    <input type="text" id="full-name" name="fullName" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                            
                            <!-- Dynamic Field Area (For Mother's Name, etc.) --><div id="custom-fields-area" class="md:col-span-2 space-y-4">
                                <!-- Example of a dynamic field that would be inserted by JS --><div id="mother-name-group" style="display: block;">
                                    <label for="mother-name" class="block text-sm font-medium text-gray-700 mb-1">Mother's Name (Required for Istekhara)</label>
                                    <div class="relative">
                                        <input type="text" id="mother-name" name="motherName" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                        <i class="fas fa-female absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Describe Your Inquiry (Briefly)</label>
                                <div class="relative">
                                    <textarea id="description" name="description" rows="3" required class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                    <i class="fas fa-comment-dots absolute left-3 top-4 text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 italic mt-8 text-center">
                            Exact session timing and further instructions will be confirmed via email after successful payment.
                        </p>
                    </form>

                    <!-- Payment Details and Buttons --><div class="bg-indigo-900 text-white p-8 rounded-xl shadow-3xl border-t-8 border-theme-gold relative overflow-hidden">
                        <h2 class="text-3xl font-bold mb-6 flex items-center">
                            <i class="fas fa-credit-card text-theme-gold mr-3 text-2xl"></i>
                            Finalize Payment
                        </h2>
                        
                        <!-- Final Total Display --><div class="flex justify-between items-center border-b border-indigo-700 pb-4 mb-6">
                            <span class="text-xl font-semibold">Total Amount Due:</span>
                            <span class="text-4xl font-extrabold text-theme-gold" id="final-total">£50.00</span>
                        </div>

                        <!-- Payment Buttons --><div class="space-y-4">
                            <!-- PayPal Button --><button id="paypal-button" type="submit" form="booking-form" class="w-full py-4 text-lg font-bold text-white bg-[#0070BA] rounded-xl hover:bg-[#003087] transition duration-150 shadow-md flex items-center justify-center">
                                <i class="fab fa-paypal mr-3 text-2xl"></i> Pay Securely with PayPal
                            </button>

                            <!-- Stripe Button --><button id="stripe-button" type="submit" form="booking-form" class="w-full py-4 text-lg font-bold text-white bg-[#635BFF] rounded-xl hover:bg-[#4a45cb] transition duration-150 shadow-md flex items-center justify-center">
                                <i class="fab fa-stripe-s mr-3 text-2xl"></i> Pay with Card (via Stripe)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @endsection

@push('scripts')
    <script>

        // Main function to populate the page
        function loadBookingPage() {
            // In a real app, serviceId would come from URL query parameter (e.g., ?serviceId=ISTEKHARA_DEEP)
            // For now, we'll hardcode one or grab from URL if available
            const urlParams = new URLSearchParams(window.location.search);
            const serviceId = urlParams.get('serviceId') || 'ISTEKHARA_DEFINITIVE'; 
            const service = getServiceDetails(serviceId);

            // 1. Populate Summary Card
            document.getElementById('summary-category').textContent = 'Category: ' + service.category;
            document.getElementById('summary-title').textContent = service.title;
            document.getElementById('summary-description').textContent = service.description;
            
            // Handle Price/Donation Display
            const summaryPriceElement = document.getElementById('summary-price');
            const finalTotalElement = document.getElementById('final-total');
            const paymentButtonsDiv = document.querySelector('.bg-indigo-900.text-white'); // The payment confirmation box

            if (service.priceType === 'FREE') {
                summaryPriceElement.textContent = 'Free';
                finalTotalElement.textContent = 'Free';
                paymentButtonsDiv.style.display = 'none'; // Hide payment options for free service
            } else if (service.priceType === 'DONATION') {
                summaryPriceElement.innerHTML = `Min. Donation: <span class="font-extrabold text-theme-gold">£${service.minDonation.toFixed(2)}</span>`;
                finalTotalElement.innerHTML = `Min. Donation: <span class="font-extrabold text-theme-gold">£${service.minDonation.toFixed(2)}</span>`;
                // Maybe replace payment buttons with a single "Donate Now" button
            } else if (service.priceType === 'RESERVATION') {
                summaryPriceElement.textContent = 'Price to be assessed';
                finalTotalElement.textContent = 'Price to be assessed';
                document.getElementById('paypal-button').style.display = 'none';
                document.getElementById('stripe-button').textContent = 'Request Assessment & Book';
                document.getElementById('stripe-button').classList.remove('bg-[#635BFF]', 'hover:bg-[#4a45cb]');
                document.getElementById('stripe-button').classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            }
            else { // FIXED price
                summaryPriceElement.textContent = `£${service.priceValue.toFixed(2)}`;
                finalTotalElement.textContent = `£${service.priceValue.toFixed(2)}`;
            }

            // 2. Populate Features
            const featuresList = document.querySelector('#service-summary ul');
            featuresList.innerHTML = ''; // Clear existing features
            service.features.forEach(feature => {
                const li = document.createElement('li');
                li.className = 'flex items-start';
                li.innerHTML = `<i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i> ${feature}`;
                featuresList.appendChild(li);
            });


            // 3. Handle Dynamic Form Fields
            const customFieldsArea = document.getElementById('custom-fields-area');
            customFieldsArea.innerHTML = ''; // Clear existing dynamic fields

            if (service.requiredFormFields && service.requiredFormFields.includes('motherName')) {
                const motherNameHtml = `
                    <div class="relative">
                        <label for="mother-name" class="block text-sm font-medium text-gray-700 mb-1">Mother's Name (Required for Istekhara)</label>
                        <div class="relative">
                            <input type="text" id="mother-name" name="motherName" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <i class="fas fa-female absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                `;
                customFieldsArea.innerHTML += motherNameHtml;
            }
            // Add other dynamic fields if needed, e.g., phone number for Rukiya Intensive
            if (service.requiredFormFields && service.requiredFormFields.includes('phone')) {
                const phoneHtml = `
                    <div class="relative">
                        <label for="phone-number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <div class="relative">
                            <input type="tel" id="phone-number" name="phoneNumber" required class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                `;
                customFieldsArea.innerHTML += phoneHtml;
            }

            // Update submit button text if specified
            const paypalButton = document.getElementById('paypal-button');
            const stripeButton = document.getElementById('stripe-button');
            if (service.submitButtonText) {
                 if (paypalButton) paypalButton.textContent = service.submitButtonText;
                 if (stripeButton) stripeButton.textContent = service.submitButtonText;
                 if(service.priceType === 'RESERVATION') { // For reservation, only Stripe-like button makes sense
                    if (paypalButton) paypalButton.style.display = 'none';
                    if (stripeButton) {
                        stripeButton.innerHTML = `<i class="fas fa-calendar-check mr-3 text-2xl"></i> ${service.submitButtonText}`;
                        stripeButton.classList.remove('bg-[#635BFF]', 'hover:bg-[#4a45cb]');
                        stripeButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                    }
                 }
                 if(service.priceType === 'FREE') {
                     if (paypalButton) paypalButton.style.display = 'none';
                     if (stripeButton) {
                        stripeButton.innerHTML = `<i class="fas fa-calendar-check mr-3 text-2xl"></i> ${service.submitButtonText}`;
                        stripeButton.classList.remove('bg-[#635BFF]', 'hover:bg-[#4a45cb]');
                        stripeButton.classList.add('bg-green-600', 'hover:bg-green-700'); // Green for free booking
                     }
                 }
                 if(service.priceType === 'DONATION') {
                     if (paypalButton) paypalButton.innerHTML = `<i class="fas fa-hand-holding-heart mr-3 text-2xl"></i> Donate via PayPal`;
                     if (stripeButton) stripeButton.innerHTML = `<i class="fas fa-hand-holding-heart mr-3 text-2xl"></i> Donate via Card`;
                 }
            }
        }

        window.onload = loadBookingPage;
    </script> 
@endpush