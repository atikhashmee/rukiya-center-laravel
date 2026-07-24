@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Page Header -->
    <section class="relative py-16 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">Your Cart</h1>
        </div>
    </section>

    <!-- Cart Content -->
    <main class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if($products->count() > 0)
                <!-- Cart Items -->
                <div class="space-y-6">
                    @foreach($products as $product)
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 p-6 bg-brand-cream/30 border border-brand-gold/15 rounded-2xl">
                            <!-- Image -->
                            <a href="{{ route('shop.show', $product->slug ?? $product->id) }}" class="flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden bg-brand-cream/50 flex items-center justify-center">
                                @if($product->images->count() > 0)
                                    <img src="{{ $product->images->first()->path }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-brand-gold/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                        <path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
                                    </svg>
                                @endif
                            </a>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('shop.show', $product->slug ?? $product->id) }}" class="text-lg font-serif font-bold text-brand-teal hover:text-brand-gold transition">
                                    {{ $product->name }}
                                </a>
                                @if($product->category)
                                    <p class="text-xs text-slate-500 mt-1">{{ $product->category->name }}</p>
                                @endif
                                <p class="text-lg font-bold text-brand-goldDark mt-2">£{{ number_format($product->price, 2) }}</p>
                            </div>

                            <!-- Quantity -->
                            <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="flex items-center border border-brand-gold/30 rounded-full overflow-hidden">
                                    <button type="submit" name="quantity" value="{{ max(1, $product->quantity - 1) }}" class="px-3 py-2 text-brand-teal hover:bg-brand-cream transition text-sm">&minus;</button>
                                    <span class="px-4 py-2 text-sm font-semibold text-brand-teal border-x border-brand-gold/30">{{ $product->quantity }}</span>
                                    <button type="submit" name="quantity" value="{{ $product->quantity + 1 }}" class="px-3 py-2 text-brand-teal hover:bg-brand-cream transition text-sm">&plus;</button>
                                </div>
                            </form>

                            <!-- Line Total -->
                            <div class="text-right min-w-[80px]">
                                <p class="text-lg font-bold text-brand-teal">£{{ number_format($product->line_total, 2) }}</p>
                            </div>

                            <!-- Remove -->
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="text-red-400 hover:text-red-600 transition" title="Remove">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="mt-12 bg-brand-cream/50 border border-brand-gold/20 rounded-2xl p-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium transition">
                                    Clear Cart
                                </button>
                            </form>
                            <a href="{{ route('shop') }}" class="text-sm text-brand-teal hover:text-brand-gold font-medium transition">
                                Continue Shopping
                            </a>
                        </div>

                        <div class="text-right space-y-2">
                            <div class="text-2xl font-bold text-brand-teal">
                                Total: <span class="text-brand-goldDark">£{{ number_format($total, 2) }}</span>
                            </div>
                            <p class="text-xs text-slate-500">Checkout coming soon</p>
                        </div>
                    </div>
                </div>

            @else
                <!-- Empty Cart -->
                <div class="text-center py-20">
                    <svg class="w-20 h-20 text-brand-gold/30 mx-auto mb-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                    </svg>
                    <h3 class="text-2xl font-serif font-bold text-brand-teal mb-3">Your cart is empty</h3>
                    <p class="text-sm text-slate-500 mb-8">Looks like you haven't added any products yet.</p>
                    <a href="{{ route('shop') }}" class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3 rounded-full font-semibold text-sm transition shadow">
                        Browse Shop
                    </a>
                </div>
            @endif

        </div>
    </main>
@endsection
