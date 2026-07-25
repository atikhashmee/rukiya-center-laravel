@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    @include('Themes.wizard.partials.hero', [
        'step' => 1,
        'title' => 'Book an Appointment',
        'subtitle' => 'Choose a category to get started.',
    ])

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
                    <a href="{{ route('wizard.service', ['category' => $category->slug]) }}"
                       class="group block bg-brand-cream/50 border border-brand-gold/20 rounded-2xl p-8 text-center hover:shadow-lg hover:border-brand-gold/40 transition-all duration-300">
                        <div class="w-16 h-16 mx-auto mb-4 bg-brand-teal/10 rounded-full flex items-center justify-center group-hover:bg-brand-teal/20 transition">
                            <i data-lucide="{{ $category->icon ?: 'sparkles' }}" class="w-8 h-8 text-brand-teal"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-brand-teal mb-2">{{ $category->name }}</h3>
                        @if($category->description)
                            <p class="text-sm text-slate-500">{{ $category->description }}</p>
                        @endif
                        <span class="inline-block mt-4 text-brand-gold text-sm font-semibold group-hover:text-brand-goldDark transition">
                            Select →
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </main>
@endsection

@push('css')
    <script src="https://unpkg.com/lucide@latest"></script>
@endpush

@push('scripts')
    <script>lucide.createIcons();</script>
@endpush
