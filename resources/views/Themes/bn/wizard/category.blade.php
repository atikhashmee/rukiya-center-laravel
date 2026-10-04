@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    @include('Themes.wizard.partials.hero', [
        'step' => 1,
        'title' => 'Book Your Consultation',
        'subtitle' => 'Select the type of support you are looking for.',
    ])

    <main class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-serif font-bold text-brand-teal text-center mb-10">Select a Service Category</h2>
<div class="text-center mb-10">

    <p class="text-slate-600">
        Not sure which service is right for you?
    </p>

    <p class="mt-2 text-brand-teal font-semibold">
        Start with a Free Initial Enquiry and we'll help you choose the most suitable service.
    </p>

   <a href="/free-counselling"
       class="inline-flex items-center mt-5 px-6 py-3 rounded-xl bg-brand-gold text-white font-semibold hover:bg-brand-goldDark transition">
        Free Initial Enquiry →
    </a>

</div>
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @foreach($categories as $category)
                    <a href="{{ route('wizard.service', ['category' => $category->slug]) }}"
                        class="group flex flex-col h-full min-h-[340px] bg-brand-cream/50 border border-brand-gold/20 rounded-2xl p-8 text-center hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="w-16 h-16 mx-auto mb-4 bg-brand-teal/10 rounded-full flex items-center justify-center group-hover:bg-brand-teal/20 transition">
                            <i data-lucide="{{ $category->icon ?: 'sparkles' }}" class="w-8 h-8 text-brand-teal"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-brand-teal mb-2">{{ $category->name }}</h3>
                        @if($category->description)
                            <p class="text-sm text-slate-500">{{ $category->description }}</p>
                        @endif
                       <span class="inline-block mt-auto pt-6 text-brand-gold text-sm font-semibold">
    Continue →
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