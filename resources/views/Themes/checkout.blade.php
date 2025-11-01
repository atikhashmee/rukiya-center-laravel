    @extends('themes.layouts.app')

    @section('content')
    <header class="relative bg-indigo-900 pt-8 pb-16 overflow-hidden">
        @include('themes.layouts.nav') 
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl mx-auto py-8 md:py-12 px-4 sm:px-6 lg:px-8">
        
        <header class="mb-8 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Secure Checkout</h1>
            <p class="text-lg text-gray-500">You are one step away from confirming your healing service.</p>
        </header>

        <!-- Checkout Layout: Two Columns (Desktop) / Stacked (Mobile) -->
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Left Column: Payment Form (3/5 width on desktop) -->
            <div class="lg:w-3/5 bg-white p-6 md:p-8 rounded-xl shadow-2xl border-t-4 border-theme-primary h-fit">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center border-b pb-3">
                    <i class="fas fa-credit-card mr-3 text-theme-primary"></i> Payment Details
                </h2>

                <!-- Payment Method Tabs -->
                <div class="mb-6 flex space-x-2 border-b">
                    <button id="card-tab" class="px-4 py-2 text-sm font-semibold text-white bg-theme-primary rounded-t-lg shadow-md">
                        Credit/Debit Card
                    </button>
                    <!-- Future Tabs -->
                    <button id="paypal-tab" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-theme-primary transition duration-150" disabled>
                        PayPal (Later)
                    </button>
                    <button id="transfer-tab" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-theme-primary transition duration-150" disabled>
                        Bank Transfer (Later)
                    </button>
                </div>
                
                <form id="payment-form" class="space-y-6">

                    <!-- Customer Email (for PaymentMethod creation) -->
                    <div class="input-group">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" value="john.doe@example.com" required placeholder="Enter your email" class="pr-10">
                    </div>

                    <!-- CARD ELEMENT: Stripe takes care of Card Number, Expiry, and CVC -->
                    <div class="input-group">
                        <label for="card-element" class="block text-sm font-medium text-gray-700 mb-1">Card Information</label>
                        <div id="card-element" class="p-3">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>
                        <div id="card-errors" role="alert" class="mt-2 text-red-600 text-sm font-medium"></div>
                    </div>
                    <!-- END OF STRIPE ELEMENT -->

                    <!-- Billing Address (Simplified) -->
                    <div class="input-group">
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <select id="country" required>
                            <option value="">Select Country...</option>
                            <option value="GB">United Kingdom</option>
                            <option value="US">United States</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>

                    <div class="input-group pt-4">
                        <div class="flex items-center">
                            <input id="save-card" name="save-card" type="checkbox" class="h-4 w-4 text-theme-primary border-gray-300 rounded focus:ring-theme-primary">
                            <label for="save-card" class="ml-2 block text-sm text-gray-900">
                                Securely save my card for future payments
                            </label>
                        </div>
                    </div>

                    <div id="status-message" class="text-center font-semibold p-3 rounded-xl hidden"></div>

                    <!-- Payment Button -->
                    <button type="submit" id="submit-button" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-lg font-extrabold rounded-xl shadow-xl text-white bg-indigo-600 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition duration-300 transform hover:scale-[1.01]">
                        <i class="fas fa-check-circle mr-3"></i>
                        Pay £{{ $booking->service_price }} Now
                    </button>
                    
                    <p class="text-xs text-center text-gray-500 mt-4 flex items-center justify-center">
                        <i class="fas fa-shield-alt mr-1 text-theme-success"></i> Payments secured by Stripe. PCI Compliant.
                    </p>
                </form>

            </div>

            <!-- Right Column: Order Summary (2/5 width on desktop) -->
            <div class="lg:w-2/5 order-summary bg-white p-6 md:p-8 rounded-xl shadow-lg border-t-4 border-theme-warning h-fit">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center border-b pb-3">
                    <i class="fas fa-receipt mr-3 text-theme-warning"></i> Order Summary
                </h2>
                
                <div class="space-y-4">
                    <!-- Item Detail -->
                    <div class="flex justify-between items-start pb-4 border-b">
                        <div>
                            <p class="font-bold text-lg text-gray-900">{{ $booking->service->title }}</p>
                            <p class="text-sm text-gray-500">Booking ID: <span class="font-medium text-gray-800">#BKG-5901</span></p>
                        </div>
                        <span class="font-semibold text-lg text-gray-900">£{{ $booking->service_price }}</span>
                    </div>

                    <!-- Subtotal & Discount -->
                    <div class="space-y-2 text-gray-700">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>£{{ $booking->service_price }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600 italic">
                            <span>Service Fee</span>
                            <span>£0.00</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="flex justify-between items-center pt-4 border-t-2 border-dashed border-gray-300">
                        <span class="text-xl font-extrabold text-gray-900">Order Total</span>
                        <span class="text-3xl font-extrabold text-theme-primary">£{{ $booking->service_price }}</span>
                    </div>

                    <div class="pt-4">
                         <div class="p-3 bg-indigo-50 rounded-lg text-sm text-indigo-700 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Your session will be confirmed instantly upon successful payment.
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </main>

    @endsection

    @push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
         <script src="https://js.stripe.com/v3/"></script>
    @endpush

    @push('scripts')
         <script>
        // --- 1. STRIPE INITIALIZATION ---
        // REPLACE WITH YOUR ACTUAL STRIPE PUBLIC KEY
        const stripe = Stripe('pk_test_51SMf2aFQ8CFaLhpflHYSaGtVj8sWK5KMaHpaUcIAYerqSShmDIUXolROuzpBcvZx71y21fEKADA2WnnUVePbAIGN00qikT5RPv'); 
        
        const elements = stripe.elements({
            fonts: [{ cssSrc: 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap' }]
        });
        
        // Create an instance of the card Element. It combines all card inputs.
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    fontFamily: 'Inter, sans-serif',
                    '::placeholder': {
                        color: '#A0AEC0',
                    },
                },
                invalid: {
                    color: '#EF4444',
                    iconColor: '#EF4444'
                }
            }
        });

        // Add an instance of the card Element into the `card-element` div.
        cardElement.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // --- 2. FORM SUBMISSION HANDLER ---
        const form = document.getElementById('payment-form');
        const payButton = document.getElementById('submit-button');
        const statusMessage = document.getElementById('status-message');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            payButton.disabled = true;
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i> Processing...';
            statusMessage.style.display = 'none';

            // 1) Create PaymentMethod using card element
            const { paymentMethod, error: pmError } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                email: document.getElementById('email').value,
                },
            });

            if (pmError) {
                document.getElementById('card-errors').textContent = pmError.message;
                payButton.disabled = false;
                payButton.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now';
                return;
            }

            // 2) Send payment_method_id to backend to create & confirm PaymentIntent
            try {
                const response = await fetch("{{ route('customer.payment.process') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        payment_method_id: paymentMethod.id,
                        amount: 15000, // 15000 pence = £150.00 (for GBP)
                        currency: 'gbp',
                        description: 'Example Order #'+Math.random().toString(36).substring(7),
                        metadata: { custom: 'meta' }
                    }),
                });

                const result = await response.json();
                console.log("intent created");
                
                console.log(result);
                

                if (result.error) {
                    document.getElementById('card-errors').textContent = result.error;
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now';
                    return;
                }

                // If requires 3D Secure / further action:
                if (result.requires_action) {
                    // Use Stripe.js to handle the required action
                    const { error: actionError, paymentIntent } = await stripe.confirmCardPayment(result.payment_intent_client_secret);

                    if (actionError) {
                        document.getElementById('card-errors').textContent = actionError.message;
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now';
                        return;
                    }

                    if (paymentIntent && paymentIntent.status === 'succeeded') {
                        statusMessage.textContent = 'Payment Successful! Redirecting...';
                        statusMessage.classList.add('bg-theme-success', 'text-white');
                        statusMessage.style.display = 'block';
                        setTimeout(() => window.location.href = "{{ route('customer.payment.success') }}?status=success", 1000);
                        return;
                    }
                }

                // Immediate success (no 3D Secure)
                if (result.success) {
                    statusMessage.textContent = 'Payment Successful! Redirecting...';
                    statusMessage.classList.add('bg-theme-success', 'text-white');
                    statusMessage.style.display = 'block';
                    setTimeout(() => window.location.href = "{{ route('customer.payment.success') }}?status=success", 1000);
                    return;
                }

                // Fallback - unknown
                document.getElementById('card-errors').textContent = 'Unexpected payment state. Please contact support.';
                payButton.disabled = false;
                payButton.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now';

            } catch (err) {
                document.getElementById('card-errors').textContent = err.message || 'Payment failed';
                payButton.disabled = false;
                payButton.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now';
            }
    });
    </script> 
    @endpush
  