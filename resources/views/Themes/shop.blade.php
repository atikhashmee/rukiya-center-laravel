@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Page Header -->
    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">
                Our Shop
            </h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                Prophetic remedies and wellness products crafted with care, following the Sunnah.
            </p>
        </div>
    </section>

    <!-- Shop Content -->
    <main class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Filters -->
            <form method="GET" action="{{ route('shop') }}" class="mb-12 bg-brand-cream/50 border border-brand-gold/20 p-6 rounded-2xl">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-end">

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-teal mb-2">Category</label>
                        <select name="category" class="w-full border border-brand-gold/30 rounded-lg px-4 py-2.5 text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-brand-gold/50">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Min Price -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-teal mb-2">Min Price (£)</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" min="0" step="0.01"
                            placeholder="0.00"
                            class="w-full border border-brand-gold/30 rounded-lg px-4 py-2.5 text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-brand-gold/50">
                    </div>

                    <!-- Max Price -->
                    <div>
                        <label class="block text-sm font-semibold text-brand-teal mb-2">Max Price (£)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" step="0.01"
                            placeholder="100.00"
                            class="w-full border border-brand-gold/30 rounded-lg px-4 py-2.5 text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-brand-gold/50">
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-brand-gold hover:bg-brand-goldDark text-white px-6 py-2.5 rounded-full text-sm font-semibold transition shadow">
                            Filter
                        </button>
                        <a href="{{ route('shop') }}" class="px-6 py-2.5 border border-brand-gold/30 rounded-full text-sm font-semibold text-brand-teal hover:bg-brand-cream transition">
                            Clear
                        </a>
                    </div>
                </div>
            </form>

            <!-- Active Filters -->
            @if(request()->hasAny(['category', 'min_price', 'max_price']))
                <div class="mb-8 flex flex-wrap items-center gap-2 text-sm">
                    <span class="text-slate-500">Active filters:</span>
                    @if(request('category'))
                        @php $cat = $categories->firstWhere('id', request('category')); @endphp
                        @if($cat)
                            <span class="inline-flex items-center gap-1 bg-brand-teal/10 text-brand-teal px-3 py-1 rounded-full">
                                {{ $cat->name }}
                                <a href="{{ route('shop', request()->except('category')) }}" class="ml-1 hover:text-brand-crimson">&times;</a>
                            </span>
                        @endif
                    @endif
                    @if(request('min_price'))
                        <span class="inline-flex items-center gap-1 bg-brand-teal/10 text-brand-teal px-3 py-1 rounded-full">
                            Min: £{{ number_format(request('min_price'), 2) }}
                            <a href="{{ route('shop', request()->except('min_price')) }}" class="ml-1 hover:text-brand-crimson">&times;</a>
                        </span>
                    @endif
                    @if(request('max_price'))
                        <span class="inline-flex items-center gap-1 bg-brand-teal/10 text-brand-teal px-3 py-1 rounded-full">
                            Max: £{{ number_format(request('max_price'), 2) }}
                            <a href="{{ route('shop', request()->except('max_price')) }}" class="ml-1 hover:text-brand-crimson">&times;</a>
                        </span>
                    @endif
                </div>
            @endif

            <!-- Product Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($products as $product)
                        <div class="group bg-brand-cream/30 border border-brand-gold/15 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <!-- Product Image -->
                            <div class="relative h-56 bg-brand-cream/50 flex items-center justify-center overflow-hidden">
                                @if($product->images->count() > 0)
                                    <img src="{{ $product->images->first()->path }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <svg class="w-16 h-16 text-brand-gold/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                        <path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
                                    </svg>
                                @endif
                                @if($product->category)
                                    <span class="absolute top-3 left-3 bg-brand-teal/90 text-white text-[10px] font-semibold px-3 py-1 rounded-full tracking-wider uppercase">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-5 space-y-3">
                                <h3 class="text-lg font-serif font-bold text-brand-teal leading-tight">{{ $product->name }}</h3>
                                @if($product->description)
                                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">{{ $product->description }}</p>
                                @endif
                                <div class="flex items-center justify-between pt-2">
                                    <span class="text-xl font-bold text-brand-goldDark">£{{ number_format($product->price, 2) }}</span>
                                    @if($product->stock_quantity > 0)
                                        <span class="text-[10px] font-semibold text-green-700 bg-green-100 px-2.5 py-1 rounded-full">In Stock</span>
                                    @else
                                        <span class="text-[10px] font-semibold text-red-700 bg-red-100 px-2.5 py-1 rounded-full">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <svg class="w-16 h-16 text-brand-gold/30 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                    </svg>
                    <h3 class="text-xl font-serif font-bold text-brand-teal mb-2">No products found</h3>
                    <p class="text-sm text-slate-500">Try adjusting your filters or check back later.</p>
                    <a href="{{ route('shop') }}" class="inline-block mt-4 bg-brand-gold hover:bg-brand-goldDark text-white px-6 py-2.5 rounded-full text-sm font-semibold transition">
                        View All Products
                    </a>
                </div>
            @endif

        </div>
    </main>
@endsection
