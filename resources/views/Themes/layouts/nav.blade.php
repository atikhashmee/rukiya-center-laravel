<!-- Navigation Bar -->
<nav class="absolute top-0 left-0 right-0 z-20 p-4 md:p-6 bg-transparent">
    <div class="flex items-center justify-between max-w-7xl mx-auto">
        
        <!-- Logo/Home Link (UPDATED: DK Healing Center SVG) -->
        <a href="{{ route("home") }}" class="flex flex-col items-start transition duration-300 hover:text-indigo-300 text-white">
            <svg class="h-8 w-auto" viewBox="0 0 100 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- DK in large, bold font (using currentColor for main white color) -->
                <text x="0" y="28" font-family="Inter, sans-serif" font-size="35" font-weight="800" fill="currentColor">DK</text>
                <!-- healing center in small, regular font, light indigo color -->
                <text x="0" y="38" font-family="Inter, sans-serif" font-size="10" font-weight="400" fill="#C7D2FE">healing center</text>
            </svg>
        </a>
        
        <!-- Main Navigation Links (Hidden on small, visible on medium+) -->
        <div class="hidden md:flex space-x-6 lg:space-x-10 items-center text-lg">
            <a href="{{ route("home") }}" class="text-white hover:text-indigo-300 transition duration-300">Home</a>
            <a href="{{ route("services") }}" class="text-white hover:text-indigo-300 transition duration-300">Services</a>
            <a href="{{ route("about") }}" class="text-white hover:text-indigo-300 transition duration-300">About Us</a>
            <a href="{{ route("contact") }}" class="text-white hover:text-indigo-300 transition duration-300">Contact Us</a>
            <a href="{{ route("free.counselling") }}" class="text-indigo-300 font-semibold hover:text-white transition duration-300">Free Counseling</a>
        </div>
        
        <!-- Auth Buttons (Always visible, simplified for mobile) -->
        <div class="flex space-x-3 items-center">
            @auth("customer")
           
                <a href="{{ route("customer.profile") }}" class="w-10 h-10 bg-theme-gold rounded-full flex items-center justify-center text-indigo-900 text-base font-bold ring-2 ring-white hover:ring-indigo-300 transition duration-300">
                        JD
                    </a>
            @else
            <a href="{{ route("customer.login") }}" class="px-3 py-2 md:px-4 md:py-2 text-white border border-white rounded-lg text-sm md:text-base hover:bg-white hover:text-indigo-900 transition duration-300">
                Login
            </a>
            <a href="{{ route("customer.register") }}" class="px-3 py-2 md:px-4 md:py-2 text-indigo-900 bg-indigo-300 rounded-lg text-sm md:text-base font-semibold hover:bg-indigo-400 transition duration-300">
                Register
            </a>
        @endauth
            
        </div>
    </div>
</nav>