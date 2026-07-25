@php
    $wizardSteps = ['Category', 'Service', 'Practitioner', 'Schedule', 'Your Details'];
@endphp
<section class="relative py-14 sm:py-18 bg-brand-teal overflow-hidden">
    <div class="absolute inset-0 opacity-[0.07] pointer-events-none"
         style="background-image: radial-gradient(circle at 1px 1px, white 1.5px, transparent 0); background-size: 22px 22px;"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5 relative">
        <div>
            <h1 class="text-3xl sm:text-4xl font-serif font-bold text-white leading-tight">{{ $title }}</h1>
            @if($subtitle ?? null)
                <p class="text-slate-300 text-sm sm:text-base mt-2 max-w-2xl mx-auto">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="flex items-center justify-center gap-1.5 sm:gap-2">
            @foreach($wizardSteps as $i => $label)
                @php $n = $i + 1; @endphp
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <span title="{{ $label }}"
                          class="w-8 h-8 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold transition
                          {{ $n < $step
                                ? 'bg-brand-gold text-white'
                                : ($n === $step
                                    ? 'bg-white text-brand-teal ring-4 ring-brand-gold/30'
                                    : 'bg-white/15 text-white/50') }}">
                        @if($n < $step)
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        @else
                            {{ $n }}
                        @endif
                    </span>
                    @if(!$loop->last)
                        <span class="w-5 sm:w-9 h-0.5 rounded-full {{ $n < $step ? 'bg-brand-gold' : 'bg-white/20' }}"></span>
                    @endif
                </div>
            @endforeach
        </div>
        <p class="text-[11px] uppercase tracking-[0.2em] text-brand-gold/80 font-semibold">{{ $wizardSteps[$step - 1] }}</p>
    </div>
</section>
