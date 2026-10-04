<footer class="py-12 bg-brand-navy text-slate-400 text-xs border-t border-brand-gold/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3 text-center sm:text-justify">
        <p class="uppercase text-brand-gold font-bold tracking-widest text-center">{{ __('site.footer.notice_title') }}</p>
        <p class="leading-relaxed text-[11px]">
            {!! __('site.footer.disclaimer') !!}
        </p>
        <p class="text-center pt-4">{!! __('site.footer.copyright', ['year' => date('Y')]) !!}</p>
    </div>
</footer>