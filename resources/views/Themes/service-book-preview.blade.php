@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <main class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-serif font-bold text-brand-teal text-center mb-12">
                Secure Your Path to <span class="italic text-brand-gold">Inner Harmony</span>
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- LEFT COLUMN: Order Summary -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-brand-cream/50 border border-brand-gold/20 p-8 rounded-2xl space-y-6">
                        <h2 class="text-xl font-serif font-bold text-brand-teal">Your Chosen Service</h2>

                        <div class="border-b border-brand-gold/20 pb-6">
                            <p class="text-xs font-bold text-brand-gold uppercase mb-1">Category: {{ $service->category }}</p>
                            <h3 class="text-lg font-serif font-bold text-brand-teal mb-2">{{ $service->title }}</h3>
                            <p class="text-sm text-slate-600 text-justify">{{ $service->description }}</p>

                            <div class="flex justify-between items-center py-3 bg-brand-gold/10 px-4 rounded-xl border border-brand-gold/20 mt-5">
                                <span class="font-semibold text-slate-600 text-sm">Service Value:</span>
                                <span class="text-xl font-bold text-brand-teal">£{{ $service->price_value }}</span>
                            </div>

                            <ul class="mt-6 space-y-3 text-sm text-slate-600">
                                @foreach ($service->features as $item)
                                    <li class="flex items-start">
                                        <span class="text-brand-gold mr-2 mt-0.5">✓</span>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route('service', ['name' => $service->category]) }}" class="block w-full text-center border-2 border-brand-teal text-brand-teal hover:bg-brand-teal hover:text-white px-4 py-3 rounded-xl font-semibold text-sm transition">
                            Change Service Option
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">
                    <!-- Booking Details Form -->
                    <form id="booking-form" method="POST" action="{{ route('customer.book.store') }}" class="bg-brand-cream/50 border border-brand-gold/20 p-8 rounded-2xl">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">

                        <h2 class="text-xl font-serif font-bold text-brand-teal mb-6">Your Booking Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="full-name" class="block text-xs font-bold text-brand-teal mb-1">Full Name</label>
                                <input type="text" id="full-name" name="full_name" required value="{{ auth()->user()->name }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                                @error('full_name') <p class="text-brand-crimson text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-bold text-brand-teal mb-1">Email Address</label>
                                <input type="email" id="email" name="email" required value="{{ auth()->user()->email }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                            </div>

                            <!-- Dynamic Field Area -->
                            <div id="custom-fields-area" class="md:col-span-2 space-y-4">
                                @if ($service->id === 'ISTEKHARA_DEFINITIVE' || $service->id === 'ISTEKHARA_GUIDANCE')
                                <div id="mother-name-group">
                                    <label for="mother-name" class="block text-xs font-bold text-brand-teal mb-1">Mother's Name (Required for {{ $service->category }})</label>
                                    <input type="text" id="mother-name" name="motherName" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                                </div>
                                @endif

                                @if ($service->id === 'RUKIYA_INTENSIVE')
                                <div id="phone-group">
                                    <label for="phone-number" class="block text-xs font-bold text-brand-teal mb-1">Phone Number (Required for Assessment)</label>
                                    <input type="tel" id="phone-number" name="phoneNumber" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                                </div>
                                @endif
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-xs font-bold text-brand-teal mb-1">Describe Your Inquiry (Briefly)</label>
                                <textarea id="description" name="inquiry_description" rows="3" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold"></textarea>
                            </div>
                        </div>

                        <p class="text-xs text-slate-400 italic text-center">
                            Exact session timing and further instructions will be confirmed via email after successful payment.
                        </p>
                        @if ($errors->any())
                            <div class="mt-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-brand-crimson text-xs">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Payment Details and Buttons -->
                        <div class="bg-brand-navy text-white p-8 rounded-2xl mt-8 space-y-6">
                            <h2 class="text-xl font-serif font-bold">Finalize Payment</h2>

                            <!-- Final Total Display -->
                            <div class="flex justify-between items-center border-b border-slate-700 pb-4">
                                <span class="text-sm font-semibold">Total Amount Due:</span>
                                @if ($service->price_type === 'FIXED')
                                    <span class="text-2xl font-bold text-brand-gold">£{{ number_format($service->price_value, 2) }}</span>
                                @elseif ($service->price_type === 'DONATION')
                                    <span class="text-2xl font-bold text-brand-gold">Min. Donation: £{{ number_format($service->min_donation, 2) }}</span>
                                @elseif ($service->price_type === 'RESERVATION')
                                    <span class="text-xl font-bold text-yellow-400">Assessment Required</span>
                                @else
                                    <span class="text-2xl font-bold text-brand-gold">Free</span>
                                @endif
                            </div>

                            <!-- Payment Buttons -->
                            @if ($service->price_type !== 'FREE' && $service->price_type !== 'RESERVATION')
                                <div class="space-y-4">
                                    <button id="paypal-button" type="submit" form="booking-form" class="w-full py-3.5 text-sm font-bold text-white bg-[#0070BA] rounded-xl hover:bg-[#003087] transition shadow-md">
                                        Pay Securely with PayPal
                                    </button>
                                    <button id="stripe-button" type="submit" form="booking-form" class="w-full py-3.5 text-sm font-bold text-white bg-[#635BFF] rounded-xl hover:bg-[#4a45cb] transition shadow-md">
                                        Pay with Card (via Stripe)
                                    </button>
                                </div>
                            @elseif ($service->price_type === 'RESERVATION')
                                <button type="submit" form="booking-form" class="w-full py-3.5 text-sm font-bold text-white bg-brand-teal hover:bg-brand-navy rounded-xl transition shadow-md">
                                    Request Assessment & Book
                                </button>
                            @else
                                <button type="submit" form="booking-form" class="w-full py-3.5 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-xl transition shadow-md">
                                    Book Free Consultation
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