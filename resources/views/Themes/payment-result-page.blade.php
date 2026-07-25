@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <main class="py-20 bg-white">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Content -->
            <div id="success-view" class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-10 rounded-2xl text-center space-y-6 hidden">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto text-3xl font-bold">
                    ✓
                </div>

                <h1 class="text-2xl font-serif font-bold text-brand-teal">Payment Successful!</h1>
                <p class="text-sm text-slate-600">Your booking is Confirmed.</p>

                <div class="bg-white border border-brand-gold/20 p-4 rounded-2xl space-y-3 text-left">
                    <div class="flex justify-between font-medium text-sm text-slate-600">
                        <span>Service Paid:</span>
                        <span class="text-brand-teal">Personalized Rukiya Package</span>
                    </div>
                    <div class="flex justify-between font-medium text-sm text-slate-600">
                        <span>Total Amount:</span>
                        <span class="text-brand-teal font-bold">£150.00</span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-400 border-t border-brand-gold/20 pt-2">
                        <span>Transaction ID:</span>
                        <span>TXN-5901-ABCDE12345</span>
                    </div>
                </div>

                <div class="flex flex-col space-y-3">
                    <a href="{{ route('home') }}" class="w-full bg-brand-teal hover:bg-brand-navy text-white px-6 py-3 rounded-xl font-bold text-sm transition shadow">
                        Back to Home
                    </a>
                </div>
            </div>

            <!-- Failure Content -->
            <div id="fail-view" class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-10 rounded-2xl text-center space-y-6 hidden">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-3xl">
                    ✗
                </div>

                <h1 class="text-2xl font-serif font-bold text-brand-teal">Payment Failed</h1>
                <p class="text-sm text-brand-crimson font-semibold">We couldn't process your transaction.</p>

                <div class="bg-white border border-brand-gold/20 p-4 rounded-2xl text-left space-y-2">
                    <p class="font-bold text-sm text-brand-teal">Reason:</p>
                    <ul class="text-xs text-slate-600 space-y-1">
                        <li class="flex items-center gap-2"><span class="text-brand-crimson">✗</span> Card declined by the bank.</li>
                        <li class="flex items-center gap-2"><span class="text-brand-crimson">✗</span> Incorrect CVC or expiry date.</li>
                        <li class="flex items-center gap-2"><span class="text-brand-crimson">✗</span> Insufficient funds.</li>
                    </ul>
                </div>

                <div class="flex flex-col space-y-3">
                    <a href="{{ route('services') }}" class="w-full bg-brand-gold hover:bg-brand-goldDark text-white px-6 py-3 rounded-xl font-bold text-sm transition shadow">
                        Try Payment Again
                    </a>
                    <a href="{{ route('contact') }}" class="w-full border border-slate-200 text-slate-600 px-6 py-3 rounded-xl font-medium text-sm hover:bg-slate-50 transition">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const successView = document.getElementById('success-view');
            const failView = document.getElementById('fail-view');
            if (status === 'success') {
                successView.classList.remove('hidden');
                document.title = "Payment Confirmed";
            } else {
                failView.classList.remove('hidden');
                document.title = "Payment Failed";
            }
        });
    </script>
@endpush