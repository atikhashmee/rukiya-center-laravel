@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

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
                <a href="{{ route('wizard.service', ['category' => $service->category]) }}"
                   class="text-brand-teal text-sm font-semibold hover:text-brand-gold transition tracking-wide">
                    &lsaquo; SELECT APPOINTMENT
                </a>
                <span class="text-brand-teal font-semibold text-sm tracking-wide">Select Calendar</span>
            </div>

            <div class="text-[10px] font-bold text-brand-teal uppercase tracking-[0.15em] mb-3">Appointment</div>
            <div class="bg-white border border-slate-200 rounded-lg p-6 relative mb-10 shadow-sm">
                <a href="{{ route('wizard.service', ['category' => $service->category]) }}"
                   class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 text-2xl leading-none transition">&times;</a>
                <h3 class="text-lg font-semibold text-slate-800">{{ $service->title }}</h3>
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
            <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                <div class="flex justify-between items-start p-6 border-b border-slate-200">
                    <div class="min-w-0 mr-4">
                        <div class="text-base font-semibold text-slate-800">Any Available</div>
                    </div>
                    <a href="{{ route('wizard.schedule', ['serviceId' => $service->id, 'instructorId' => 'any']) }}"
                       class="bg-brand-teal hover:bg-brand-navy text-white px-6 py-2.5 rounded font-semibold text-xs tracking-wider transition flex-shrink-0 shadow-sm">
                        SELECT
                    </a>
                </div>
                @foreach($instructors as $instructor)
                    <div class="flex justify-between items-start p-6 {{ !$loop->last ? 'border-b border-slate-200' : '' }}">
                        <div class="min-w-0 mr-4">
                            <div class="text-base font-semibold text-slate-800 mb-2">{{ $loop->iteration }}. {{ $instructor->name }}</div>
                            @if($instructor->languages && count($instructor->languages) > 0)
                                <div class="text-xs text-slate-500 mb-1">Languages spoken by the practitioner:</div>
                                <ul class="text-sm text-slate-500 space-y-0.5">
                                    @foreach($instructor->languages as $language)
                                        <li>{{ $language }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <a href="{{ route('wizard.schedule', ['serviceId' => $service->id, 'instructorId' => $instructor->id]) }}"
                           class="bg-brand-teal hover:bg-brand-navy text-white px-6 py-2.5 rounded font-semibold text-xs tracking-wider transition flex-shrink-0 shadow-sm">
                            SELECT
                        </a>
                    </div>
                @endforeach
                @if($instructors->isEmpty())
                    <div class="p-6 text-center text-slate-500 text-sm">
                        No instructors available for this service yet.
                    </div>
                @endif
            </div>

        </div>
    </main>
@endsection
