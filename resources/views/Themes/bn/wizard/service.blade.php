@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    @include('Themes.wizard.partials.hero', [
        'step' => 2,
        'title' => 'Book an Appointment',
        'subtitle' => 'Choose the appointment that fits your needs.',
    ])

    <main class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-8">
                <a href="{{ route('wizard.index') }}" class="text-brand-teal hover:text-brand-gold transition">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <h2 class="text-2xl font-serif font-bold text-brand-teal">{{ $category->name }} Services</h2>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($services as $service)
                    <div class="bg-brand-cream/30 border border-brand-gold/15 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-brand-teal text-lg font-serif font-bold">{{ $loop->iteration }}</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-serif font-bold text-brand-teal mb-1">{{ $service->title }}</h3>
                                @if($service->tagline)
                                    <p class="text-xs text-brand-gold font-semibold uppercase tracking-wider">{{ $service->tagline }}</p>
                                @endif
                            </div>
                        </div>

                        <p class="text-sm text-slate-600 mb-4 leading-relaxed">{{ $service->description }}</p>

                        @if($service->features && count($service->features) > 0)
                            <ul class="space-y-2 mb-6">
                                @foreach(array_slice($service->features, 0, 4) as $feature)
                                    <li class="flex items-start text-sm text-slate-600">
                                        <span class="text-brand-gold mr-2 mt-0.5 flex-shrink-0">✓</span>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="flex items-center justify-between pt-4 border-t border-brand-gold/10">
                            <div>
                                @if($service->price_type === 'FIXED')
                                    <span class="text-lg font-bold text-brand-teal">£{{ number_format($service->price_value, 2) }}</span>
                                @elseif($service->price_type === 'DONATION')
                                    <span class="text-lg font-bold text-brand-teal">Min. £{{ number_format($service->min_donation, 2) }} donation</span>
                                @elseif($service->price_type === 'RESERVATION')
                                    <span class="text-sm font-semibold text-yellow-600">Assessment Required</span>
                                @else
                                    <span class="text-lg font-bold text-green-700">Free</span>
                                @endif
                            </div>
                            <a href="{{ route('wizard.instructor', ['serviceId' => $service->id]) }}"
                               class="bg-brand-gold hover:bg-brand-goldDark text-white px-6 py-2.5 rounded-full text-sm font-semibold transition shadow">
                                {{ $service->submit_button_text ?? 'Select Service' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
