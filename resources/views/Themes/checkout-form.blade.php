@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <main class="py-20 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">

            <header class="mb-8 text-center space-y-2">
                <h1 class="text-3xl font-serif font-bold text-brand-teal">Checkout</h1>
                <p class="text-sm text-slate-500">Review your cart and enter your details to continue.</p>
            </header>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                {{-- Cart Summary --}}
                <div class="lg:col-span-3 space-y-4">
                    <h2 class="text-lg font-serif font-bold text-brand-teal mb-4">Cart Items</h2>
                    @foreach($products as $product)
                        <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-4 shadow-sm">
                            <div class="w-16 h-16 bg-brand-cream/50 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($product->images->first())
                                    <img src="{{ $product->images->first()->path }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-slate-800 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400">Qty: {{ $product->quantity }} × £{{ number_format($product->price, 2) }}</p>
                            </div>
                            <span class="font-semibold text-sm text-brand-teal flex-shrink-0">£{{ number_format($product->line_total, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Checkout Form --}}
                <div class="lg:col-span-2">
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm sticky top-24">
                        <h2 class="text-lg font-serif font-bold text-brand-teal mb-6">Your Details</h2>

                        <form method="POST" action="{{ route('cart.placeOrder') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-brand-teal mb-1">Full Name <span class="text-brand-crimson">*</span></label>
                                <input type="text" name="full_name" required value="{{ old('full_name') }}" placeholder="Your full name"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                @error('full_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-brand-teal mb-1">Email <span class="text-brand-crimson">*</span></label>
                                <input type="email" name="email" required value="{{ old('email') }}" placeholder="your@email.com"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-brand-teal mb-1">Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+44 7000 000000"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="border-t border-slate-200 pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Subtotal</span>
                                    <span class="font-semibold text-slate-700">£{{ number_format($total, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Shipping</span>
                                    <span class="text-slate-400">Free</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold pt-2 border-t border-slate-200">
                                    <span class="text-brand-teal">Total</span>
                                    <span class="text-brand-teal">£{{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-brand-gold hover:bg-brand-goldDark text-white font-bold py-3.5 rounded-xl transition shadow text-sm">
                                Proceed to Payment &rarr;
                            </button>

                            <p class="text-xs text-center text-slate-400">You will be redirected to our secure payment page.</p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
