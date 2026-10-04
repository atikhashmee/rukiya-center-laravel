@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- HERO SECTION -->
    <section class="relative py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-block px-3 py-1 bg-brand-gold/15 text-brand-goldDark text-xs font-semibold tracking-wider rounded-md uppercase">
                        কুরআন ও সুন্নাহভিত্তিক নির্ভরযোগ্য সহায়তা
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-brand-teal leading-tight">
                        বিশুদ্ধ রুকইয়াহ ও <span class="italic text-brand-gold">ইসলামিক আধ্যাত্মিক দিকনির্দেশনা</span>
                    </h1>
                    <p class="text-slate-600 max-w-xl mx-auto lg:mx-0 text-sm sm:text-base">
                       কুরআন ও সুন্নাহর আলোকে রুকইয়াহ, ইস্তিখারা পরামর্শ এবং ইসলামিক আধ্যাত্মিক সহায়তা — বাংলাদেশের ব্যক্তি ও পরিবারের জন্য, অনলাইনে দেশের বাইরেও।
                     <div class="border-l-4 border-brand-gold pl-4 my-6">
    <p class="text-2xl text-brand-teal font-arabic leading-loose">
        وَنُنَزِّلُ مِنَ الْقُرْآنِ مَا هُوَ شِفَاءٌ وَرَحْمَةٌ لِّلْمُؤْمِنِينَ
    </p>

    <p class="mt-2 text-sm italic text-slate-600">
        “আর আমি কুরআনে এমন বিষয় অবতীর্ণ করি যা মুমিনদের জন্য আরোগ্য ও রহমত।”
    </p>

    <p class="text-xs text-brand-gold font-semibold mt-1">
        সূরা আল-ইসরা (১৭:৮২)
    </p>
