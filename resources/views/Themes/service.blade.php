   @extends('Themes.layouts.app')

    @section('content')

    <!-- HEADER / NAVIGATION (Consistent with index.html) -->
    <header class="relative bg-indigo-900 pt-32 pb-20 overflow-hidden">
        
        <!-- Navigation Bar (Identical to index.html) -->
        @include('Themes.layouts.nav')
        
        <!-- Dark Overlay for subtle background image -->
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('https://picsum.photos/1600/900?random=5');"></div>
        <div class="absolute inset-0 bg-indigo-900 opacity-70"></div>

        <!-- Content Container (Banner Title) -->
        <div class="relative z-10 text-center max-w-4xl mx-auto p-6 md:p-8">
            <h1 class="text-5xl md:text-6xl font-extrabold text-white leading-tight tracking-tight">
                Your Path to Spiritual Healing
            </h1>
            <p class="text-xl md:text-2xl text-indigo-100 mt-4 max-w-3xl mx-auto">
                We offer three core, personalized services designed to guide you toward clarity, restoration, and inner peace.
            </p>
        </div>
    </header>
    <!-- HEADER END -->

    <main class="py-20 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto space-y-24">

            <!-- SERVICE 1: Sacred Listening and Personal Guidance (Text Left, Image Right) -->
            <section class="bg-white p-8 md:p-12 rounded-3xl shadow-2xl flex flex-col lg:flex-row items-center gap-10 border-t-8 border-indigo-600">
                <div class="lg:w-1/2">
                    <div class="flex items-center text-indigo-600 mb-4">
                        <svg class="w-10 h-10 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20.3c-.9 1.1-2.2 1.7-3.6 1.7H4c-1.1 0-2-.9-2-2v-4c0-.9.6-1.7 1.4-1.9L22 4"/><path d="M12 12V3h10v9"/></svg>
                        <h2 class="text-4xl font-extrabold text-gray-900">
                            1. Sacred Listening & Guidance
                        </h2>
                    </div>
                    
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        This is the **foundational step** in your journey toward clarity. We provide a confidential, non-judgmental space for deep dialogue and spiritual consultation, focusing entirely on understanding your unique story and current emotional or spiritual challenges. We help uncover the root causes of imbalance.
                    </p>
                    
                    <ul class="list-disc list-inside text-gray-700 space-y-2 pl-4 mb-8">
                        <li>In-depth conversational intake.</li>
                        <li>Guided advice for immediate clarity.</li>
                        <li>Preparation for deeper healing rituals.</li>
                    </ul>

                    <a href="{{ route("service", ["name" => "counseling"]) }}" class="inline-block px-8 py-4 text-lg font-bold text-white bg-indigo-600 rounded-full shadow-lg shadow-indigo-500/50 hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                        Book Your Consultation Now
                    </a>
                </div>
                
                <div class="lg:w-1/2 flex justify-center lg:justify-end">
                    <img 
                        src="https://picsum.photos/800/600?random=13" 
                        alt="A calm setting with hands gently clasped, symbolizing listening and consultation." 
                        class="w-full max-w-md h-auto object-cover rounded-2xl shadow-xl border border-indigo-200"
                    >
                </div>
            </section>

            <!-- SERVICE 2: Personalized Rukiya: Spiritual Restoration (Image Left, Text Right - Alternating Layout) -->
            <section class="bg-gray-100 p-8 md:p-12 rounded-3xl shadow-2xl flex flex-col lg:flex-row items-center gap-10 border-t-8 border-indigo-600">
                <div class="lg:w-1/2 flex justify-center lg:justify-start order-2 lg:order-1">
                     <img 
                        src="https://picsum.photos/800/600?random=14" 
                        alt="A picture symbolizing spiritual purification, like a clear stream or light." 
                        class="w-full max-w-md h-auto object-cover rounded-2xl shadow-xl border border-indigo-300"
                    >
                </div>
                
                <div class="lg:w-1/2 order-1 lg:order-2">
                    <div class="flex items-center text-indigo-600 mb-4">
                        <svg class="w-10 h-10 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.7 2.8"/><path d="M2 15h2c.7 0 1.2.3 1.5.8L7 18"/><path d="M22 15h-2c-.7 0-1.2-.3-1.5-.8L17 12"/></svg>
                        <h2 class="text-4xl font-extrabold text-gray-900">
                            2. Personalized Rukiya
                        </h2>
                    </div>

                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Rukiya is our comprehensive **purification and spiritual healing process**. It is customized based on your initial consultation to precisely target and clear negative energies, neutralize spiritual blocks, and dissolve long-standing emotional wounds.
                    </p>
                    
                    <ul class="list-disc list-inside text-gray-700 space-y-2 pl-4 mb-8">
                        <li>Customized cleansing protocol based on need.</li>
                        <li>Guided practices for spiritual and emotional fortification.</li>
                        <li>Designed to achieve profound, lasting inner peace.</li>
                    </ul>

                    <a href="{{ route("service", ["name" => "rukiya"]) }}" class="inline-block px-8 py-4 text-lg font-bold text-white bg-indigo-600 rounded-full shadow-lg shadow-indigo-500/50 hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                        Start Your Restoration
                    </a>
                </div>
            </section>

            <!-- SERVICE 3: Istikhara: Divine Guidance (Text Left, Image Right) -->
            <section class="bg-white p-8 md:p-12 rounded-3xl shadow-2xl flex flex-col lg:flex-row items-center gap-10 border-t-8 border-indigo-600">
                <div class="lg:w-1/2">
                    <div class="flex items-center text-indigo-600 mb-4">
                        <svg class="w-10 h-10 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9 9 0 0 0-9 9Z"/><path d="M12 3v18"/><path d="M3 12h18"/><path d="m14 10-2 4-2-4 4-2Z"/></svg>
                        <h2 class="text-4xl font-extrabold text-gray-900">
                            3. Istikhara: Divine Guidance
                        </h2>
                    </div>
                    
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        When facing a major life decision—be it career, relationship, or relocation—Istikhara is a sacred spiritual method to seek **benevolent counsel from the Divine**. We guide you through the dedicated process to ensure your chosen path aligns with your highest purpose.
                    </p>
                    
                    <ul class="list-disc list-inside text-gray-700 space-y-2 pl-4 mb-8">
                        <li>Process for clarity on life-altering decisions.</li>
                        <li>Removes doubt and grants peace of mind.</li>
                        <li>Move forward with confidence and spiritual approval.</li>
                    </ul>

                    <a href="{{ route("service", ["name" => "istekhara"]) }}" class="inline-block px-8 py-4 text-lg font-bold text-white bg-indigo-600 rounded-full shadow-lg shadow-indigo-500/50 hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                        Request Guidance Now
                    </a>
                </div>
                
                <div class="lg:w-1/2 flex justify-center lg:justify-end">
                    <img 
                        src="https://picsum.photos/800/600?random=15" 
                        alt="A picture of a compass or path in nature, symbolizing divine direction." 
                        class="w-full max-w-md h-auto object-cover rounded-2xl shadow-xl border border-indigo-200"
                    >
                </div>
            </section>
            
        </div>
    </main>

    @endsection
 
