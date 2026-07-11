<!-- LEFT NAVIGATION BAR (Sidebar) -->
<nav class="lg:w-1/4 bg-brand-cream/50 border border-brand-gold/20 p-6 rounded-2xl h-fit sticky top-28 flex flex-col justify-between">
    <div>
        <h2 class="text-lg font-serif font-bold text-brand-teal mb-4 pb-2 border-b border-brand-gold/20">User Dashboard</h2>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('customer.profile') }}" class="w-full text-left py-3 px-4 rounded-xl text-sm font-semibold text-white bg-brand-teal hover:bg-brand-navy transition">
                    Profile Information
                </a>
            </li>
            <li>
                <a href="{{ route('customer.mybooking') }}" class="w-full text-left py-3 px-4 rounded-xl text-sm font-semibold text-slate-600 hover:bg-brand-gold/10 hover:text-brand-teal transition">
                    Services Booked
                </a>
            </li>
            <li>
                <a href="{{ route('customer.mytransactions') }}" class="w-full text-left py-3 px-4 rounded-xl text-sm font-semibold text-slate-600 hover:bg-brand-gold/10 hover:text-brand-teal transition">
                    Payments History
                </a>
            </li>
            <li>
                <a href="#" class="w-full text-left py-3 px-4 rounded-xl text-sm font-semibold text-slate-600 hover:bg-brand-gold/10 hover:text-brand-teal transition">
                    Account Settings
                </a>
            </li>
        </ul>
    </div>

    <!-- LOGOUT BUTTON -->
    <div class="mt-8 pt-4 border-t border-brand-gold/20">
        <form action="{{ route('customer.logout') }}" method="POST" id="logout-form">
            @csrf
        </form>
        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-full text-center py-3 px-4 rounded-xl text-sm font-bold text-brand-crimson bg-brand-crimson/10 hover:bg-brand-crimson/20 border border-brand-crimson/20 transition">
            Logout
        </button>
    </div>
</nav>