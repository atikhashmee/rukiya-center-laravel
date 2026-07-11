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
                            <input id="password" name="password" type="password" autocomplete="current-password"  
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold" 
                                placeholder="Password">
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
@endsection