@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- HERO SECTION -->
    <section class="relative py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-block px-3 py-1 bg-brand-gold/15 text-brand-goldDark text-xs font-semibold tracking-wider rounded-md uppercase">
                        Pure Ahl al-Sunnah wa al-Jamāʿah Creed
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-brand-teal leading-tight">
                        Grounded Spiritual Healing Through <span class="italic text-brand-gold">Qur'an & Sunnah</span>
                    </h1>
                    <p class="text-slate-600 max-w-xl mx-auto lg:mx-0 text-sm sm:text-base">
                        We provide structured, transparent Ruqyah Shariah treatment plans and authentic Prophetic herbal remedies. Empowering individuals and families to heal safely under strict privacy rules.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('services') }}" class="w-full sm:w-auto text-center bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3.5 rounded-full font-semibold transition shadow">
                            Book a Ruqyah Session
                        </a>
                        <a href="{{ route('services') }}" class="w-full sm:w-auto text-center border-2 border-brand-teal text-brand-teal hover:bg-brand-teal hover:text-white px-8 py-3 rounded-full font-semibold transition">
                            Browse Our Services
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-5 bg-brand-cream border border-brand-gold/20 rounded-2xl p-6 text-center space-y-4">
                    <span class="text-xs font-bold text-brand-crimson tracking-widest uppercase block">⚠️ Strictly Enforced Policy</span>
                    <h3 class="text-lg font-serif font-bold text-brand-teal">Safe Consultation Guarantee</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        All appointments are conducted with absolute professionalism. Female clients must be accompanied by a Mahram, adult relative, or friend for both in-person and online consultations.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section id="services" class="py-20 bg-brand-cream/50 border-y border-brand-gold/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-brand-crimson text-xs font-bold tracking-widest uppercase">Our Healing Offerings</span>
                <h2 class="text-3xl font-serif font-bold text-brand-teal">Prophetic Remedies & Spiritual Guidance</h2>
                <p class="text-sm text-slate-500">Authentic services based on Qur'an and Sunnah for spiritual restoration and healing.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service Card 1: Counseling -->
                <div class="bg-white border border-brand-gold/20 rounded-2xl p-8 hover:shadow-md transition space-y-4">
                    <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20.3c-.9 1.1-2.2 1.7-3.6 1.7H4c-1.1 0-2-.9-2-2v-4c0-.9.6-1.7 1.4-1.9L22 4"/><path d="M12 12V3h10v9"/></svg>
                    </div>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">Sacred Listening & Personal Guidance</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        A confidential space for deep dialogue and spiritual consultation. We provide compassionate listening to your challenges and offer guided, personalized advice.
                    </p>
                    <a href="{{ route('service', ['name' => 'counseling']) }}" class="inline-block text-brand-gold text-sm font-semibold hover:text-brand-goldDark transition">Learn More →</a>
                </div>

                <!-- Service Card 2: Rukiya -->
                <div class="bg-white border border-brand-gold/20 rounded-2xl p-8 hover:shadow-md transition space-y-4">
                    <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.7 2.8"/><path d="M2 15h2c.7 0 1.2.3 1.5.8L7 18"/><path d="M22 15h-2c-.7 0-1.2-.3-1.5-.8L17 12"/></svg>
                    </div>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">Personalized Rukiya: Spiritual Restoration</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        The core purification and healing process. It involves prescribed spiritual disciplines combined with therapeutic natural remedies to cleanse energetic imbalances.
                    </p>
                    <a href="{{ route('service', ['name' => 'rukiya']) }}" class="inline-block text-brand-gold text-sm font-semibold hover:text-brand-goldDark transition">Learn More →</a>
                </div>

                <!-- Service Card 3: Istekhara -->
                <div class="bg-white border border-brand-gold/20 rounded-2xl p-8 hover:shadow-md transition space-y-4">
                    <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9 9 0 0 0-9 9Z"/><path d="M12 3v18"/><path d="M3 12h18"/><path d="m14 10-2 4-2-4 4-2Z"/></svg>
                    </div>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">Istikhara: Divine Guidance</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        A sacred process of seeking benevolent counsel from the Divine before embarking on major life choices. We guide you through the dedicated spiritual method.
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
                <h2 class="text-3xl font-serif font-bold text-brand-teal">The Journey to Inner Harmony</h2>
                <p class="text-sm text-slate-500">Our system follows a guided two-step approach designed to provide deep understanding and lasting spiritual restoration.</p>
            </div>

            <div class="space-y-8 md:space-y-0 md:grid md:grid-cols-2 md:gap-8">
                <!-- Step 1 -->
                <div class="p-8 bg-brand-cream/50 rounded-2xl border border-brand-gold/20 space-y-4">
                    <span class="text-4xl font-serif font-bold text-brand-gold/30">01</span>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">Initial Counseling & Assessment</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        The journey begins with a confidential counseling session. We take the time to deeply understand your current challenges, spiritual blocks, and personal history.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-8 bg-brand-cream/50 rounded-2xl border border-brand-gold/20 space-y-4">
                    <span class="text-4xl font-serif font-bold text-brand-gold/30">02</span>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">Spiritual Meditation & Healing</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        If deep work is required, we move into dedicated spiritual meditation and healing. This phase involves personalized techniques to clear energetic imbalances.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section id="about" class="py-20 bg-brand-cream/50 border-t border-brand-gold/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <h2 class="text-3xl font-serif font-bold text-brand-teal">Verified Client Testimonials</h2>
                <p class="text-sm text-slate-500">Transparent experiences from families who pursued their spiritual pathway with us.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-white border border-brand-gold/10 rounded-2xl space-y-3 shadow-sm">
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed">
                        "Alhamdulillah, the initial counseling was incredibly insightful, leading to a profoundly transformative healing process. Highly recommend this path to anyone seeking spiritual clarity."
                    </p>
                    <span class="block text-xs font-bold text-brand-teal">— Sarah K., London</span>
                </div>
                <div class="p-6 bg-white border border-brand-gold/10 rounded-2xl space-y-3 shadow-sm">
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed">
                        "Highly structure-driven. Unlike many unregulated places, the practitioners explained the process clearly. Pure transparency and authentic Ahlus Sunnah practices."
                    </p>
                    <span class="block text-xs font-bold text-brand-teal">— Ibrahim S., Birmingham</span>
                </div>
                <div class="p-6 bg-white border border-brand-gold/10 rounded-2xl space-y-3 shadow-sm">
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed">
                        "I feel lighter, clearer, and far more aligned with my true self. The guidance offered here is authentic, powerful, and gently directs you toward healing."
                    </p>
                    <span class="block text-xs font-bold text-brand-teal">— Elena R., Manchester</span>
                </div>
            </div>
        </div>
    </section>

    <!-- NEWSLETTER SECTION -->
    <section class="py-16 bg-brand-teal">
        <div class="max-w-3xl mx-auto text-center px-4 sm:px-6 lg:px-8 space-y-6">
            <h3 class="text-2xl font-serif font-bold text-white">Stay Aligned: Get Our Spiritual Updates</h3>
            <p class="text-sm text-slate-300">Subscribe to our newsletter for exclusive insights into new healing services and special promotions.</p>
            <form action="#" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                @csrf
                <input 
                    type="email" 
                    placeholder="Enter your email address"
                    required
                    class="w-full sm:flex-1 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold"
                >
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-brand-gold hover:bg-brand-goldDark text-white rounded-xl text-sm font-semibold transition">
                    Subscribe
                </button>
            </form>
        </div>
    </section>
@endsection