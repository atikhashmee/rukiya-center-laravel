<!-- Top Announcement Bar -->
<div class="bg-brand-teal text-brand-cream py-2 px-4 text-center text-xs tracking-wider font-semibold">
    🕌 AUTHENTIC RUQYAH CLINIC • 100% SECURE UK GDPR COMPLIANT • ACCORDING TO QUR'AN & SUNNAH
</div>

<!-- Sticky Header -->
<header class="sticky top-0 z-50 bg-brand-cream/90 backdrop-blur-md border-b border-brand-gold/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <svg class="w-14 h-14" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="46" fill="white" stroke="#c5a880" stroke-width="2"/>
                <circle cx="50" cy="50" r="42" fill="none" stroke="#b31b1b" stroke-width="1.5"/>
                <circle cx="50" cy="50" r="40" fill="none" stroke="#0a3c5c" stroke-width="1.5"/>
                <text x="24" y="58" font-family="'Playfair Display', serif" font-size="28" font-weight="bold" fill="#0a3c5c">D</text>
                <text x="48" y="58" font-family="'Playfair Display', serif" font-size="28" font-weight="bold" fill="#b31b1b">K</text>
            </svg>
            <div>
                <span class="block text-lg font-bold tracking-tight text-brand-teal uppercase">DK Healing Centre</span>
                <span class="block text-[10px] tracking-widest text-brand-gold font-semibold uppercase">Ruqyah & Prophetic Medicine</span>
            </div>
        </a>

        <nav class="hidden md:flex items-center gap-8 font-medium text-sm">
            <a href="{{ route('about') }}" class="hover:text-brand-gold transition">About Us</a>
            <a href="{{ route('services') }}" class="hover:text-brand-gold transition">Services</a>
            <a href="{{ route('shop') }}" class="hover:text-brand-gold transition">Shop</a>
            <a href="{{ route('contact') }}" class="hover:text-brand-gold transition">Contact Us</a>
            <a href="{{ route('free.counselling') }}" class="text-brand-crimson font-bold hover:opacity-80 transition">Free Counseling</a>
        </nav>

        <div class="flex items-center gap-4">
            <!-- Cart -->
            @php $cartCount = collect(session()->get('cart', []))->sum(); @endphp
            <a href="{{ route('cart') }}" class="relative text-brand-teal hover:text-brand-gold transition">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-brand-crimson text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                @endif
            </a>

            @auth("customer")
                <a href="{{ route('customer.profile') }}" class="w-10 h-10 bg-brand-gold rounded-full flex items-center justify-center text-white text-base font-bold ring-2 ring-white hover:ring-brand-gold transition">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </a>
            @else
                <a href="{{ route('customer.login') }}" class="bg-brand-teal hover:bg-brand-navy text-white px-5 py-2.5 rounded-full text-sm font-semibold transition">
                    Login
                </a>
                <a href="{{ route('customer.register') }}" class="bg-brand-gold hover:bg-brand-goldDark text-white px-5 py-2.5 rounded-full text-sm font-semibold transition">
                    Register
                </a>
            @endauth
        </div>
    </div>
</header>