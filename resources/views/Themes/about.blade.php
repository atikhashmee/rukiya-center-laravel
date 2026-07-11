@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Page Header -->
    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">
                Our Mission: Guided by Inner Peace
            </h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                DK Healing Centre is dedicated to restoring spiritual balance and empowering individuals through personalized guidance and sacred practices.
            </p>
        </div>
    </section>

    <!-- About Content -->
    <main class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">

            <!-- SECTION 1: The Founding Story -->
            <section class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-12 rounded-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6">
                        <h2 class="text-3xl font-serif font-bold text-brand-teal">A Journey of Spiritual Restoration</h2>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            The DK Healing Centre was founded on the belief that true healing stems from aligning one's inner spiritual self with the outer world. We recognized the modern need for authentic, grounded spiritual guidance that respects individual journeys.
                        </p>
                        <blockquote class="border-l-4 border-brand-gold pl-4 py-2 text-sm text-slate-600 italic">
                            "Our methods are ancient, our application is modern. We bridge tradition and contemporary life to achieve lasting clarity."
                        </blockquote>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            We don't just offer services; we offer a partnership in your pursuit of inner harmony. Our personalized approach, utilizing techniques like Sacred Listening, Rukiya, and Istikhara, is designed to dissolve spiritual blocks.
                        </p>
                    </div>
                    <div class="flex justify-center">
                        <div class="w-full h-64 bg-brand-teal/10 rounded-2xl flex items-center justify-center">
                            <svg class="w-16 h-16 text-brand-teal/30" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: Core Values -->
            <section>
                <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                    <h2 class="text-3xl font-serif font-bold text-brand-teal">Our Guiding Principles</h2>
                    <p class="text-sm text-slate-500">The values that drive our mission to serve.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Value 1: Integrity -->
                    <div class="text-center p-8 bg-brand-cream/50 border border-brand-gold/20 rounded-2xl space-y-4">
                        <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                        </div>
                        <h3 class="font-serif font-bold text-brand-teal text-lg">Absolute Integrity</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            We uphold the highest ethical standards, ensuring all guidance is transparent, authentic, and delivered with genuine care and honesty.
                        </p>
                    </div>

                    <!-- Value 2: Compassion -->
                    <div class="text-center p-8 bg-brand-cream/50 border border-brand-gold/20 rounded-2xl space-y-4">
                        <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </div>
                        <h3 class="font-serif font-bold text-brand-teal text-lg">Unwavering Compassion</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Every journey is met with empathy. We treat each individual with kindness and respect, creating a supportive, non-judgmental healing environment.
                        </p>
                    </div>

                    <!-- Value 3: Clarity -->
                    <div class="text-center p-8 bg-brand-cream/50 border border-brand-gold/20 rounded-2xl space-y-4">
                        <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .6-2 1-3 1-2 2-3 3-3"/><path d="M10 14c-.2-1-.6-2-1-3-1-2-2-3-3-3"/><path d="M12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2Z"/><path d="M10 17H7.76l-.34.34a1 1 0 0 0 0 1.41l1.41 1.41a1 1 0 0 0 1.41 0l1.41-1.41a1 1 0 0 0 0-1.41L12 17h-2Z"/></svg>
                        </div>
                        <h3 class="font-serif font-bold text-brand-teal text-lg">Pinnacle Clarity</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Our purpose is to help you cut through confusion and doubt, providing clear spiritual insights that enable confident decision-making and direction.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection