@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Page Header -->
    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">
                Get in Touch
            </h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                We are here to help you start your journey toward spiritual clarity and peace.
            </p>
        </div>
    </section>

    <!-- Contact Content -->
    <main class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- TWO-COLUMN CONTACT FORM SECTION -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 bg-brand-cream/50 border border-brand-gold/20 rounded-2xl overflow-hidden">

                <!-- Column 1: Image (Hidden on small screens) -->
                <div class="hidden md:block bg-brand-teal/10 min-h-[400px] flex items-center justify-center">
                    <svg class="w-24 h-24 text-brand-teal/20" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                </div>

                <!-- Column 2: Form -->
                <div class="p-8 md:p-12">
                    <h2 class="text-2xl font-serif font-bold text-brand-teal mb-8">Send Us a Message</h2>

                    <form action="#" method="POST" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-brand-teal mb-1">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required 
                                placeholder="your.name@example.com"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold"
                            >
                        </div>

                        <!-- Subject Field -->
                        <div>
                            <label for="subject" class="block text-xs font-bold text-brand-teal mb-1">Subject</label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject" 
                                required 
                                placeholder="Inquiry about Rukiya session"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold"
                            >
                        </div>

                        <!-- Message/Textarea Field -->
                        <div>
                            <label for="message" class="block text-xs font-bold text-brand-teal mb-1">How can we help you?</label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="5" 
                                required 
                                placeholder="I would like to know more about the personalized guidance process..."
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold"
                            ></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full py-3.5 bg-brand-teal hover:bg-brand-navy text-white font-bold rounded-xl transition shadow text-sm"
                        >
                            Send Inquiry
                        </button>
                    </form>
                </div>
            </div>

            <!-- GLOBAL LOCATIONS / ADDRESS SECTION -->
            <section class="mt-16 space-y-8">
                <h2 class="text-2xl font-serif font-bold text-brand-teal text-center">Our Locations</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    <!-- Location Card 1: Headquarters -->
                    <div class="bg-brand-cream/50 border border-brand-gold/20 p-6 rounded-2xl space-y-3">
                        <h3 class="font-serif font-bold text-brand-teal text-lg">Headquarters (Asia)</h3>
                        <p class="text-sm text-slate-600">123 Tranquility Lane, Gulshan-2,</p>
                        <p class="text-sm text-slate-600">Dhaka, Bangladesh 1212</p>
                        <div class="border-t border-brand-gold/20 pt-3 mt-3">
                            <p class="text-xs text-slate-500"><strong class="text-brand-teal">Email:</strong> asia@dkhealing.com</p>
                            <p class="text-xs text-slate-500"><strong class="text-brand-teal">Phone:</strong> +880 1700 123 456</p>
                        </div>
                    </div>

                    <!-- Location Card 2: European Office -->
                    <div class="bg-brand-cream/50 border border-brand-gold/20 p-6 rounded-2xl space-y-3">
                        <h3 class="font-serif font-bold text-brand-teal text-lg">European Office</h3>
                        <p class="text-sm text-slate-600">Unit 5, Wellness Center,</p>
                        <p class="text-sm text-slate-600">London, SW1A 0AA, UK</p>
                        <div class="border-t border-brand-gold/20 pt-3 mt-3">
                            <p class="text-xs text-slate-500"><strong class="text-brand-teal">Email:</strong> europe@dkhealing.com</p>
                            <p class="text-xs text-slate-500"><strong class="text-brand-teal">Phone:</strong> +44 20 7946 0888</p>
                        </div>
                    </div>

                    <!-- Location Card 3: North American Hub -->
                    <div class="bg-brand-cream/50 border border-brand-gold/20 p-6 rounded-2xl space-y-3">
                        <h3 class="font-serif font-bold text-brand-teal text-lg">North American Hub</h3>
                        <p class="text-sm text-slate-600">45 Spirituality Rd, Suite 100,</p>
                        <p class="text-sm text-slate-600">New York, NY 10001, USA</p>
                        <div class="border-t border-brand-gold/20 pt-3 mt-3">
                            <p class="text-xs text-slate-500"><strong class="text-brand-teal">Email:</strong> usa@dkhealing.com</p>
                            <p class="text-xs text-slate-500"><strong class="text-brand-teal">Phone:</strong> +1 212 555 1212</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection