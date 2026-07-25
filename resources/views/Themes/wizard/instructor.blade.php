@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    @include('Themes.wizard.partials.hero', [
        'step' => 3,
        'title' => 'Book an Appointment',
        'subtitle' => 'Choose who you\'d like to see, or let us pick for you.',
    ])

    <section class="bg-yellow-50 border-b border-yellow-200 py-3">
        <div class="max-w-3xl mx-auto px-4 text-center text-sm text-yellow-800">
            *Please Note: for <strong>RUQYAH</strong> appointments &mdash; we do not see clients alone. You will need to be accompanied on the day by another adult.
        </div>
    </section>

    <main class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('wizard.service', ['category' => $service->category->slug]) }}"
                   class="text-brand-teal text-sm font-semibold hover:text-brand-gold transition tracking-wide">
                    &lsaquo; SELECT APPOINTMENT
                </a>
                <span class="text-brand-teal font-semibold text-sm tracking-wide">Select Practitioner</span>
            </div>

            <div class="text-[10px] font-bold text-brand-teal uppercase tracking-[0.15em] mb-3">Appointment</div>
            <div class="bg-white border border-slate-200 rounded-xl p-6 relative mb-10 shadow-sm">
                <a href="{{ route('wizard.service', ['category' => $service->category->slug]) }}"
                   class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 text-2xl leading-none transition">&times;</a>
                <h3 class="text-lg font-serif font-semibold text-slate-800">{{ $service->title }}</h3>
                <div class="text-slate-500 text-sm mb-3">
                    @if($service->price_type === 'FIXED')
                        £{{ number_format($service->price_value, 2) }}
                    @elseif($service->price_type === 'DONATION')
                        Min. £{{ number_format($service->min_donation, 2) }}
                    @elseif($service->price_type === 'RESERVATION')
                        <span class="text-yellow-600 font-semibold">Assessment Required</span>
                    @else
                        Free
                    @endif
                </div>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $service->description }}</p>
            </div>

            <div class="text-[10px] font-bold text-brand-teal uppercase tracking-[0.15em] mb-3">With</div>
            <div class="space-y-3">
                <div class="flex items-center justify-between gap-4 p-5 bg-white border-2 border-dashed border-brand-gold/40 rounded-xl hover:border-brand-gold hover:shadow-sm transition">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-full bg-brand-gold/15 text-brand-goldDark flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
                        </div>
                        <div class="min-w-0">
                            <div class="text-base font-semibold text-slate-800">Any Available</div>
                            <div class="text-xs text-slate-500">Fastest option — we'll match you with a free practitioner</div>
                        </div>
                    </div>
                    <a href="{{ route('wizard.schedule', ['serviceId' => $service->id, 'instructorId' => 'any']) }}"
                       class="bg-brand-teal hover:bg-brand-navy text-white px-6 py-2.5 rounded-full font-semibold text-xs tracking-wider transition flex-shrink-0 shadow-sm">
                        SELECT
                    </a>
                </div>

                @foreach($instructors as $instructor)
                    <div class="flex items-center justify-between gap-4 p-5 bg-white border border-slate-200 rounded-xl hover:border-brand-gold/40 hover:shadow-sm transition">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-full bg-brand-teal/10 text-brand-teal font-serif font-bold flex items-center justify-center flex-shrink-0 text-sm">
                                {{ strtoupper(substr($instructor->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-base font-semibold text-slate-800">{{ $instructor->name }}</div>
                                @if($instructor->languages && count($instructor->languages) > 0)
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @foreach($instructor->languages as $language)
                                            <span class="text-[11px] text-brand-teal bg-brand-cream border border-brand-gold/20 rounded-full px-2 py-0.5">{{ $language }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('wizard.schedule', ['serviceId' => $service->id, 'instructorId' => $instructor->id]) }}"
                           class="bg-brand-teal hover:bg-brand-navy text-white px-6 py-2.5 rounded-full font-semibold text-xs tracking-wider transition flex-shrink-0 shadow-sm">
                            SELECT
                        </a>
                    </div>
                @endforeach

                @if($instructors->isEmpty())
                    <div class="p-6 text-center text-slate-500 text-sm bg-white border border-slate-200 rounded-xl">
                        No named practitioners are listed for this service yet — choose "Any Available" above to continue.
                    </div>
                @endif
            </div>

        </div>
    </main>
@endsection