</div>  
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('wizard.index') }}" class="w-full sm:w-auto text-center bg-brand-gold hover:bg-brand-goldDark text-white px-8 py-3.5 rounded-full font-semibold transition shadow">
                            রুকইয়াহ সেশন বুক করুন
                        </a>
                        <a href="{{ route('wizard.index') }}" class="w-full sm:w-auto text-center border-2 border-brand-teal text-brand-teal hover:bg-brand-teal hover:text-white px-8 py-3 rounded-full font-semibold transition">
                            আমাদের সেবাসমূহ দেখুন
                        </a>
                    </div>
                </div>
                 <div class="lg:col-span-5 space-y-6">
                    <img
    src="{{ asset('images/hero-ruqyah.png') }}"
    alt="কুরআন ও সুন্নাহভিত্তিক রুকইয়াহ সেবা"
    class="w-full h-80 object-cover rounded-2xl shadow-xl border border-brand-gold/20">
    <div class="bg-brand-cream border border-brand-gold/20 rounded-2xl p-6 text-center space-y-8">
                    <span class="text-xs font-bold text-brand-crimson tracking-widest uppercase block">⚠️ সম্মানজনক পরামর্শ নীতিমালা</span>
                    <h3 class="text-lg font-serif font-bold text-brand-teal">নিরাপদ, সম্মানজনক ও গোপনীয় সেবা</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        প্রতিটি পরামর্শ পেশাদারিত্ব, গোপনীয়তা এবং বিশুদ্ধ ইসলামি নীতিমালা মেনে পরিচালিত হয়।
                        <div class="flex flex-wrap justify-center lg:justify-start gap-4 text-sm font-medium text-brand-teal">
    <span>✓ কুরআন ও সুন্নাহভিত্তিক</span>
    <span>✓ বাংলাদেশে সেবা</span>
    <span>✓ অনলাইনে দেশ-বিদেশে পরামর্শ</span>
    <span>✓ ব্যক্তিগত ও গোপনীয়</span>
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
                <span class="text-brand-crimson text-xs font-bold tracking-widest uppercase">আমাদের সেবাসমূহ</span>
                <h2 class="text-3xl font-serif font-bold text-brand-teal">আমাদের ইসলামিক সেবাসমূহ</h2>
                <p class="text-sm text-slate-500">কুরআন ও বিশুদ্ধ সুন্নাহর ভিত্তিতে সহানুভূতিশীল ইসলামিক সহায়তা। প্রতিটি সেবা গোপনীয়তা ও পেশাদারিত্বের সঙ্গে প্রদান করা হয়।</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service Card 1: Counseling -->
                <div class="bg-white rounded-2xl border border-brand-gold/20 p-8 h-full flex flex-col">
                    <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20.3c-.9 1.1-2.2 1.7-3.6 1.7H4c-1.1 0-2-.9-2-2v-4c0-.9.6-1.7 1.4-1.9L22 4"/><path d="M12 12V3h10v9"/></svg>
                    </div>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">ইসলামিক কাউন্সেলিং</h3>
                    <p class="text-xs text-slate-500 leading-relaxed flex-grow">
                        আবেগজনিত, আধ্যাত্মিক ও ব্যক্তিগত সমস্যায় প্রজ্ঞা ও সহানুভূতির সঙ্গে পথ দেখাতে গোপনীয় একান্ত ইসলামিক কাউন্সেলিং।
                    </p>
                    <a href="{{ route('service', ['name' => 'counseling']) }}" class="inline-block text-brand-gold text-sm font-semibold hover:text-brand-goldDark transition">বিস্তারিত দেখুন →</a>
                </div>

                <!-- Service Card 2: Rukiya -->
                <div class="bg-white rounded-2xl border border-brand-gold/20 p-8 h-full flex flex-col">
                    <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.7 2.8"/><path d="M2 15h2c.7 0 1.2.3 1.5.8L7 18"/><path d="M22 15h-2c-.7 0-1.2-.3-1.5-.8L17 12"/></svg>
                    </div>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">বিশুদ্ধ রুকইয়াহ</h3>
                    <p class="text-xs text-slate-500 leading-relaxed flex-grow">
                        আধ্যাত্মিক আরোগ্য, সুরক্ষা ও প্রশান্তির জন্য কুরআন ও সুন্নাহভিত্তিক বিশুদ্ধ রুকইয়াহ, আন্তরিকতা ও পেশাদারিত্বের সঙ্গে পরিচালিত।
                    </p>
                    <a href="{{ route('service', ['name' => 'rukiya']) }}" class="inline-block text-brand-gold text-sm font-semibold hover:text-brand-goldDark transition">বিস্তারিত দেখুন →</a>
                </div>

                <!-- Service Card 3: Istekhara -->
                <div class="bg-white rounded-2xl border border-brand-gold/20 p-8 h-full flex flex-col">
                    <div class="w-12 h-12 bg-brand-teal/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-teal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9 9 0 0 0-9 9Z"/><path d="M12 3v18"/><path d="M3 12h18"/><path d="m14 10-2 4-2-4 4-2Z"/></svg>
                    </div>
                    <h3 class="font-serif font-bold text-brand-teal text-lg">ইস্তিখারা দিকনির্দেশনা</h3>
                    <p class="text-xs text-slate-500 leading-relaxed flex-grow">
                        জীবনের গুরুত্বপূর্ণ সিদ্ধান্তের আগে বিশুদ্ধ ইস্তিখারা ও বাস্তবভিত্তিক ইসলামিক পরামর্শের মাধ্যমে আল্লাহর নির্দেশনা প্রার্থনা করুন।
                    </p>
                    <a href="{{ route('service', ['name' => 'istekhara']) }}" class="inline-block text-brand-gold text-sm font-semibold hover:text-brand-goldDark transition">বিস্তারিত দেখুন →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HEALING PROCESS SECTION -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <h2 class="text-3xl font-serif font-bold text-brand-teal">পরামর্শ প্রক্রিয়া যেভাবে চলে </h2>
                <p class="text-sm text-slate-500">প্রতিটি পরামর্শ সহানুভূতি, গোপনীয়তা এবং কুরআন ও বিশুদ্ধ সুন্নাহর কঠোর অনুসরণে পরিচালিত হয়।</p>
            </div>

            <div class="w-full max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Step 1 -->
        <div class="w-full p-8 bg-brand-cream/50 rounded-2xl border border-brand-gold/20 space-y-4">
            <span class="block text-4xl font-serif font-bold text-brand-gold/30">
                01
            </span>

            <h3 class="font-serif font-bold text-brand-teal text-lg">
                📋 প্রাথমিক পরামর্শ
            </h3>

            <p class="text-sm text-slate-500 leading-relaxed">
                আমরা শুরু করি একটি গোপনীয় পরামর্শ দিয়ে, যেন আপনার সমস্যা, প্রাসঙ্গিক পটভূমি,
                আধ্যাত্মিক পরীক্ষা ও ব্যক্তিগত লক্ষ্য বুঝতে পারি।
            </p>
        </div>

        <!-- Step 2 -->
        <div class="w-full p-8 bg-brand-cream/50 rounded-2xl border border-brand-gold/20 space-y-4">
            <span class="block text-4xl font-serif font-bold text-brand-gold/30">
                02
            </span>

            <h3 class="font-serif font-bold text-brand-teal text-lg">
                📖 বিশুদ্ধ রুকইয়াহ সেশন
            </h3>

            <p class="text-sm text-slate-500 leading-relaxed">
                রুকইয়াহ প্রযোজ্য হলে বিশুদ্ধ কুরআন তিলাওয়াত, নববী দুআ ও ইসলামিক
                দিকনির্দেশনার মাধ্যমে সেশন পরিচালনা করা হয়।
            </p>
        </div>

        <!-- Step 3 -->
        <div class="w-full p-8 bg-brand-cream/50 rounded-2xl border border-brand-gold/20 space-y-4">
            <span class="block text-4xl font-serif font-bold text-brand-gold/30">
                03
            </span>

            <h3 class="font-serif font-bold text-brand-teal text-lg">
                🤝 পরবর্তী সহায়তা
            </h3>

            <p class="text-sm text-slate-500 leading-relaxed">
                আমরা বাস্তবভিত্তিক পরামর্শ, প্রয়োজনীয় আযকার, দুআ ও ধারাবাহিক দিকনির্দেশনা দিই,
                যেন আপনি আত্মবিশ্বাস ও আল্লাহর উপর ভরসা নিয়ে এগিয়ে যেতে পারেন।
            </p>
        </div>

    </div>
</div>

    </section>

    <!-- TESTIMONIALS SECTION -->
    <section id="about" class="py-20 bg-brand-cream/50 border-t border-brand-gold/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <h2 class="text-3xl font-serif font-bold text-brand-teal">ক্লায়েন্টদের অভিজ্ঞতা</h2>
                <p class="text-xs text-slate-500 text-center mt-8 italic">
মতামতগুলো ব্যক্তিগত অভিজ্ঞতার প্রতিফলন। ফলাফল ব্যক্তিভেদে ও আল্লাহর ইচ্ছা অনুযায়ী ভিন্ন হতে পারে।
</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="pt-10 pb-8 px-8 bg-white border border-brand-gold/10 rounded-2xl space-y-5 shadow-sm">
               <div class="text-brand-gold text-6xl leading-none opacity-40 mb-6">
    &ldquo;
</div>
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed">
                        “আলহামদুলিল্লাহ, পরামর্শটি ছিল সহানুভূতিশীল, পেশাদার এবং কুরআন ও বিশুদ্ধ সুন্নাহর উপর প্রতিষ্ঠিত। দিকনির্দেশনা মনে প্রশান্তি এনেছে এবং সামনে এগোনোর বাস্তব পথ দেখিয়েছে।”
                    </p>
                    <div class="flex gap-1 text-brand-gold text-sm mb-4">
    ⭐⭐⭐⭐⭐
</div>
<div class="w-16 h-0.5 bg-brand-gold rounded-full my-4"></div>
                    <span class="block text-xs tracking-wide uppercase font-semibold text-brand-teal">
    — একজন বোন
</span>
                </div>
                <div class="pt-10 pb-8 px-8 bg-white border border-brand-gold/10 rounded-2xl space-y-5 shadow-sm">
                <div class="text-brand-gold text-6xl leading-none opacity-40 mb-6">
    &ldquo;
</div>
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed">
                        “পুরো প্রক্রিয়াটি সুসংগঠিত। অনেক অনিয়ন্ত্রিত জায়গার মতো নয় — এখানে প্রতিটি ধাপ স্পষ্টভাবে বুঝিয়ে বলা হয়েছে। সম্পূর্ণ স্বচ্ছতা ও আহলুস সুন্নাহর বিশুদ্ধ আমল।”
                    </p>
                    <div class="flex gap-1 text-brand-gold text-sm mb-4">
    ⭐⭐⭐⭐⭐
</div>
<div class="w-16 h-0.5 bg-brand-gold rounded-full my-4"></div>
                    <span class="block text-xs tracking-wide uppercase font-semibold text-brand-teal">— একজন ভাই</span>
                </div>
                <div class="pt-10 pb-8 px-8 bg-white border border-brand-gold/10 rounded-2xl space-y-5 shadow-sm">
                <div class="text-brand-gold text-6xl leading-none opacity-40 mb-6">
    &ldquo;
</div>
                    <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed">
                        “আমি এখন অনেক হালকা ও স্বচ্ছ বোধ করছি। এখানকার দিকনির্দেশনা বিশুদ্ধ, শক্তিশালী এবং কোমলভাবে আরোগ্যের পথ দেখায়।”
                    </p>
                    <div class="flex gap-1 text-brand-gold text-sm mb-4">
    ⭐⭐⭐⭐⭐
</div>
<div class="w-16 h-0.5 bg-brand-gold rounded-full my-4"></div>
                    <span class="block text-xs tracking-wide uppercase font-semibold text-brand-teal">— একটি পরিবার</span>
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
“আর যখন আমি অসুস্থ হই, তখন তিনিই আমাকে সুস্থ করেন।”
</p>

<p class="text-sm text-slate-500 mb-10">
সূরা আশ-শুআরা (২৬:৮০)
</p>

        <h2 class="text-4xl font-serif font-bold text-brand-teal mb-4">
           আত্মবিশ্বাসের সঙ্গে আপনার যাত্রা শুরু করুন
        </h2>

        <p class="text-slate-600 max-w-2xl mx-auto mb-10">
            কুরআন ও বিশুদ্ধ সুন্নাহর ভিত্তিতে সহানুভূতিশীল ও গোপনীয় ইসলামিক দিকনির্দেশনা নিন।
        </p>

        <a href="{{ route('wizard.index') }}"
           class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-10 py-4 rounded-full font-semibold transition">
            পরামর্শের জন্য বুক করুন
        </a>

    </div>
</section>
</section>

    <!-- NEWSLETTER SECTION -->
<section class="py-16 bg-brand-teal">
    <div class="max-w-3xl mx-auto text-center px-4 sm:px-6 lg:px-8 space-y-6">

        <h3 class="text-2xl font-serif font-bold text-white">
            যোগাযোগে থাকুন 
        </h3>

        <p class="text-sm text-slate-300 max-w-2xl mx-auto">
            কুরআন ও সুন্নাহভিত্তিক সুস্থতার পরামর্শ, উপকারী লেখা এবং আমাদের সেবার হালনাগাদ তথ্য মাঝেমধ্যে ইমেইলে পান।
        </p>

        <form action="#" method="POST"
              class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">

            @csrf

            <label for="newsletter-email" class="sr-only">
                ইমেইল ঠিকানা
            </label>

            <input
                id="newsletter-email"
                type="email"
                name="email"
                required
                autocomplete="email"
                placeholder="আপনার ইমেইল ঠিকানা"
                class="w-full sm:flex-1 px-5 py-3 rounded-xl text-sm text-slate-800
                       focus:outline-none focus:ring-2 focus:ring-brand-gold"
            >

            <button
                type="submit"
                class="w-full sm:w-auto px-8 py-3 bg-brand-gold
                       hover:bg-brand-goldDark text-white rounded-xl
                       text-sm font-semibold transition">
                সাবস্ক্রাইব
            </button>

        </form>

        <p class="text-xs text-white/70">
            সাবস্ক্রাইব করলে আপনি ডিকে হিলিং সেন্টার থেকে মাঝেমধ্যে ইমেইল পেতে সম্মত হচ্ছেন। যেকোনো সময় আনসাবস্ক্রাইব করতে পারবেন।
        </p>

    </div>
</section>
@endsection