    @extends('themes.layouts.app')

    @section('content')

    <!-- HEADER / NAVIGATION (Consistent) -->
    <header class="relative bg-indigo-900 pt-32 pb-20 overflow-hidden">
        
         @include('themes.layouts.nav')
        
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('https://picsum.photos/1600/900?random=16');"></div>
        <div class="absolute inset-0 bg-indigo-900 opacity-70"></div>

        <div class="relative z-10 text-center max-w-4xl mx-auto p-6 md:p-8">
            <p class="text-xl md:text-2xl text-yellow-300 font-extrabold tracking-widest uppercase">Special Offer</p>
            <h1 class="text-5xl md:text-6xl font-extrabold text-white leading-tight tracking-tight mt-2">
                Your First Step to Inner Harmony: FREE 30-Minute Consultation
            </h1>
        </div>
    </header>
    <!-- HEADER END -->

    <main class="flex-grow py-20 px-4 sm:px-8">
        <div class="max-w-4xl mx-auto text-center bg-white p-10 rounded-3xl shadow-2xl">
            <div class="mb-12">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-6">How Our Free Counseling Works</h2>
                <p class="text-xl text-gray-600 leading-relaxed">
                    We understand that committing to a healing journey is a big decision. That's why we offer a completely **free, no-obligation 30-minute counseling session** to experience our compassionate approach firsthand.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left mb-12">
                
                <!-- Benefit 1 -->
                <div class="p-6 bg-indigo-50 rounded-xl shadow-md border-l-4 border-indigo-600">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Identify Your Needs</h3>
                    <p class="text-gray-600">
                        Use this time to discuss your current challenges, spiritual questions, or feelings of imbalance. We'll help you articulate your healing goals.
                    </p>
                </div>
                
                <!-- Benefit 2 -->
                <div class="p-6 bg-indigo-50 rounded-xl shadow-md border-l-4 border-indigo-600">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Experience Our Method</h3>
                    <p class="text-gray-600">
                        Get a taste of our Sacred Listening technique. This session is designed to give you initial clarity and peace within a brief period.
                    </p>
                </div>
                
                <!-- Benefit 3 -->
                <div class="p-6 bg-indigo-50 rounded-xl shadow-md border-l-4 border-indigo-600">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Zero Pressure</h3>
                    <p class="text-gray-600">
                        There's no expectation of booking further services. This is purely to build trust and show you how we can help.
                    </p>
                </div>
            </div>

            <div class="mt-10 p-8 bg-indigo-600 rounded-xl shadow-xl">
                <p class="text-2xl font-semibold text-white mb-6">
                    Ready to take the first step towards clarity?
                </p>
                <a href="contact-us.html" class="inline-block px-12 py-5 text-xl font-bold text-indigo-900 bg-yellow-300 rounded-full shadow-lg hover:bg-yellow-400 transition duration-300 transform hover:scale-105">
                    Book Your FREE 30-Minute Session
                </a>
            </div>
        </div>
    </main>

  @endsection
