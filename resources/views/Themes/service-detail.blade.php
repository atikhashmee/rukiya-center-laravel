@extends('Themes.layouts.app')
@push('css')
    <script src="https://unpkg.com/lucide@latest"></script>
@endpush
@section('content')
    @include('Themes.layouts.nav')

    <!-- Page Header -->
    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            @if ($service_type == "counseling")
                <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">Spiritual Counseling Paths</h1>
                <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                    Start your journey with a complimentary session to establish trust and clarity before committing to deep, transformative guidance.
                </p>
            @elseif ($service_type == "rukiya")
                <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">Rukiya: Spiritual Healing & Restoration</h1>
                <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                    Select the path that aligns with your spiritual needs. We offer gentle guidance for minor concerns and intensive intervention for complex challenges.
                </p>
            @elseif ($service_type == "istekhara")
                <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">Istekhara Guidance Options</h1>
                <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                    Seek divine guidance and clarity for life's important decisions. Choose the path that matches the complexity of your situation.
                </p>
            @endif
        </div>
    </section>

    <!-- Service Selection Area -->
    <main class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach ($services as $service)
                    <div class="bg-brand-cream/50 border border-brand-gold/20 rounded-2xl p-8 space-y-4 hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-brand-teal/10 rounded-xl flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $service->icon }}" class="w-5 h-5 text-brand-teal"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-serif font-bold text-brand-teal">{{ $service->title }}</h2>
                                <p class="text-xs text-slate-500">{{ $service->tagline }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            {{ $service->description }}
                        </p>
                        <ul class="space-y-2">
                            @foreach ($service->features as $feature)
                                <li class="flex items-center gap-2 text-sm text-slate-600">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-brand-gold shrink-0"></i>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('customer.book.preview', ['service' => $service->id]) }}" class="block w-full text-center bg-brand-gold hover:bg-brand-goldDark text-white px-4 py-3 rounded-xl font-semibold text-sm transition shadow">
                            {{ $service->submit_button_text }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        lucide.createIcons();
    </script>
@endpush