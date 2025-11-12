    @extends('Themes.layouts.app')

    @section('content')

        <!-- HERO SECTION START -->
        <header class="relative h-screen flex items-center justify-center overflow-hidden">
            @include('Themes.layouts.nav')
            <!-- Background Image Container -->
            <div 
                class="absolute inset-0 bg-cover bg-center transition-opacity duration-700"
                style="
                    background-image: url('https://picsum.photos/1600/900?random=1');
                    /* Ensure image scales well and covers the whole area */
                "
            >
            </div>
            
            <!-- Dark Overlay for better text readability (using a subtle indigo overlay) -->
            <div class="absolute inset-0 bg-indigo-900 opacity-70"></div>

            <!-- Content Container -->
            <div class="relative z-10 text-center max-w-4xl mx-auto p-6 md:p-8">
                
                <!-- Main Title (Updated for spiritual theme) -->
                <h1 class="text-5xl md:text-7xl font-extrabold text-white leading-tight mb-4 tracking-tight">
                    Unlock Your <span class="text-indigo-300">Inner Harmony</span> and Peace
                </h1>

                <!-- Subheading / Description -->
                <p class="text-xl md:text-2xl text-indigo-100 mb-10 max-w-3xl mx-auto">
                    Discover a personalized spiritual healing system designed for profound self-discovery and lasting transformation.
                </p>

                <!-- Call to Action Button -->
                <a 
                    href="{{ route("services") }}" 
                    class="inline-block px-10 py-4 text-lg font-semibold text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition duration-300 ease-in-out transform hover:scale-105"
                >
                    Begin Your Journey
                </a>
                
            </div>
        </header>
        <!-- HERO SECTION END -->

        <!-- NEW SERVICES SECTION START (Content Updated for spiritual theme) -->
        <main id="services" class="py-20 px-4 sm:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-4xl font-extrabold text-gray-900 text-center mb-16">
                    Our Healing Offerings
                </h2>

                <!-- Service Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <!-- Service Card 1: Counseling -->
                    <div class="bg-gray-50 p-8 rounded-xl shadow-2xl transition duration-300 hover:shadow-indigo-300/50 hover:scale-[1.02]">
                        <div class="text-indigo-600 mb-4">
                            <!-- Icon Placeholder (lucide-message-circle) -->
                            <svg class="w-12 h-12" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20.3c-.9 1.1-2.2 1.7-3.6 1.7H4c-1.1 0-2-.9-2-2v-4c0-.9.6-1.7 1.4-1.9L22 4"/><path d="M12 12V3h10v9"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Sacred Listening and Personal Guidance</h3>
                        <p class="text-gray-600">
                            A confidential space for deep dialogue and spiritual consultation. We provide **compassionate listening** to your challenges and offer guided, personalized advice to navigate life's complexities with clarity and faith.
                        </p>
                        <!-- REMOVED BOOK NOW BUTTON -->
                    </div>

                    <!-- Service Card 2: Rukiya -->
                    <div class="bg-gray-50 p-8 rounded-xl shadow-2xl transition duration-300 hover:shadow-indigo-300/50 hover:scale-[1.02]">
                        <div class="text-indigo-600 mb-4">
                            <!-- Icon Placeholder (lucide-refresh-cw for Renewal/Purification) -->
                            <svg class="w-12 h-12" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.7 2.8"/><path d="M2 15h2c.7 0 1.2.3 1.5.8L7 18"/><path d="M22 15h-2c-.7 0-1.2-.3-1.5-.8L17 12"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Personalized Rukiya: Spiritual Restoration</h3>
                        <p class="text-gray-600">
                            This is the core purification and **healing process**. It involves prescribed spiritual disciplines (like prayer and fasting) combined with therapeutic natural remedies to cleanse energetic imbalances and fortify your spiritual health.
                        </p>
                        <!-- REMOVED BOOK NOW BUTTON -->
                    </div>

                    <!-- Service Card 3: Estekhara -->
                    <div class="bg-gray-50 p-8 rounded-xl shadow-2xl transition duration-300 hover:shadow-indigo-300/50 hover:scale-[1.02]">
                        <div class="text-indigo-600 mb-4">
                            <!-- Icon Placeholder (lucide-compass for Guidance/Direction) -->
                            <svg class="w-12 h-12" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9 9 0 0 0-9 9Z"/><path d="M12 3v18"/><path d="M3 12h18"/><path d="m14 10-2 4-2-4 4-2Z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Istikhara: Divine Guidance for Life Decisions</h3>
                        <p class="text-gray-600">
                            A sacred process of seeking benevolent counsel from the Divine before embarking on major life choices. We guide you through the dedicated spiritual method to seek **clarity, peace of mind**, and confidence in the path you choose.
                        </p>
                        <!-- REMOVED BOOK NOW BUTTON -->
                    </div>

                </div>
            </div>
        </main>
        <!-- NEW SERVICES SECTION END -->

        <!-- HEALING PROCESS SECTION START -->
        <section id="counseling" class="py-20 px-4 sm:px-8 bg-gray-50">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-4">
                    The Journey to Inner Harmony
                </h2>
                <p class="text-xl text-gray-600 mb-12">
                    Our system follows a guided two-step approach designed to provide deep understanding and lasting spiritual restoration.
                </p>

                <!-- Process Steps Container (Side-by-side on desktop, stacked on mobile) -->
                <div class="space-y-8 md:space-y-0 md:flex md:space-x-8">

                    <!-- Step 1: Counseling -->
                    <div class="md:w-1/2 p-8 bg-white rounded-2xl shadow-xl border-t-4 border-indigo-500 transition duration-300 hover:shadow-2xl hover:scale-[1.02]">
                        <div class="text-indigo-600 mb-4">
                            <span class="text-5xl font-extrabold">01</span>
                            <!-- Icon for Counseling/Talking (lucide-users) -->
                            <svg class="w-12 h-12 mx-auto mt-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Initial Counseling & Assessment</h3>
                        <p class="text-gray-600 text-left">
                            The journey begins with a confidential **counseling session**. We take the time to deeply understand your current challenges, spiritual blocks, and personal history. This assessment is foundational for tailor-making your healing path.
                        </p>
                    </div>

                    <!-- Separation Icon/Arrow (Hidden on Mobile, serves as visual flow indicator) -->
                    <div class="hidden md:flex items-center justify-center">
                        <svg class="w-10 h-10 text-indigo-500 transform rotate-90 md:rotate-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </div>

                    <!-- Step 2: Meditation and Healing -->
                    <div class="md:w-1/2 p-8 bg-white rounded-2xl shadow-xl border-t-4 border-indigo-500 transition duration-300 hover:shadow-2xl hover:scale-[1.02]">
                        <div class="text-indigo-600 mb-4">
                            <span class="text-5xl font-extrabold">02</span>
                            <!-- Icon for Meditation/Healing (lucide-lotus) -->
                            <svg class="w-12 h-12 mx-auto mt-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.8 9.2a4 4 0 0 0-5.8 0L12 10.2l-1.09-1.09a4 4 0 0 0-5.8 0 4.21 4.21 0 0 0 0 6l6.91 6.91a2 2 0 0 0 2.82 0l6.91-6.91a4.21 4.21 0 0 0 0-6z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Spiritual Meditation & Healing</h3>
                        <p class="text-gray-600 text-left">
                            If deep work is required, we move into dedicated **spiritual meditation and healing**. This phase involves personalized techniques to clear energetic imbalances, facilitate profound self-discovery, and achieve lasting internal peace and transformation.
                        </p>
                    </div>
                    
                </div>
            </div>
        </section>
        <!-- HEALING PROCESS SECTION END -->

        <!-- NEW TESTIMONIALS SECTION START -->
        <section id="about" class="py-20 px-4 sm:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-4xl font-extrabold text-gray-900 text-center mb-4">
                    Customer Feedbacks
                </h2>
                <p class="text-xl text-gray-600 text-center mb-16 max-w-2xl mx-auto">
                    Read what our clients have achieved through clarity, guidance, and spiritual alignment.
                </p>

                <!-- Testimonials Grid -->
                <div class="grid grid-cols-1 gap-10 md:grid-cols-3">

                    <!-- Testimonial Card 1 -->
                    <div class="bg-gray-50 p-8 rounded-2xl shadow-xl border-t-4 border-indigo-500 transition duration-300">
                        <p class="text-xl italic text-gray-800 mb-6 leading-relaxed">
                            "A truly life-changing experience. I finally found the clarity and spiritual peace I had been searching for. The personalized approach made all the difference."
                        </p>
                        <p class="text-right text-sm font-semibold text-indigo-600">
                            — <span class="italic font-medium text-gray-700">Sarah K., Designer</span>
                        </p>
                    </div>

                    <!-- Testimonial Card 2 -->
                    <div class="bg-gray-50 p-8 rounded-2xl shadow-xl border-t-4 border-indigo-500 transition duration-300">
                        <p class="text-xl italic text-gray-800 mb-6 leading-relaxed">
                            "The initial counseling was incredibly insightful, leading to a profoundly transformative meditation process. Highly recommend this path to anyone feeling spiritually stuck."
                        </p>
                        <p class="text-right text-sm font-semibold text-indigo-600">
                            — <span class="italic font-medium text-gray-700">Mark T., Entrepreneur</span>
                        </p>
                    </div>

                    <!-- Testimonial Card 3 -->
                    <div class="bg-gray-50 p-8 rounded-2xl shadow-xl border-t-4 border-indigo-500 transition duration-300">
                        <p class="text-xl italic text-gray-800 mb-6 leading-relaxed">
                            "I feel lighter, clearer, and far more aligned with my true self. The guidance offered here is authentic, powerful, and gently directs you toward healing."
                        </p>
                        <p class="text-right text-sm font-semibold text-indigo-600">
                            — <span class="italic font-medium text-gray-700">Elena R., Yoga Instructor</span>
                        </p>
                    </div>

                </div>
            </div>
        </section>
        <!-- NEW TESTIMONIALS SECTION END -->

        <!-- NEW NEWSLETTER SECTION START -->
        <section id="contact" class="py-16 px-4 sm:px-8 bg-indigo-50">
            <div class="max-w-3xl mx-auto text-center p-8 rounded-3xl shadow-2xl bg-white">
                <h3 class="text-3xl font-bold text-gray-900 mb-3">
                    Stay Aligned: Get Our Spiritual Updates
                </h3>
                <p class="text-lg text-gray-600 mb-8">
                    Subscribe to our newsletter for exclusive insights into new healing services, guided meditations, and special promotions.
                </p>
                
                <form action="#" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                    <input 
                        type="email" 
                        placeholder="Enter your email address"
                        aria-label="Email for newsletter subscription"
                        required
                        class="w-full sm:flex-1 p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 shadow-inner"
                    >
                    <button 
                        type="submit"
                        class="w-full sm:w-auto px-8 py-4 text-lg font-semibold text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition duration-300 ease-in-out transform hover:scale-[1.01]"
                    >
                        Subscribe
                    </button>
                </form>
            </div>
        </section>
        <!-- NEW NEWSLETTER SECTION END -->
    @endsection


