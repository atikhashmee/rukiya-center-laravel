@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-14">
                <span class="text-brand-crimson text-xs font-bold tracking-widest uppercase">Our Team</span>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-brand-teal">Meet Our Practitioners</h1>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Experienced practitioners providing Ruqyah, Istikharah guidance and Islamic counselling with care and confidentiality.
                </p>
            </div>

            @if($instructors->isEmpty())
                <p class="text-center text-sm text-slate-500 py-12">Our team details will be published here shortly.</p>
            @else
                {{-- Flex-wrap keeps the cards centred whatever the count, unlike a fixed grid --}}
                <div class="flex flex-wrap justify-center gap-8">
                    @foreach($instructors as $instructor)
                        <div class="w-full sm:w-[22rem] bg-white border border-brand-gold/20 rounded-2xl p-8 flex flex-col items-center text-center gap-4 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition duration-200">
                            @if($instructor->photo)
                                <img src="{{ $instructor->photo }}" alt="{{ $instructor->name }}" loading="lazy"
                                     class="w-32 h-32 rounded-full object-cover object-top ring-4 ring-brand-cream border border-brand-gold/30">
                            @else
                                <div class="w-32 h-32 rounded-full bg-brand-teal/10 text-brand-teal font-serif font-bold flex items-center justify-center text-3xl ring-4 ring-brand-cream">
                                    {{ strtoupper(mb_substr($instructor->name, 0, 2)) }}
                                </div>
                            @endif

                            <div class="space-y-1">
                                <h2 class="text-xl font-serif font-bold text-brand-teal leading-snug break-words">{{ $instructor->name }}</h2>
                                @if($instructor->title)
                                    <p class="text-sm text-brand-gold font-semibold">{{ $instructor->title }}</p>
                                @endif
                            </div>

                            @if($instructor->experience || $instructor->location || $instructor->appointment_type)
                                <div class="flex flex-wrap justify-center gap-1.5">
                                    @foreach(array_filter([$instructor->experience, $instructor->location, $instructor->appointment_type]) as $badge)
                                        <span class="text-[11px] bg-brand-cream border border-brand-gold/20 text-brand-teal px-2.5 py-1 rounded-full">{{ $badge }}</span>
                                    @endforeach
                                </div>
                            @endif

                            @if($instructor->bio)
                                <p class="text-xs text-slate-500 leading-relaxed">{{ $instructor->bio }}</p>
                            @endif

                            @php($languages = is_array($instructor->languages) ? $instructor->languages : array_filter(array_map('trim', explode(',', (string) $instructor->languages))))
                            @if(count($languages))
                                <div class="flex flex-wrap justify-center gap-1">
                                    @foreach($languages as $language)
                                        <span class="text-[11px] text-slate-600 bg-slate-100 rounded-full px-2.5 py-1">{{ $language }}</span>
                                    @endforeach
                                </div>
                            @endif

                            @if($instructor->services->isNotEmpty())
                                <div class="w-full border-t border-brand-gold/15 pt-4 space-y-1">
                                    <p class="text-[11px] uppercase tracking-widest text-slate-400 font-semibold">Services</p>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $instructor->services->pluck('title')->join(' • ') }}</p>
                                </div>
                            @endif

                            <a href="{{ route('wizard.index') }}"
                               class="mt-auto w-full inline-block text-center bg-brand-teal hover:bg-brand-navy text-white px-6 py-3 rounded-full text-sm font-semibold transition">
                                Book an Appointment
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
