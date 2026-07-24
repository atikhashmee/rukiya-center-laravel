@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Breadcrumb -->
    <section class="bg-brand-cream/50 border-b border-brand-gold/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center gap-2 text-xs text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-brand-teal transition">Home</a>
                <span>/</span>
                <a href="{{ route('shop') }}" class="hover:text-brand-teal transition">Shop</a>
                <span>/</span>
                <span class="text-brand-teal font-medium">{{ $product->name }}</span>
            </nav>
        </div>
    </section>

    <!-- Product Detail -->
    <main class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                <!-- Product Images -->
                <div class="space-y-4">
                    <div class="relative bg-brand-cream/30 border border-brand-gold/15 rounded-2xl overflow-hidden aspect-square flex items-center justify-center" id="mainImage">
                        @if($product->images->count() > 0)
                            <img src="{{ $product->images->first()->path }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover" id="mainImg">
                        @else
                            <svg class="w-24 h-24 text-brand-gold/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                <path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
                            </svg>
                        @endif
                        @if($product->category)
                            <span class="absolute top-4 left-4 bg-brand-teal/90 text-white text-[10px] font-semibold px-3 py-1 rounded-full tracking-wider uppercase">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>

                    @if($product->images->count() > 1)
                        <div class="flex gap-3 overflow-x-auto pb-2">
                            @foreach($product->images as $image)
                                <button onclick="document.getElementById('mainImg').src='{{ $image->path }}'"
                                    class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-transparent hover:border-brand-gold transition">
                                    <img src="{{ $image->path }}" alt="" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="space-y-6">
                    @if($product->category)
                        <span class="inline-block text-[10px] font-semibold text-brand-teal bg-brand-teal/10 px-3 py-1 rounded-full tracking-wider uppercase">
                            {{ $product->category->name }}
                        </span>
                    @endif

                    <h1 class="text-3xl sm:text-4xl font-serif font-bold text-brand-teal leading-tight">{{ $product->name }}</h1>

                    <div class="flex items-center gap-4">
                        <span class="text-3xl font-bold text-brand-goldDark">£{{ number_format($product->price, 2) }}</span>
                        @if($product->stock_quantity > 0)
                            <span class="text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full">In Stock ({{ $product->stock_quantity }})</span>
                        @else
                            <span class="text-xs font-semibold text-red-700 bg-red-100 px-3 py-1 rounded-full">Out of Stock</span>
                        @endif
                    </div>

                    @if($product->description)
                        <div class="prose prose-sm text-slate-600 leading-relaxed">
                            <p>{!! nl2br(e($product->description)) !!}</p>
                        </div>
                    @endif

                    @if($product->stock_quantity > 0)
                        <!-- Add to Cart Form -->
                        <form action="{{ route('cart.add') }}" method="POST" class="flex items-center gap-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="flex items-center border border-brand-gold/30 rounded-full overflow-hidden">
                                <button type="button" onclick="this.parentNode.querySelector('input').stepDown()" class="px-4 py-3 text-brand-teal hover:bg-brand-cream transition">&minus;</button>
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                                    class="w-14 text-center text-sm font-semibold text-brand-teal border-x border-brand-gold/30 py-3 focus:outline-none">
                                <button type="button" onclick="this.parentNode.querySelector('input').stepUp()" class="px-4 py-3 text-brand-teal hover:bg-brand-cream transition">&plus;</button>
                            </div>
                            <button type="submit" class="flex-1 bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3 rounded-full font-semibold text-sm transition shadow">
                                Add to Cart
                            </button>
                        </form>
                    @endif

                    <!-- Features -->
                    <div class="border-t border-brand-gold/15 pt-6 space-y-3">
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <svg class="w-5 h-5 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Authentic Prophetic Remedy
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <svg class="w-5 h-5 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                            100% Secure & Halal
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <svg class="w-5 h-5 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="M7 15h0M2 9.5h20"/></svg>
                            UK Based Dispatch
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($related->count() > 0)
                <section class="mt-20">
                    <h2 class="text-2xl font-serif font-bold text-brand-teal mb-8">You May Also Like</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($related as $item)
                            <a href="{{ route('shop.show', $item->slug ?? $item->id) }}" class="group bg-brand-cream/30 border border-brand-gold/15 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="relative h-48 bg-brand-cream/50 flex items-center justify-center overflow-hidden">
                                    @if($item->images->count() > 0)
                                        <img src="{{ $item->images->first()->path }}" alt="{{ $item->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <svg class="w-12 h-12 text-brand-gold/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                            <path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="p-4 space-y-2">
                                    <h3 class="font-serif font-bold text-brand-teal text-sm leading-tight">{{ $item->name }}</h3>
                                    <span class="text-lg font-bold text-brand-goldDark">£{{ number_format($item->price, 2) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </main>
@endsection
