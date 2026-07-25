@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">Book an Appointment</h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">Choose a category to get started.</p>
            <!-- Progress -->
            <div class="flex items-center justify-center gap-2 pt-4">
                <span class="w-8 h-8 rounded-full bg-brand-gold text-white flex items-center justify-center text-sm font-bold">1</span>
                <span class="w-8 h-0.5 bg-white/30"></span>
                <span class="w-8 h-8 rounded-full bg-white/20 text-white/60 flex items-center justify-center text-sm font-bold">2</span>
                <span class="w-8 h-0.5 bg-white/30"></span>
                <span class="w-8 h-8 rounded-full bg-white/20 text-white/60 flex items-center justify-center text-sm font-bold">3</span>
                <span class="w-8 h-0.5 bg-white/30"></span>
                <span class="w-8 h-8 rounded-full bg-white/20 text-white/60 flex items-center justify-center text-sm font-bold">4</span>
                <span class="w-8 h-0.5 bg-white/30"></span>
                <span class="w-8 h-8 rounded-full bg-white/20 text-white/60 flex items-center justify-center text-sm font-bold">5</span>
            </div>
        </div>
    </section>

    <main class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-serif font-bold text-brand-teal text-center mb-10">Select a Service Category</h2>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('wizard.service', ['category' => $category]) }}"
                       class="group block bg-brand-cream/50 border border-brand-gold/20 rounded-2xl p-8 text-center hover:shadow-lg hover:border-brand-gold/40 transition-all duration-300">
                        <div class="w-16 h-16 mx-auto mb-4 bg-brand-teal/10 rounded-full flex items-center justify-center group-hover:bg-brand-teal/20 transition">
                            @if($category === 'istekhara')
                                <svg class="w-8 h-8 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4"/><path d="m6.41 6.41-2.83-2.83"/><path d="M2 12h4"/><path d="m6.41 17.59-2.83 2.83"/><path d="M12 18v4"/><path d="m17.59 17.59 2.83 2.83"/><path d="M18 12h4"/><path d="m17.59 6.41 2.83-2.83"/></svg>
                            @elseif($category === 'rukiya')
                                <svg class="w-8 h-8 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/></svg>
                            @else
                                <svg class="w-8 h-8 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            @endif
                        </div>
                        <h3 class="text-xl font-serif font-bold text-brand-teal mb-2">{{ ucfirst($category) }}</h3>
                        <p class="text-sm text-slate-500">
                            @if($category === 'istekhara')
                                Istekhara guidance and definitive readings
                            @elseif($category === 'rukiya')
                                Prophetic healing and spiritual cleansing
                            @else
                                Professional Islamic counselling services
                            @endif
                        </p>
                        <span class="inline-block mt-4 text-brand-gold text-sm font-semibold group-hover:text-brand-goldDark transition">
                            Select →
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </main>
@endsection
