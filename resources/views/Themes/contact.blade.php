    @extends('themes.layouts.app')

    @section('content')
    @include('themes.layouts.nav')
    <!-- Page Header -->
    <header class="bg-indigo-700 py-20 text-white text-center">
        <h1 class="text-5xl font-extrabold mb-4">Get in Touch</h1>
        <p class="text-xl max-w-3xl mx-auto px-4">
            We are here to help you start your journey toward spiritual clarity and peace.
        </p>
    </header>

    <main class="py-16 px-4 sm:px-8">
        <div class="max-w-6xl mx-auto">
            
            <!-- TWO-COLUMN CONTACT FORM SECTION -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 bg-white rounded-2xl shadow-2xl border-t-4 border-indigo-500 overflow-hidden">
                
                <!-- Column 1: Image (Hidden on small screens) -->
                <div class="hidden md:block">
                    <!-- A tall, serene placeholder image related to peace or spiritual journey -->
                    <img 
                        src="https://picsum.photos/800/1000?random=23" 
                        alt="A serene image of hands holding a glowing light, symbolizing healing and clarity." 
                        class="w-full h-full object-cover"
                    >
                </div>

                <!-- Column 2: Form -->
                <div class="p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Send Us a Message</h2>
                    
                    <form action="#" method="POST" class="space-y-6">
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required 
                                placeholder="your.name@example.com"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                            >
                        </div>
                        
                        <!-- Subject Field -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject" 
                                required 
                                placeholder="Inquiry about Rukiya session"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                            >
                        </div>
                        
                        <!-- Message/Textarea Field -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">How can we help you?</label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="5" 
                                required 
                                placeholder="I would like to know more about the personalized guidance process..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                            ></textarea>
                        </div>
                        
                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full py-4 text-xl font-bold text-white bg-indigo-600 rounded-xl shadow-lg hover:bg-indigo-700 transition duration-300 ease-in-out transform hover:scale-[1.01] focus:ring-4 focus:ring-indigo-300"
                        >
                            Send Inquiry
                        </button>
                    </form>
                </div>
            </div>
            <!-- END TWO-COLUMN CONTACT FORM SECTION -->

            <!-- GLOBAL LOCATIONS / ADDRESS SECTION -->
            <section class="mt-16 bg-gray-50 p-8 rounded-2xl shadow-xl">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-10 text-center">Our Global Locations</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- Location Card 1: Headquarters (Asia) -->
                    <div class="bg-white p-8 rounded-xl shadow-lg transition duration-300 hover:shadow-indigo-300/50 border-t-4 border-indigo-400">
                        <h3 class="text-2xl font-bold text-indigo-700 mb-3">Headquarters (Asia)</h3>
                        <p class="text-gray-700">123 Tranquility Lane, Gulshan-2,</p>
                        <p class="text-gray-700">Dhaka, Bangladesh 1212</p>
                        <div class="mt-4 border-t pt-4">
                            <p class="text-sm font-semibold text-gray-900">Email: <span class="text-indigo-500 font-normal">asia@dkhealing.com</span></p>
                            <p class="text-sm font-semibold text-gray-900">Phone: <span class="text-indigo-500 font-normal">+880 1700 123 456</span></p>
                        </div>
                    </div>
                    
                    <!-- Location Card 2: European Office -->
                    <div class="bg-white p-8 rounded-xl shadow-lg transition duration-300 hover:shadow-indigo-300/50 border-t-4 border-indigo-400">
                        <h3 class="text-2xl font-bold text-indigo-700 mb-3">European Office</h3>
                        <p class="text-gray-700">Unit 5, Wellness Center,</p>
                        <p class="text-gray-700">London, SW1A 0AA, UK</p>
                         <div class="mt-4 border-t pt-4">
                            <p class="text-sm font-semibold text-gray-900">Email: <span class="text-indigo-500 font-normal">europe@dkhealing.com</span></p>
                            <p class="text-sm font-semibold text-gray-900">Phone: <span class="text-indigo-500 font-normal">+44 20 7946 0888</span></p>
                        </div>
                    </div>
                    
                    <!-- Location Card 3: North American Hub -->
                    <div class="bg-white p-8 rounded-xl shadow-lg transition duration-300 hover:shadow-indigo-300/50 border-t-4 border-indigo-400">
                        <h3 class="text-2xl font-bold text-indigo-700 mb-3">North American Hub</h3>
                        <p class="text-gray-700">45 Spirituality Rd, Suite 100,</p>
                        <p class="text-gray-700">New York, NY 10001, USA</p>
                         <div class="mt-4 border-t pt-4">
                            <p class="text-sm font-semibold text-gray-900">Email: <span class="text-indigo-500 font-normal">usa@dkhealing.com</span></p>
                            <p class="text-sm font-semibold text-gray-900">Phone: <span class="text-indigo-500 font-normal">+1 212 555 1212</span></p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END GLOBAL LOCATIONS / ADDRESS SECTION -->
        </div>
    </main>

  @endsection 