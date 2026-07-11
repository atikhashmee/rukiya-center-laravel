@extends('Themes.layouts.app')

@section('content')
    @include('Themes.layouts.nav')

    <!-- Profile Header -->
    <section class="relative py-20 bg-brand-teal">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-white leading-tight">
                Welcome Back, <span class="italic text-brand-gold">{{ auth()->user()->name }}</span>!
            </h1>
        </div>
    </section>

    <!-- Transactions Content -->
    <main class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                @include('Themes.layouts.sidebar-customer-profile')

                <!-- Transaction List/Table Container -->
                <div class="lg:w-3/4 bg-brand-cream/50 border border-brand-gold/20 rounded-2xl overflow-hidden">
                    <!-- Table Header (Desktop) -->
                    <div class="hidden md:grid grid-cols-6 lg:grid-cols-7 gap-4 p-4 text-xs font-bold text-brand-teal border-b border-brand-gold/20 bg-white">
                        <div class="col-span-2">Service / Item</div>
                        <div class="col-span-1 text-center">Amount</div>
                        <div class="col-span-1 text-center">Date</div>
                        <div class="col-span-1 text-center">Status</div>
                        <div class="col-span-1 lg:col-span-2 text-right">Transaction ID</div>
                    </div>

                    <!-- Transaction List Body -->
                    <div>
                        @foreach ($transactions as $transaction)
                            <div class="transaction-row grid grid-cols-6 lg:grid-cols-7 gap-4 p-4 border-b border-brand-gold/10 hover:bg-white transition">
                                <!-- Service / Item -->
                                <div class="col-span-4 md:col-span-2 flex flex-col justify-center">
                                    <span class="font-semibold text-brand-teal text-sm">{{ $transaction?->booking?->service?->title ?? "N/A" }}</span>
                                    <span class="md:hidden text-xs text-slate-400 mt-0.5">{{ $transaction->payment_intent_id }}</span>
                                </div>

                                <!-- Amount -->
                                <div class="col-span-2 md:col-span-1 flex flex-col justify-center text-right md:text-center">
                                    <span class="font-bold text-sm text-brand-teal">+£{{ $transaction->formatted_amount }}</span>
                                    <span class="md:hidden text-xs text-slate-400">{{ $transaction->created_at->format("d M Y") }}</span>
                                </div>

                                <!-- Date (Desktop Only) -->
                                <div class="hidden md:col-span-1 md:flex flex-col justify-center text-center text-sm text-slate-500">
                                    {{ $transaction->created_at->format("d M Y") }}
                                </div>

                                <!-- Status (Desktop Only) -->
                                <div class="hidden md:col-span-1 md:flex flex-col justify-center text-center">
                                    <span class="text-xs font-bold py-1 px-3 rounded-full bg-green-100 text-green-700">
                                        {{ $transaction->status }}
                                    </span>
                                </div>

                                <!-- Transaction ID (Desktop Only) -->
                                <div class="hidden md:col-span-2 lg:flex flex-col justify-center text-right text-xs text-slate-400 font-mono">
                                    {{ $transaction->payment_intent_id }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Empty State -->
                    @if ($transactions->isEmpty())
                        <div class="text-center p-12 text-slate-400">
                            <p class="text-lg font-semibold text-slate-500">No Transactions Found</p>
                            <p class="text-sm text-slate-400">It looks like you haven't made any payments yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
    </script>
@endpush