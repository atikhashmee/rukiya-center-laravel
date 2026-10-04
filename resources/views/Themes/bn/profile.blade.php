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

    <!-- Profile Content -->
    <main class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                @include('Themes.layouts.sidebar-customer-profile')

                @php
                    $customer = auth()->guard('customer')->user();
                    $customer_name = $customer->name ?? 'John Doe';
                    $customer_email = $customer->email ?? 'john.doe@example.com';
                    $customer_phone = $customer->phone ?? '+1 555-123-4567';
                    $customer_registered = $customer->created_at->format('Y-m-d') ?? '2023-10-01';
                    $customer_interests = $customer->interests ?? 'Sacred Listening, Personalized Rukiya';
                    $customer_about = $customer->about ?? 'Seeking clarity on career path and spiritual grounding.';

                    $payments_mock = $payments ?? collect([
                        (object)['id' => 1001, 'description' => 'Sacred Listening Session', 'date' => '2023-11-10', 'amount' => 99.00, 'status' => 'paid'],
                        (object)['id' => 1002, 'description' => 'Personalized Rukiya Package', 'date' => '2023-10-25', 'amount' => 249.00, 'status' => 'paid'],
                        (object)['id' => 1003, 'description' => 'Free Consultation (0 Min)', 'date' => '2023-10-01', 'amount' => 0.00, 'status' => 'free'],
                    ]);
                @endphp

                <div class="lg:w-3/4 flex-1">
                    <div class="bg-brand-cream/50 border border-brand-gold/20 p-6 md:p-8 rounded-2xl space-y-6">
                        <h2 class="text-xl font-serif font-bold text-brand-teal border-b border-brand-gold/20 pb-3">
                            Your Profile Information
                        </h2>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-white border border-brand-gold/10 rounded-xl">
                                    <p class="text-xs text-slate-400 font-medium">Name</p>
                                    <p class="font-semibold text-brand-teal text-sm">{{ $customer_name }}</p>
                                </div>
                                <div class="p-4 bg-white border border-brand-gold/10 rounded-xl">
                                    <p class="text-xs text-slate-400 font-medium">Email</p>
                                    <p class="font-semibold text-brand-teal text-sm">{{ $customer_email }}</p>
                                </div>
                                <div class="p-4 bg-white border border-brand-gold/10 rounded-xl">
                                    <p class="text-xs text-slate-400 font-medium">Phone</p>
                                    <p class="font-semibold text-brand-teal text-sm">{{ $customer_phone }}</p>
                                </div>
                                <div class="p-4 bg-white border border-brand-gold/10 rounded-xl">
                                    <p class="text-xs text-slate-400 font-medium">Registered On</p>
                                    <p class="font-semibold text-brand-teal text-sm">{{ $customer_registered }}</p>
                                </div>
                            </div>

                            <div class="p-4 bg-brand-gold/10 border border-brand-gold/20 rounded-xl">
                                <p class="text-xs text-brand-gold font-semibold mb-1">Interests</p>
                                <p class="text-sm text-brand-teal font-medium">{{ implode(', ', $customer->interests) }}</p>
                            </div>

                            <div class="p-4 bg-brand-gold/10 border border-brand-gold/20 rounded-xl">
                                <p class="text-xs text-brand-gold font-semibold mb-1">About Myself</p>
                                <p class="text-sm text-brand-teal font-medium">{{ $customer_about }}</p>
                            </div>

                            <button class="mt-4 px-8 py-3 bg-brand-gold hover:bg-brand-goldDark text-white rounded-xl transition text-sm font-semibold shadow">
                                Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
    </script>
@endpush