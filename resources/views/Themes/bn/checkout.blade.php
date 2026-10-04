@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <main class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <header class="mb-8 text-center space-y-2">
                <h1 class="text-3xl font-serif font-bold text-brand-teal">Secure Checkout</h1>
                <p class="text-sm text-slate-500">You are one step away from confirming your order.</p>
            </header>

            <div class="flex flex-col lg:flex-row gap-8">

                {{-- Payment Form --}}
                <div class="lg:w-3/5 bg-brand-cream/50 border border-brand-gold/20 p-6 md:p-8 rounded-2xl h-fit">
                    <h2 class="text-lg font-serif font-bold text-brand-teal mb-6 border-b border-brand-gold/20 pb-3">Payment Details</h2>

                    <div class="mb-6 flex space-x-2 border-b border-brand-gold/20">
                        <button id="card-tab" class="px-4 py-2 text-sm font-semibold text-white bg-brand-teal rounded-t-lg">Credit/Debit Card</button>
                        <button id="paypal-tab" class="px-4 py-2 text-sm font-semibold text-slate-400" disabled>PayPal (Later)</button>
                        <button id="transfer-tab" class="px-4 py-2 text-sm font-semibold text-slate-400" disabled>Bank Transfer (Later)</button>
                    </div>

                    <form id="payment-form" class="space-y-6">
                        <div>
                            <label for="email" class="block text-xs font-bold text-brand-teal mb-1">Email Address</label>
                            <input type="email" id="email" value="{{ $type === 'booking' ? $item->email : $item->email }}" required placeholder="Enter your email" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                        </div>

                        <div>
                            <label for="card-element" class="block text-xs font-bold text-brand-teal mb-1">Card Information</label>
                            <div id="card-element" class="p-3 border border-slate-200 rounded-xl"></div>
                            <div id="card-errors" role="alert" class="mt-2 text-brand-crimson text-xs font-medium"></div>
                        </div>

                        <div>
                            <label for="country" class="block text-xs font-bold text-brand-teal mb-1">Country</label>
                            <select id="country" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                <option value="">Select Country...</option>
                                <option value="GB">United Kingdom</option>
                                <option value="US">United States</option>
                            </select>
                        </div>

                        <div class="flex items-center">
                            <input id="save-card" name="save-card" type="checkbox" class="h-4 w-4 text-brand-teal border-slate-300 rounded focus:ring-brand-gold">
                            <label for="save-card" class="ml-2 block text-sm text-slate-600">Securely save my card for future payments</label>
                        </div>

                        <div id="status-message" class="text-center font-semibold p-3 rounded-xl hidden"></div>

                        <button type="submit" id="submit-button" class="w-full bg-brand-teal hover:bg-brand-navy text-white font-bold py-3.5 rounded-xl transition shadow text-sm">
                            Pay £{{ $type === 'booking' ? $item->service_price : $item->total }} Now
                        </button>

                        <p class="text-xs text-center text-slate-400">Payments secured by Stripe. PCI Compliant.</p>
                    </form>
                </div>

                {{-- Order Summary --}}
                <div class="lg:w-2/5 bg-brand-cream/50 border border-brand-gold/20 p-6 md:p-8 rounded-2xl h-fit">
                    <h2 class="text-lg font-serif font-bold text-brand-teal mb-6 border-b border-brand-gold/20 pb-3">Order Summary</h2>

                    @if($type === 'booking')
                        <div class="space-y-4">
                            <div class="flex justify-between items-start pb-4 border-b border-brand-gold/20">
                                <div>
                                    <p class="font-bold text-sm text-brand-teal">{{ $item->service->title }}</p>
                                    <p class="text-xs text-slate-400">Booking ID: <span class="font-medium text-brand-teal">#{{ $item->booking_id }}</span></p>
                                </div>
                                <span class="font-semibold text-sm text-brand-teal">£{{ $item->service_price }}</span>
                            </div>
                            <div class="space-y-2 text-sm text-slate-600">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>£{{ $item->service_price }}</span>
                                </div>
                                <div class="flex justify-between text-slate-400 italic">
                                    <span>Service Fee</span>
                                    <span>£0.00</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t-2 border-dashed border-brand-gold/20">
                                <span class="text-lg font-bold text-brand-teal">Order Total</span>
                                <span class="text-xl font-bold text-brand-teal">£{{ $item->service_price }}</span>
                            </div>
                            <div class="p-3 bg-brand-gold/10 border border-brand-gold/20 rounded-xl text-xs text-slate-600">
                                Your session will be confirmed instantly upon successful payment.
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="pb-4 border-b border-brand-gold/20">
                                <p class="text-xs text-slate-400">Order #{{ $item->order_number }}</p>
                                <p class="text-xs text-slate-400">{{ $item->full_name }}</p>
                            </div>
                            <div class="space-y-3">
                                @foreach($item->items as $lineItem)
                                    <div class="flex justify-between items-start text-sm">
                                        <div class="flex-1 min-w-0 mr-2">
                                            <p class="font-medium text-slate-700 truncate">{{ $lineItem->product_name }}</p>
                                            <p class="text-xs text-slate-400">Qty: {{ $lineItem->quantity }} × £{{ number_format($lineItem->price, 2) }}</p>
                                        </div>
                                        <span class="font-medium text-slate-700 flex-shrink-0">£{{ number_format($lineItem->subtotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="space-y-2 text-sm text-slate-600 pt-4 border-t border-brand-gold/20">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>£{{ number_format($item->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-slate-400 italic">
                                    <span>Shipping</span>
                                    <span>£0.00</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t-2 border-dashed border-brand-gold/20">
                                <span class="text-lg font-bold text-brand-teal">Order Total</span>
                                <span class="text-xl font-bold text-brand-teal">£{{ number_format($item->total, 2) }}</span>
                            </div>
                            <div class="p-3 bg-brand-gold/10 border border-brand-gold/20 rounded-xl text-xs text-slate-600">
                                Your order will be processed upon successful payment.
                            </div>
                        </div>
                    @endif
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
        const stripe = Stripe('{{ env("STRIPE_KEY") }}');
        const elements = stripe.elements({
            fonts: [{ cssSrc: 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap' }]
        });
        const cardElement = elements.create('card', {
            style: {
                base: { fontSize: '16px', fontFamily: 'Inter, sans-serif', '::placeholder': { color: '#A0AEC0' } },
                invalid: { color: '#EF4444', iconColor: '#EF4444' }
            }
        });
        cardElement.mount('#card-element');
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) { displayError.textContent = event.error.message; } else { displayError.textContent = ''; }
        });

        const form = document.getElementById('payment-form');
        const payButton = document.getElementById('submit-button');
        const statusMessage = document.getElementById('status-message');
        const orderId = {{ $type === 'booking' ? $item->id : $item->id }};
        const orderType = '{{ $type === 'booking' ? 'App\\Models\\Booking' : 'App\\Models\\Order' }}';
        const price = {{ $type === 'booking' ? $item->service_price : $item->total }};

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            payButton.disabled = true;
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i> Processing...';
            statusMessage.style.display = 'none';

            const { paymentMethod, error: pmError } = await stripe.createPaymentMethod({
                type: 'card', card: cardElement,
                billing_details: { email: document.getElementById('email').value },
            });

            if (pmError) {
                document.getElementById('card-errors').textContent = pmError.message;
                payButton.disabled = false;
                payButton.innerHTML = 'Pay £' + price.toFixed(2) + ' Now';
                return;
            }

            try {
                const response = await fetch("{{ route('wizard.payment.process') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({
                        payment_method_id: paymentMethod.id,
                        order_id: orderId,
                        order_type: orderType,
                    }),
                });
                const result = await response.json();
                if (result.error) {
                    document.getElementById('card-errors').textContent = result.error;
                    payButton.disabled = false;
                    payButton.innerHTML = 'Pay £' + price.toFixed(2) + ' Now';
                    return;
                }
                if (result.requires_action) {
                    const { error: actionError, paymentIntent } = await stripe.confirmCardPayment(result.payment_intent_client_secret);
                    if (actionError) {
                        document.getElementById('card-errors').textContent = actionError.message;
                        payButton.disabled = false;
                        payButton.innerHTML = 'Pay £' + price.toFixed(2) + ' Now';
                        return;
                    }
                    if (paymentIntent && paymentIntent.status === 'succeeded') {
                        statusMessage.textContent = 'Payment Successful! Redirecting...';
                        statusMessage.classList.add('bg-green-500', 'text-white');
                        statusMessage.style.display = 'block';
                        setTimeout(() => window.location.href = "{{ route('wizard.payment.success') }}?status=success", 1000);
                        return;
                    }
                }
                if (result.success) {
                    statusMessage.textContent = 'Payment Successful! Redirecting...';
                    statusMessage.classList.add('bg-green-500', 'text-white');
                    statusMessage.style.display = 'block';
                    setTimeout(() => window.location.href = "{{ route('wizard.payment.success') }}?status=success", 1000);
                    return;
                }
                document.getElementById('card-errors').textContent = 'Unexpected payment state. Please contact support.';
                payButton.disabled = false;
                payButton.innerHTML = 'Pay £' + price.toFixed(2) + ' Now';
            } catch (err) {
                document.getElementById('card-errors').textContent = err.message || 'Payment failed';
                payButton.disabled = false;
                payButton.innerHTML = 'Pay £' + price.toFixed(2) + ' Now';
            }
        });
    </script>
@endpush
