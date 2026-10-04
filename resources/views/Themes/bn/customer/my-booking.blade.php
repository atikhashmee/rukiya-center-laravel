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

    <!-- Bookings Content -->
    <main class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                @include('Themes.layouts.sidebar-customer-profile')

                <!-- MAIN CONTENT AREA -->
                <div class="lg:w-3/4 bg-brand-cream/50 border border-brand-gold/20 p-6 md:p-8 rounded-2xl">
                    <h2 class="text-xl font-serif font-bold text-brand-teal mb-6 border-b border-brand-gold/20 pb-3">
                        Recent Bookings
                    </h2>

                    @php
                        function get_status_badge($status) {
                            $status = strtolower(str_replace([' ', '_'], ['-','-'], $status));
                            $colors = [
                                'confirmed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                'new' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                                'in-progress' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                'completed' => ['bg' => 'bg-brand-gold/20', 'text' => 'text-brand-goldDark'],
                                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                'pending' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                'assessment-required' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
                                'refunded' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                                'donation' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                            ];
                            $color = $colors[$status] ?? ['bg' => 'bg-gray-200', 'text' => 'text-gray-800'];
                            return '<span class="status-badge px-3 py-1 rounded-full text-xs font-semibold uppercase ' . $color['bg'] . ' ' . $color['text'] . '">' . str_replace('-', ' ', $status) . '</span>';
                        }
                    @endphp

                    @if ($bookings->isEmpty())
                        <div class="text-center p-10 border border-brand-gold/20 rounded-2xl bg-white">
                            <p class="text-lg font-semibold text-slate-600">No Bookings Found</p>
                            <p class="text-sm text-slate-400 mt-2">It looks like you haven't booked any services yet. Start your healing journey now!</p>
                            <a href="{{ route('wizard.index') }}" class="mt-4 inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-6 py-2.5 rounded-full text-sm font-semibold transition shadow">
                                Book a Service
                            </a>
                        </div>
                    @else
                        <!-- Desktop View: Table Structure -->
                        <div class="hidden lg:block overflow-x-auto mb-8">
                            <table class="min-w-full divide-y divide-brand-gold/20">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-brand-teal uppercase tracking-wider">Service</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-brand-teal uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-brand-teal uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-brand-teal uppercase tracking-wider">Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-brand-teal uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-brand-gold/10">
                                    @foreach ($bookings as $booking)
                                        @php
                                            $serviceName = $booking->service->title ?? 'Service Unavailable';
                                            $formattedAmount = $booking->service_price > 0 ? '£' . number_format($booking->service_price, 2) : 'N/A';
                                        @endphp
                                        <tr class="hover:bg-brand-cream/50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-brand-teal">{{ $serviceName }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $booking->created_at->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-brand-teal">{{ $formattedAmount }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{!! get_status_badge($booking->payment_status) !!}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{!! get_status_badge($booking->booking_status) !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile View: Cards -->
                        <div class="lg:hidden space-y-4 mb-8">
                            @foreach ($bookings as $booking)
                                @php
                                    $serviceName = $booking->service->name ?? 'Service Unavailable';
                                    $formattedAmount = $booking->amount > 0 ? '£' . number_format($booking->amount, 2) : 'N/A';
                                @endphp
                                <div class="bg-white p-4 rounded-2xl border border-brand-gold/20">
                                    <p class="text-xs text-slate-400 mb-1">Booking ID: <span class="text-brand-teal font-bold">#{{ $booking->id }}</span></p>
                                    <h3 class="text-base font-serif font-bold text-brand-teal mb-3">{{ $serviceName }}</h3>
                                    <div class="space-y-2">
                                        <p class="flex justify-between items-center text-sm"><span class="font-medium text-slate-500">Date:</span> <span class="font-semibold text-brand-teal">{{ $booking->created_at->format('Y-m-d') }}</span></p>
                                        <p class="flex justify-between items-center text-sm"><span class="font-medium text-slate-500">Amount:</span> <span class="font-bold text-brand-teal">{{ $formattedAmount }}</span></p>
                                        <p class="flex justify-between items-center text-sm"><span class="font-medium text-slate-500">Payment:</span> {!! get_status_badge($booking->payment_status) !!}</p>
                                        <p class="flex justify-between items-center text-sm"><span class="font-medium text-slate-500">Status:</span> {!! get_status_badge($booking->booking_status) !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center">
                            <a href="#" class="inline-block bg-brand-gold hover:bg-brand-goldDark text-white px-6 py-2.5 rounded-full text-sm font-semibold transition shadow">
                                Load More Bookings
                            </a>
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