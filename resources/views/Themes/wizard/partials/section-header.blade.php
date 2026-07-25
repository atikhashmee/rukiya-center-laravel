<div class="flex items-start gap-3 mb-5">
    <span class="flex-shrink-0 w-7 h-7 rounded-full bg-brand-teal text-white text-xs font-bold flex items-center justify-center mt-0.5">{{ $n }}</span>
    <div>
        <h3 class="text-lg font-serif font-bold text-brand-teal leading-tight">{{ $title }}</h3>
        @if($hint ?? null)
            <p class="text-xs text-slate-500 mt-1">{{ $hint }}</p>
        @endif
    </div>
</div>
<div class="h-px bg-gradient-to-r from-brand-gold/40 via-brand-gold/10 to-transparent mb-5"></div>
