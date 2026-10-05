@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <section class="py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-12">
                <span class="text-brand-crimson text-xs font-bold tracking-widest uppercase">আমাদের টিম</span>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-brand-teal">আমাদের বিশেষজ্ঞদের সঙ্গে পরিচিত হোন</h1>
                <p class="text-sm text-slate-500">
                    অভিজ্ঞ বিশেষজ্ঞদের মাধ্যমে রুকইয়াহ, ইস্তিখারা দিকনির্দেশনা ও ইসলামিক কাউন্সেলিং — যত্ন ও গোপনীয়তার সঙ্গে।
                </p>
            </div>

            @if($instructors->isEmpty())
                <p class="text-center text-sm text-slate-500 py-12">আমাদের টিমের তথ্য শীঘ্রই এখানে প্রকাশ করা হবে।</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($instructors as $instructor)
                        <div class="bg-white border border-brand-gold/20 rounded-2xl p-6 flex flex-col gap-4 hover:shadow-md transition">
                            <div class="flex items-center gap-4">
                                @if($instructor->photo)
                                    <img src="{{ asset('storage/' . $instructor->photo) }}" alt="{{ $instructor->name }}"
                                         class="w-16 h-16 rounded-full object-cover border border-brand-gold/30 flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-brand-teal/10 text-brand-teal font-serif font-bold flex items-center justify-center flex-shrink-0 text-lg">
                                        {{ strtoupper(mb_substr($instructor->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h2 class="text-lg font-serif font-bold text-brand-teal truncate">{{ $instructor->name }}</h2>
                                    @if($instructor->title)
                                        <p class="text-xs text-brand-gold font-semibold mt-0.5">{{ $instructor->title }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($instructor->experience || $instructor->location || $instructor->appointment_type)
                                <div class="flex flex-wrap gap-1.5">
                                    @if($instructor->experience)
                                        <span class="text-[11px] bg-brand-cream border border-brand-gold/20 text-brand-teal px-2 py-0.5 rounded-full">{{ $instructor->experience }}</span>
                                    @endif
                                    @if($instructor->location)
                                        <span class="text-[11px] bg-brand-cream border border-brand-gold/20 text-brand-teal px-2 py-0.5 rounded-full">{{ $instructor->location }}</span>
                                    @endif
                                    @if($instructor->appointment_type)
                                        <span class="text-[11px] bg-brand-cream border border-brand-gold/20 text-brand-teal px-2 py-0.5 rounded-full">{{ $instructor->appointment_type }}</span>
                                    @endif
                                </div>
                            @endif

                            @if($instructor->bio)
                                <p class="text-xs text-slate-500 leading-relaxed flex-grow">{{ $instructor->bio }}</p>
                            @endif

                            @php($languages = is_array($instructor->languages) ? $instructor->languages : array_filter(array_map('trim', explode(',', (string) $instructor->languages))))
                            @if(count($languages))
                                <div class="flex flex-wrap gap-1">
                                    @foreach($languages as $language)
                                        <span class="text-[11px] text-slate-600 bg-slate-100 rounded-full px-2 py-0.5">{{ $language }}</span>
                                    @endforeach
                                </div>
                            @endif

                            @if($instructor->services->isNotEmpty())
                                <div class="border-t border-brand-gold/10 pt-3 space-y-1">
                                    <p class="text-[11px] uppercase tracking-wide text-slate-400 font-semibold">সেবাসমূহ</p>
                                    <p class="text-xs text-slate-600">{{ $instructor->services->pluck('title')->join(', ') }}</p>
                                </div>
                            @endif

                            <a href="{{ route('wizard.index') }}"
                               class="mt-auto inline-block text-center bg-brand-teal hover:bg-brand-navy text-white px-5 py-2.5 rounded-full text-sm font-semibold transition">
                                অ্যাপয়েন্টমেন্ট নিন
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
