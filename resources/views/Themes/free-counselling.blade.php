@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Page Header -->
    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <p class="text-xs font-bold text-brand-gold tracking-widest uppercase">Special Offer</p>
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight mt-2">
                Your First Step to Inner Harmony: FREE 30-Minute Consultation
            </h1>
        </div>
    </section>

    <!-- Free Counselling Content -->
    <main class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-12 rounded-2xl space-y-12">
                <div class="text-center space-y-4">
                    <h2 class="text-3xl font-serif font-bold text-brand-teal">How Our Free Counseling Works</h2>
                    <p class="text-sm text-slate-600 leading-relaxed max-w-2xl mx-auto">
                        We understand that committing to a healing journey is a big decision. That's why we offer a completely free, no-obligation 30-minute counseling session to experience our compassionate approach firsthand.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Benefit 1 -->
                    <div class="p-6 bg-white border border-brand-gold/20 rounded-2xl space-y-3">
                        <h3 class="font-serif font-bold text-brand-teal text-lg">Identify Your Needs</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Use this time to discuss your current challenges, spiritual questions, or feelings of imbalance. We'll help you articulate your healing goals.
                        </p>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="p-6 bg-white border border-brand-gold/20 rounded-2xl space-y-3">
                        <h3 class="font-serif font-bold text-brand-teal text-lg">Experience Our Method</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Get a taste of our Sacred Listening technique. This session is designed to give you initial clarity and peace within a brief period.
                        </p>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="p-6 bg-white border border-brand-gold/20 rounded-2xl space-y-3">
                        <h3 class="font-serif font-bold text-brand-teal text-lg">Zero Pressure</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            There's no expectation of booking further services. This is purely to build trust and show you how we can help.
                        </p>
                    </div>
                </div>

                <div class="bg-brand-teal p-8 rounded-2xl text-center space-y-4">
                    <p class="text-lg font-serif font-semibold text-white">
                        Ready to take the first step towards clarity?
                    </p>
                    <a href="{{ route('contact') }}" class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-10 py-3.5 rounded-full font-semibold text-sm transition shadow">
                        Book Your FREE 30-Minute Session
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection