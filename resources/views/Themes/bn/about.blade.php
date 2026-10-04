@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Page Header -->
    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">
                About DK Healing Centre
            </h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                Qur’an and Sunnah-based Ruqyah, Istikhara guidance and Islamic spiritual support for individuals and families in the UK and worldwide..
            </p>
        </div>
    </section>

    <!-- About Content -->
    <main class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">

            <!-- SECTION 1: The Founding Story -->
            <section class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-12 rounded-3xl">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-20 items-center">
                    <div class="lg:col-span-6 space-y-6">
                        <h2 class="text-3xl font-serif font-bold text-brand-teal">Authentic Islamic Healing & Spiritual Support</h2>
                        <p class="text-sm text-slate-600 leading-relaxed">
                           DK Healing Centre is a trusted UK-based Islamic spiritual support service providing authentic Ruqyah, Istikhara guidance and Islamic spiritual support in accordance with the Qur'an and Sunnah. Led by an experienced Imam, Ruqyah Practitioner and Islamic Spiritual Guide with over 30 years of serving the Muslim community and more than 9 years of Ruqyah practice in the UK, we are dedicated to supporting individuals and families with compassion, respect and confidentiality. Our aim is to help people strengthen their relationship with Allah, find spiritual clarity and receive authentic Islamic guidance, while recognising that healing, guidance and success come from Allah alone.
                        </p>
                        <blockquote class="border-l-8 border-brand-gold pl-6 py-2 text-sm text-slate-600 italic">
                            "Healing comes from Allah alone. Our role is to provide authentic Qur'an and Sunnah based Ruqyah, sincere guidance and compassionate support."
                        </blockquote>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Every individual is welcomed with dignity, compassion and complete confidentiality. We listen carefully, explain every step clearly and provide authentic Islamic spiritual guidance rooted in the Qur'an and Sunnah, helping people seek healing, peace and hope through Allah's mercy.
                        </p>
                    </div>
                    <div class="lg:col-span-6 flex justify-center">
    <img
        src="{{ asset('images/about-dk-healing.png') }}"
        alt="DK Healing Centre"
        class="w-full h-[430px] rounded-2xl object-cover shadow-2xl border border-brand-gold/20">
</div><div class="flex justify-center">
    
                </div>
            </section>
<!-- Trust Statistics -->
<section class="py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="text-center bg-brand-cream/50 border border-brand-gold/20 rounded-2xl p-8 min-h-[320px] transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
        <div class="w-14 h-14 bg-brand-teal/10 rounded-xl flex items-center justify-center mx-auto mb-4">
    <svg class="w-7 h-7 text-brand-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V10L12 3 2 10v10h5m10 0v-6H7v6"/>
    </svg>
</div>
            <h3 class="text-4xl font-bold text-brand-gold">30+</h3>
            <p class="mt-3 font-serif text-lg font-semibold text-brand-teal">
                Years of Community Service
            </p>
            <p class="text-sm text-slate-600 mt-2">
                Serving the Muslim community with trusted Islamic guidance.
            </p>
        </div>

        <div class="text-center bg-brand-cream/50 border border-brand-gold/20 rounded-2xl p-8 min-h-[320px] transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
        <div class="w-14 h-14 bg-brand-teal/10 rounded-xl flex items-center justify-center mx-auto mb-4">
    <svg class="w-7 h-7 text-brand-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c4.97-4.97 8-8.58 8-12a5 5 0 00-9-3 5 5 0 00-9 3c0 3.42 3.03 7.03 8 12z"/>
    </svg>
</div>
            <h3 class="text-4xl font-bold text-brand-gold">9+</h3>
            <p class="mt-3 font-serif text-lg font-semibold text-brand-teal">
                UK Ruqyah Experience
            </p>
            <p class="text-sm text-slate-600 mt-2">
                Providing authentic Ruqyah and spiritual support across the UK.
            </p>
        </div>

        <div class="text-center bg-brand-cream/50 border border-brand-gold/20 rounded-2xl p-8 min-h-[320px] transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
        <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center mx-auto mb-4">
    <svg class="w-7 h-7 text-brand-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.483 9.246 5 7.5 5S4.168 5.483 3 6.253v13C4.168 18.483 5.754 18 7.5 18s3.332.483 4.5 1.253m0-13C13.168 5.483 14.754 5 16.5 5s3.332.483 4.5 1.253v13C19.832 18.483 18.246 18 16.5 18s-3.332.483-4.5 1.253"/>
    </svg>
</div>
            <h3 class="text-5xl font-bold text-brand-gold">100%</h3>
            <p class="mt-3 font-serif text-lg font-semibold text-brand-teal">
                Qur'an & Sunnah Based
            </p>
            <p class="text-sm text-slate-600 mt-2">
                Every service is rooted in authentic Islamic teachings.
            </p>
        </div>

    </div>
</section>
            <!-- SECTION 2: Core Values -->
            <section>
                <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                    <h2 class="text-3xl font-serif font-bold text-brand-teal">Our Guiding Principles</h2>
                    <p class="text-sm text-slate-500">Guided by authentic Islamic teachings, compassion and a commitment to serving every individual with respect and confidentiality.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Value 1: Integrity -->
                    <div class="text-center p-8 bg-brand-cream/50 border border-brand-gold/20 rounded-2xl space-y-4">
                        <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                        </div>
                        <h3 class="font-serif font-bold text-brand-teal text-lg"> Qur'an & Sunnah Based</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Every service we provide is guided by authentic Islamic teachings from the Qur'an and the Sunnah.
                        </p>
                    </div>

                    <!-- Value 2: Compassion -->
                    <div class="text-center p-8 bg-brand-cream/50 border border-brand-gold/20 rounded-2xl space-y-4">
                        <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </div>
                        <h3 class="font-serif font-bold text-brand-teal text-lg"> Compassion & Confidentiality</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Every individual is welcomed with respect, compassion and complete confidentiality.
                        </p>
                    </div>

                    <!-- Value 3: Clarity -->
                    <div class="text-center p-8 bg-brand-cream/50 border border-brand-gold/20 rounded-2xl space-y-4">
                        <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .6-2 1-3 1-2 2-3 3-3"/><path d="M10 14c-.2-1-.6-2-1-3-1-2-2-3-3-3"/><path d="M12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2Z"/><path d="M10 17H7.76l-.34.34a1 1 0 0 0 0 1.41l1.41 1.41a1 1 0 0 0 1.41 0l1.41-1.41a1 1 0 0 0 0-1.41L12 17h-2Z"/></svg>
                        </div>
                        <h3 class="font-serif font-bold text-brand-teal text-lg"> Supporting Individuals & Families Worldwide</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            We provide trusted Islamic spiritual support to individuals and families across the UK and worldwide through secure online and in-person services.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection