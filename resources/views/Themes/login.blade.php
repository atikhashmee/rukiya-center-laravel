@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <main class="py-20 bg-white">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-10 rounded-2xl space-y-6">
                <div class="text-center space-y-2">
                    <h2 class="text-2xl font-serif font-bold text-brand-teal">Sign in to your account</h2>
                    <p class="text-xs text-slate-500">
                        Or <a href="{{ route('customer.register') }}" class="font-semibold text-brand-gold hover:text-brand-goldDark transition">
                            register for a new account
                        </a>
                    </p>
                </div>

                <form class="space-y-5" action="{{ route('customer.login.auth') }}" method="POST">
                    @csrf
                    <input type="hidden" name="remember" value="true">

                    <div class="space-y-4">
                        <div>
                            <label for="email-address" class="block text-xs font-bold text-brand-teal mb-1">Email Address</label>
                            <input id="email-address" name="email" type="email" autocomplete="email"  
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold" 
                                placeholder="Email address">
                        </div>
                        <div>
                            <label for="password" class="block text-xs font-bold text-brand-teal mb-1">Password</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                    class="w-full px-4 py-3 pr-11 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold"
                                    placeholder="Password">
                                <button type="button" onclick="togglePasswordVisibility()"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-teal transition">
                                    <svg id="eye-icon" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg id="eye-off-icon" class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-brand-gold hover:text-brand-goldDark transition">
                                Forgot your password?
                            </a>
                        </div>
                    </div>

                    <div>
                        @if ($errors->any())
                            <div class="mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-brand-crimson text-xs">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button type="submit" class="w-full bg-brand-teal hover:bg-brand-navy text-white font-bold py-3 rounded-xl transition shadow text-sm">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            const showing = input.type === 'text';

            input.type = showing ? 'password' : 'text';
            eyeIcon.classList.toggle('hidden', !showing);
            eyeOffIcon.classList.toggle('hidden', showing);
        }
    </script>
    @endpush
@endsection