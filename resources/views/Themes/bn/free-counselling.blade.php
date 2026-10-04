@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Page Header -->
    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <p class="text-xs font-bold text-brand-gold tracking-widest uppercase">WELCOME TO DK HEALING CENTRE</p>
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight mt-2">
                Free Initial Enquiry
            </h1>
            <p class="mt-6 text-lg text-white/90 max-w-2xl mx-auto leading-relaxed">
    Speak with an experienced Imam and Ruqyah Practitioner.<br>
    Discuss your concerns and learn which service may be most suitable for your needs.
</p>
        </div>
    </section>

    <!-- Free Initial Enquiry Content -->
    <main class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-12 rounded-2xl space-y-12">
                <div class="text-center space-y-4">
                    <h2 class="text-3xl font-serif font-bold text-brand-teal">What to Expect During Your Free Initial Enquiry</h2>
                    <p class="text-sm text-slate-600 leading-relaxed max-w-2xl mx-auto">
                       This free introductory enquiry gives you the opportunity to discuss your concerns, ask questions and learn about our Ruqyah and Islamic spiritual guidance services.

There is no obligation to book any further appointments.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Benefit 1 -->
                    <div class="p-6 bg-white border border-brand-gold/20 rounded-2xl space-y-3">
                        <h3 class="font-serif font-bold text-brand-teal text-lg">Discuss Your Situation</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Share your concerns with an experienced Imam and Ruqyah practitioner in a respectful and confidential conversation..
                        </p>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="p-6 bg-white border border-brand-gold/20 rounded-2xl space-y-3">
                        <h3 class="font-serif font-bold text-brand-teal text-lg">Learn About Our Services</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            We'll explain our Ruqyah, Istikhara and Islamic spiritual guidance services so you can choose the option that best suits your needs..
                        </p>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="p-6 bg-white border border-brand-gold/20 rounded-2xl space-y-3">
                        <h3 class="font-serif font-bold text-brand-teal text-lg">No Obligation</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            This is a free introductory enquiry. You are under no obligation to book any additional services..
                        </p>
                    </div>
                </div>

                <div class="bg-brand-teal p-8 rounded-2xl text-center space-y-4">
                    <p class="text-lg font-serif font-semibold text-white">
                       Ready to Speak ?
                    </p>
                    <a href="{{ route('contact') }}" class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-10 py-3.5 rounded-full font-semibold text-sm transition shadow">
                        Book Your FREE 30-Minute Session
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection