   @extends('themes.layouts.app')

    @section('content')

    <!-- HEADER / NAVIGATION (Consistent with other pages) -->
    <header class="relative bg-indigo-900 pt-32 pb-20 overflow-hidden">
        
        <!-- Navigation Bar (Identical to index.html) -->
         @include('Themes.layouts.nav')
        
        <!-- Dark Overlay for subtle background image -->
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('https://picsum.photos/1600/900?random=11');"></div>
        <div class="absolute inset-0 bg-indigo-900 opacity-70"></div>

        <!-- Content Container (Banner Title) -->
        <div class="relative z-10 text-center max-w-4xl mx-auto p-6 md:p-8">
            <h1 class="text-5xl md:text-6xl font-extrabold text-white leading-tight tracking-tight">
                Our Mission: Guided by Inner Peace
            </h1>
            <p class="text-xl md:text-2xl text-indigo-100 mt-4 max-w-3xl mx-auto">
                DK Healing Center is dedicated to restoring spiritual balance and empowering individuals through personalized guidance and sacred practices.
            </p>
        </div>
    </header>
    <!-- HEADER END -->

    <main class="py-20 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto space-y-20">

            <!-- SECTION 1: THE FOUNDING STORY / WHAT WE DO -->
            <section class="bg-white p-8 md:p-16 rounded-3xl shadow-2xl border-b-4 border-indigo-500">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    
                    <!-- Text Content -->
                    <div class="order-2 lg:order-1">
                        <h2 class="text-4xl font-extrabold text-gray-900 mb-6">
                            A Journey of Spiritual Restoration
                        </h2>
                        <p class="text-lg text-gray-600 mb-4 leading-relaxed">
                            The **DK Healing Center** was founded on the belief that true healing stems from aligning one's inner spiritual self with the outer world. We recognized the modern need for authentic, grounded spiritual guidance that respects individual journeys.
                        </p>
                        <p class="text-lg text-gray-600 leading-relaxed italic border-l-4 border-indigo-400 pl-4 py-2">
                            "Our methods are ancient, our application is modern. We bridge tradition and contemporary life to achieve lasting clarity."
                        </p>
                        <p class="text-lg text-gray-600 mt-4 leading-relaxed">
                            We don't just offer services; we offer a **partnership** in your pursuit of inner harmony. Our personalized approach, utilizing techniques like Sacred Listening, Rukiya, and Istikhara, is designed to dissolve spiritual blocks and fortify your resilience.
                        </p>
                    </div>
                    
                    <!-- Image -->
                    <div class="lg:w-full order-1 lg:order-2">
                        <img 
                            src="https://picsum.photos/800/600?random=12" 
                            alt="A calm, spiritual setting with soft light, symbolizing healing." 
                            class="w-full h-auto object-cover rounded-xl shadow-xl transform hover:scale-[1.01] transition duration-500"
                        >
                    </div>
                </div>
            </section>

            <!-- SECTION 2: CORE VALUES -->
            <section class="bg-indigo-50 py-16 px-4 rounded-3xl shadow-inner">
                 <h2 class="text-4xl font-extrabold text-gray-900 text-center mb-12">
                    Our Guiding Principles
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    
                    <!-- Value 1: Integrity -->
                    <div class="text-center p-8 bg-white rounded-xl shadow-lg border-t-4 border-indigo-600 transition duration-300 hover:shadow-indigo-300/50">
                        <!-- Icon (lucide-shield-check) -->
                        <svg class="w-12 h-12 text-indigo-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Absolute Integrity</h3>
                        <p class="text-gray-600">
                            We uphold the highest ethical standards, ensuring all guidance is transparent, authentic, and delivered with genuine care and honesty.
                        </p>
                    </div>
                    
                    <!-- Value 2: Compassion -->
                    <div class="text-center p-8 bg-white rounded-xl shadow-lg border-t-4 border-indigo-600 transition duration-300 hover:shadow-indigo-300/50">
                        <!-- Icon (lucide-heart) -->
                        <svg class="w-12 h-12 text-indigo-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Unwavering Compassion</h3>
                        <p class="text-gray-600">
                            Every journey is met with empathy. We treat each individual with kindness and respect, creating a supportive, non-judgmental healing environment.
                        </p>
                    </div>
                    
                    <!-- Value 3: Clarity -->
                    <div class="text-center p-8 bg-white rounded-xl shadow-lg border-t-4 border-indigo-600 transition duration-300 hover:shadow-indigo-300/50">
                        <!-- Icon (lucide-lightbulb) -->
                        <svg class="w-12 h-12 text-indigo-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .6-2 1-3 1-2 2-3 3-3"/><path d="M10 14c-.2-1-.6-2-1-3-1-2-2-3-3-3"/><path d="M12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2Z"/><path d="M10 17H7.76l-.34.34a1 1 0 0 0 0 1.41l1.41 1.41a1 1 0 0 0 1.41 0l1.41-1.41a1 1 0 0 0 0-1.41L12 17h-2Z"/></svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Pinnacle Clarity</h3>
                        <p class="text-gray-600">
                            Our purpose is to help you cut through confusion and doubt, providing clear spiritual insights that enable confident decision-making and direction.
                        </p>
                    </div>
                </div>
            </section>
            
        </div>
    </main>

    @endsection