@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Page Header -->
    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">
                Your Path to Spiritual Healing
            </h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                We offer three core, personalized services designed to guide you toward clarity, restoration, and inner peace.
            </p>
        </div>
    </section>

    <!-- Services Content -->
    <main class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

            <!-- SERVICE 1: Sacred Listening & Guidance -->
            <section class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-12 rounded-2xl flex flex-col lg:flex-row items-center gap-10">
                <div class="lg:w-1/2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20.3c-.9 1.1-2.2 1.7-3.6 1.7H4c-1.1 0-2-.9-2-2v-4c0-.9.6-1.7 1.4-1.9L22 4"/><path d="M12 12V3h10v9"/></svg>
                        </div>
                        <h2 class="text-2xl font-serif font-bold text-brand-teal">1. Sacred Listening & Guidance</h2>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        This is the foundational step in your journey toward clarity. We provide a confidential, non-judgmental space for deep dialogue and spiritual consultation, focusing entirely on understanding your unique story.
                    </p>
                    <ul class="text-sm text-slate-600 space-y-2">
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> In-depth conversational intake</li>
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> Guided advice for immediate clarity</li>
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> Preparation for deeper healing rituals</li>
                    </ul>
                    <a href="{{ route('service', ['name' => 'counseling']) }}" class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3 rounded-full font-semibold text-sm transition shadow">
                        Book Your Consultation Now
                    </a>
                </div>
                <div class="lg:w-1/2 flex justify-center">
                    <div class="w-full max-w-md h-64 bg-brand-gold/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-16 h-16 text-brand-gold/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20.3c-.9 1.1-2.2 1.7-3.6 1.7H4c-1.1 0-2-.9-2-2v-4c0-.9.6-1.7 1.4-1.9L22 4"/><path d="M12 12V3h10v9"/></svg>
                    </div>
                </div>
            </section>

            <!-- SERVICE 2: Personalized Rukiya -->
            <section class="bg-white border border-brand-gold/20 p-8 md:p-12 rounded-2xl flex flex-col lg:flex-row items-center gap-10">
                <div class="lg:w-1/2 flex justify-center order-2 lg:order-1">
                    <div class="w-full max-w-md h-64 bg-brand-teal/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-16 h-16 text-brand-teal/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.7 2.8"/><path d="M2 15h2c.7 0 1.2.3 1.5.8L7 18"/><path d="M22 15h-2c-.7 0-1.2-.3-1.5-.8L17 12"/></svg>
                    </div>
                </div>
                <div class="lg:w-1/2 order-1 lg:order-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.7 2.8"/><path d="M2 15h2c.7 0 1.2.3 1.5.8L7 18"/><path d="M22 15h-2c-.7 0-1.2-.3-1.5-.8L17 12"/></svg>
                        </div>
                        <h2 class="text-2xl font-serif font-bold text-brand-teal">2. Personalized Rukiya</h2>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Rukiya is our comprehensive purification and spiritual healing process. It is customized based on your initial consultation to precisely target and clear negative energies.
                    </p>
                    <ul class="text-sm text-slate-600 space-y-2">
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> Customized cleansing protocol based on need</li>
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> Guided practices for spiritual fortification</li>
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> Designed to achieve profound, lasting inner peace</li>
                    </ul>
                    <a href="{{ route('service', ['name' => 'rukiya']) }}" class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3 rounded-full font-semibold text-sm transition shadow">
                        Start Your Restoration
                    </a>
                </div>
            </section>

            <!-- SERVICE 3: Istikhara -->
            <section class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-12 rounded-2xl flex flex-col lg:flex-row items-center gap-10">
                <div class="lg:w-1/2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9 9 0 0 0-9 9Z"/><path d="M12 3v18"/><path d="M3 12h18"/><path d="m14 10-2 4-2-4 4-2Z"/></svg>
                        </div>
                        <h2 class="text-2xl font-serif font-bold text-brand-teal">3. Istikhara: Divine Guidance</h2>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        When facing a major life decision—be it career, relationship, or relocation—Istikhara is a sacred spiritual method to seek benevolent counsel from the Divine.
                    </p>
                    <ul class="text-sm text-slate-600 space-y-2">
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> Process for clarity on life-altering decisions</li>
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> Removes doubt and grants peace of mind</li>
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> Move forward with confidence and spiritual approval</li>
                    </ul>
                    <a href="{{ route('service', ['name' => 'istekhara']) }}" class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3 rounded-full font-semibold text-sm transition shadow">
                        Request Guidance Now
                    </a>
                </div>
                <div class="lg:w-1/2 flex justify-center">
                    <div class="w-full max-w-md h-64 bg-brand-crimson/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-16 h-16 text-brand-crimson/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9 9 0 0 0-9 9Z"/><path d="M12 3v18"/><path d="M3 12h18"/><path d="m14 10-2 4-2-4 4-2Z"/></svg>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection