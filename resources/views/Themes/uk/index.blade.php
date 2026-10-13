@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- HERO SECTION -->
    <section class="relative py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-block px-3 py-1 bg-brand-gold/15 text-brand-goldDark text-xs font-semibold tracking-wider rounded-md uppercase">
                        {{ content('index', 'hero_eyebrow', 'AUTHENTIC QUR’AN & SUNNAH BASED SUPPORT') }}
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-brand-teal leading-tight">
                        {!! content_html('index', 'hero_title', 'Authentic Ruqyah & <span class="italic text-brand-gold">Islamic Spiritual Guidance</span>') !!}
                    </h1>
                    <p class="text-slate-600 max-w-xl mx-auto lg:mx-0 text-sm sm:text-base">
                       {{ content('index', 'hero_text', 'Compassionate Qur\'an and Sunnah-based Ruqyah, Istikharah guidance and Islamic spiritual support for individuals and families across the UK and worldwide.') }}
                     <div class="border-l-4 border-brand-gold pl-4 my-6">
    <p class="text-2xl text-brand-teal font-arabic leading-loose">
        وَنُنَزِّلُ مِنَ الْقُرْآنِ مَا هُوَ شِفَاءٌ وَرَحْمَةٌ لِّلْمُؤْمِنِينَ
    </p>

    <p class="mt-2 text-sm italic text-slate-600">
        {{ content('index', 'hero_ayah_translation', '"We send down in the Qur\'an that which is a healing and a mercy for the believers."') }}
    </p>

    <p class="text-xs text-brand-gold font-semibold mt-1">
        {{ content('index', 'hero_ayah_reference', 'Surah Al-Isra (17:82)') }}
    </p>
</div>  
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('wizard.index') }}" class="w-full sm:w-auto text-center bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3.5 rounded-full font-semibold transition shadow">
                            {{ content('index', 'hero_button_primary', 'Book a Ruqyah Session') }}
                        </a>
                        <a href="{{ route('wizard.index') }}" class="w-full sm:w-auto text-center border-2 border-brand-teal text-brand-teal hover:bg-brand-teal hover:text-white px-8 py-3 rounded-full font-semibold transition">
                            {{ content('index', 'hero_button_secondary', 'Explore Our Services') }}
                        </a>
                    </div>
                </div>
                 <div class="lg:col-span-5 space-y-6">
                    <img
    src="{{ asset('images/hero-ruqyah.png') }}"
    alt="Authentic Qur'an and Sunnah based Ruqyah support"
    class="w-full h-80 object-cover rounded-2xl shadow-xl border border-brand-gold/20">
    <div class="bg-brand-cream border border-brand-gold/20 rounded-2xl p-6 text-center space-y-8">
                    <span class="text-xs font-bold text-brand-crimson tracking-widest uppercase block">{{ content('index', 'standards_eyebrow', '⚠️ RESPECTFUL CONSULTATION STANDARDS') }}</span>
                    <h3 class="text-lg font-serif font-bold text-brand-teal">{{ content('index', 'standards_title', 'Safe, Respectful & Confidential Care') }}</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        {{ content('index', 'standards_text', 'Every consultation is conducted professionally, confidentially and in accordance with authentic Islamic principles.') }}
                        <div class="flex flex-wrap justify-center lg:justify-start gap-4 text-sm font-medium text-brand-teal">
    <span>✓ Qur'an & Sunnah Based</span>
    <span>✓ UK Based Service</span>
    <span>✓  Worldwide Online Consultations</span>
    <span>✓ Private & Confidential</span>
</div>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section id="services" class="py-20 bg-brand-cream/50 border-y border-brand-gold/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-brand-crimson text-xs font-bold tracking-widest uppercase">{{ content('index', 'services_eyebrow', 'Our Healing Offerings') }}</span>
                <h2 class="text-3xl font-serif font-bold text-brand-teal">{{ content('index', 'services_title', 'Our Islamic Healing Services') }}</h2>
                <p class="text-sm text-slate-500">{{ content('index', 'services_text', 'Compassionate Islamic support rooted in the Qur\'an and authentic Sunnah. Every service is delivered confidentially and with professional care.') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service Card 1: Counseling -->
                <div class="bg-white rounded-2xl border border-brand-gold/20 p-8 h-full flex flex-col">
                    <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20.3c-.9 1.1-2.2 1.7-3.6 1.7H4c-1.1 0-2-.9-2-2v-4c0-.9.6-1.7 1.4-1.9L22 4"/><path d="M12 12V3h10v9"/></svg>
                    </div>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">{{ content('index', 'service_1_title', 'Islamic Counselling') }}</h3>
                    <p class="text-xs text-slate-500 leading-relaxed flex-grow">
                        {{ content('index', 'service_1_text', 'Confidential one-to-one Islamic counselling to help you navigate emotional, spiritual and personal challenges with wisdom and compassion.') }}
                    </p>
                    <a href="{{ route('service', ['name' => 'counseling']) }}" class="inline-block text-brand-gold text-sm font-semibold hover:text-brand-goldDark transition">Learn More →</a>
                </div>

                <!-- Service Card 2: Rukiya -->
                <div class="bg-white rounded-2xl border border-brand-gold/20 p-8 h-full flex flex-col">
                    <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.7 2.8"/><path d="M2 15h2c.7 0 1.2.3 1.5.8L7 18"/><path d="M22 15h-2c-.7 0-1.2-.3-1.5-.8L17 12"/></svg>
                    </div>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">Authentic Ruqyah</h3>
                    <p class="text-xs text-slate-500 leading-relaxed flex-grow">
                        {{ content('index', 'service_2_text', 'Authentic Qur\'an and Sunnah based Ruqyah for spiritual healing, protection and restoration, delivered with sincerity and professionalism.') }}
                    </p>
                    <a href="{{ route('service', ['name' => 'rukiya']) }}" class="inline-block text-brand-gold text-sm font-semibold hover:text-brand-goldDark transition">Learn More →</a>
                </div>

                <!-- Service Card 3: Istekhara -->
                <div class="bg-white rounded-2xl border border-brand-gold/20 p-8 h-full flex flex-col">
                    <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9 9 0 0 0-9 9Z"/><path d="M12 3v18"/><path d="M3 12h18"/><path d="m14 10-2 4-2-4 4-2Z"/></svg>
                    </div>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">{{ content('index', 'service_3_title', 'Istikharah Guidance') }}</h3>
                    <p class="text-xs text-slate-500 leading-relaxed flex-grow">
                        {{ content('index', 'service_3_text', 'Seek Allah\'s guidance before important life decisions through authentic Istikharah and practical Islamic advice.') }}
                    </p>
                    <a href="{{ route('service', ['name' => 'istekhara']) }}" class="inline-block text-brand-gold text-sm font-semibold hover:text-brand-goldDark transition">Learn More →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HEALING PROCESS SECTION -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <h2 class="text-3xl font-serif font-bold text-brand-teal">How Your Consultation Works </h2>
                <p class="text-sm text-slate-500">{{ content('index', 'process_text', 'Every consultation is carried out with compassion, confidentiality and strict adherence to the Qur\'an and authentic Sunnah.') }}</p>
            </div>

            <div class="w-full max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Step 1 -->
        <div class="w-full p-8 bg-brand-cream/50 rounded-2xl border border-brand-gold/20 space-y-4">
            <span class="block text-4xl font-serif font-bold text-brand-gold/30">
                01
            </span>

            <h3 class="font-serif font-bold text-brand-teal text-lg">
                {{ content('index', 'step_1_title', '📋 Initial Consultation') }}
            </h3>

            <p class="text-sm text-slate-500 leading-relaxed">
                We begin with a confidential consultation to understand your concerns,
                relevant background, spiritual challenges and personal goals.
            </p>
        </div>

        <!-- Step 2 -->
        <div class="w-full p-8 bg-brand-cream/50 rounded-2xl border border-brand-gold/20 space-y-4">
            <span class="block text-4xl font-serif font-bold text-brand-gold/30">
                02
            </span>

            <h3 class="font-serif font-bold text-brand-teal text-lg">
                {{ content('index', 'step_2_title', '📖 Authentic Ruqyah Session') }}
            </h3>

            <p class="text-sm text-slate-500 leading-relaxed">
                When Ruqyah is appropriate, we conduct the session using authentic
                Qur'anic recitation, Prophetic supplications and Islamic guidance.
            </p>
        </div>

        <!-- Step 3 -->
        <div class="w-full p-8 bg-brand-cream/50 rounded-2xl border border-brand-gold/20 space-y-4">
            <span class="block text-4xl font-serif font-bold text-brand-gold/30">
                03
            </span>

            <h3 class="font-serif font-bold text-brand-teal text-lg">
                {{ content('index', 'step_3_title', '🤝 Follow-up Support') }}
            </h3>

            <p class="text-sm text-slate-500 leading-relaxed">
                We provide practical advice, recommended adhkar, duas and ongoing
                guidance to help you continue with confidence and reliance upon Allah.
            </p>
        </div>

    </div>
</div>

    </section>

    <!-- TESTIMONIALS SECTION -->
    <section id="about" class="py-20 bg-brand-cream/50 border-t border-brand-gold/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <h2 class="text-3xl font-serif font-bold text-brand-teal">{{ content('index', 'testimonials_title', 'Trusted Client Experiences') }}</h2>
                <p class="text-xs text-slate-500 text-center mt-8 italic">
{{ content('index', 'testimonials_note', 'Testimonials reflect individual experiences. Outcomes vary according to individual circumstances and the will of Allah.') }}
</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="pt-10 pb-8 px-8 bg-white border border-brand-gold/10 rounded-2xl space-y-5 shadow-sm">
               <div class="text-brand-gold text-6xl leading-none opacity-40 mb-6">
    &ldquo;
</div>
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed">
                        "Alhamdulillah, the consultation was compassionate, professional and firmly rooted in the Qur'an and authentic Sunnah. The guidance brought peace of mind and practical steps forward."
                    </p>
                    <div class="flex gap-1 text-brand-gold text-sm mb-4">
    ⭐⭐⭐⭐⭐
</div>
<div class="w-16 h-0.5 bg-brand-gold rounded-full my-4"></div>
                    <span class="block text-xs tracking-wide uppercase font-semibold text-brand-teal">
    — A Sister from London
</span>
                </div>
                <div class="pt-10 pb-8 px-8 bg-white border border-brand-gold/10 rounded-2xl space-y-5 shadow-sm">
                <div class="text-brand-gold text-6xl leading-none opacity-40 mb-6">
    &ldquo;
</div>
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed">
                        "Highly structure-driven. Unlike many unregulated places, the practitioners explained the process clearly. Pure transparency and authentic Ahlus Sunnah practices."
                    </p>
                    <div class="flex gap-1 text-brand-gold text-sm mb-4">
    ⭐⭐⭐⭐⭐
</div>
<div class="w-16 h-0.5 bg-brand-gold rounded-full my-4"></div>
                    <span class="block text-xs tracking-wide uppercase font-semibold text-brand-teal">-A Brother from Birmingham</span>
                </div>
                <div class="pt-10 pb-8 px-8 bg-white border border-brand-gold/10 rounded-2xl space-y-5 shadow-sm">
                <div class="text-brand-gold text-6xl leading-none opacity-40 mb-6">
    &ldquo;
</div>
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed">
                        "I feel lighter, clearer, and far more aligned with my true self. The guidance offered here is authentic, powerful, and gently directs you toward healing."
                    </p>
                    <div class="flex gap-1 text-brand-gold text-sm mb-4">
    ⭐⭐⭐⭐⭐
</div>
<div class="w-16 h-0.5 bg-brand-gold rounded-full my-4"></div>
                    <span class="block text-xs tracking-wide uppercase font-semibold text-brand-teal">— Family from Manchester</span>
                </div>
            </div>
        </div>
    </section>
   <section class="py-20 bg-white border-t border-brand-gold/10">
    <div class="max-w-4xl mx-auto text-center px-6">

        <div class="w-28 h-0.5 bg-brand-gold mx-auto mb-8"></div>

<p class="text-2xl text-brand-gold leading-loose font-serif mb-3" dir="rtl">
وَإِذَا مَرِضْتُ فَهُوَ يَشْفِينِ
</p>

<p class="italic text-brand-gold text-lg mb-2">
"And when I am ill, it is He who cures me."
</p>

<p class="text-sm text-slate-500 mb-10">
Qur'an 26:80
</p>

        <h2 class="text-4xl font-serif font-bold text-brand-teal mb-4">
           {{ content('index', 'cta_title', 'Begin Your Journey with Confidence') }}
        </h2>

        <p class="text-slate-600 max-w-2xl mx-auto mb-10">
            Receive compassionate, confidential Islamic guidance rooted in the
            Qur'an and authentic Sunnah.
        </p>

        <a href="{{ route('wizard.index') }}"
           class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-10 py-4 rounded-full font-semibold transition">
            {{ content('index', 'cta_button', 'Book Your Consultation') }}
        </a>

    </div>
</section>
</section>

    <!-- NEWSLETTER SECTION -->
<section class="py-16 bg-brand-teal">
    <div class="max-w-3xl mx-auto text-center px-4 sm:px-6 lg:px-8 space-y-6">

        <h3 class="text-2xl font-serif font-bold text-white">
            Stay Connected 
        </h3>

        <p class="text-sm text-slate-300 max-w-2xl mx-auto">
            Receive occasional Qur'an and Sunnah-based wellbeing reminders, practical articles, and updates about our services. We respect your privacy and never send spam.
        </p>

        <form action="#" method="POST"
              class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">

            @csrf

            <label for="newsletter-email" class="sr-only">
                Email address
            </label>

            <input
                id="newsletter-email"
                type="email"
                name="email"
                required
                autocomplete="email"
                placeholder="Your email address"
                class="w-full sm:flex-1 px-5 py-3 rounded-xl text-sm text-slate-800
                       focus:outline-none focus:ring-2 focus:ring-brand-gold"
            >

            <button
                type="submit"
                class="w-full sm:w-auto px-8 py-3 bg-brand-gold
                       hover:bg-brand-goldDark text-white rounded-xl
                       text-sm font-semibold transition">
                {{ content('index', 'newsletter_button', 'Subscribe') }}
            </button>

        </form>

        <p class="text-xs text-white/70">
            By subscribing, you agree to receive occasional emails from
            DK Healing Centre. You may unsubscribe at any time.
        </p>

    </div>
</section>
@endsection