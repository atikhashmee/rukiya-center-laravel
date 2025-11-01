    @extends('themes.layouts.app')

    @section('content')

    <!-- HEADER / NAVIGATION (Consistent) -->
    <header class="relative bg-indigo-900 pt-8 pb-16 overflow-hidden">
        @include('themes.layouts.nav') 
    </header>
    <!-- HEADER END -->

    <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Welcome Back, User Name!</h1>

        <div class="flex flex-col lg:flex-row gap-8">
            @include('themes.layouts.sidebar-customer-profile') 
            <!-- MAIN CONTENT AREA - Slight style refinement -->
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg">
                
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-list-alt text-theme-primary mr-2"></i>
                    Recent Bookings
                </h2>

                {{-- Helper function to render status badges --}}
                @php
                    function get_status_badge($status) {
                        $status = strtolower(str_replace([' ', '_'], ['-','-'], $status));
                        $colors = [
                            'confirmed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                            'new' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                            'in-progress' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                            'completed' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800'],
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
                    {{-- Empty State Content --}}
                    <div class="text-center p-10 border border-gray-200 rounded-lg bg-gray-50">
                        <i class="far fa-calendar-times text-6xl text-gray-400 mb-4"></i>
                        <p class="text-xl font-semibold text-gray-700">No Bookings Found</p>
                        <p class="text-gray-500 mt-2">It looks like you haven't booked any services yet. Start your healing journey now!</p>
                        <a href="{{ route('booking.page') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-theme-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-theme-primary">
                            Book a Service
                        </a>
                    </div>
                @else
                    {{-- Desktop View: Table Structure Rendered ONCE --}}
                    <div class="hidden lg:block overflow-x-auto mb-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-indigo-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tl-lg">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tr-lg">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($bookings as $booking)
                                    @php
                                        // Assuming the Booking model has a relationship to Service model called 'service'
                                        $serviceName = $booking->service->title ?? 'Service Unavailable';
                                        $formattedAmount = $booking->service_price > 0 ? '£' . number_format($booking->service_price, 2) : 'N/A';
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $serviceName }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->created_at->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $formattedAmount }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{!! get_status_badge($booking->payment_status) !!}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{!! get_status_badge($booking->booking_status) !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile View: Cards Rendered In a Single Loop --}}
                    <div class="lg:hidden space-y-4 mb-8">
                        @foreach ($bookings as $booking)
                            @php
                                $serviceName = $booking->service->name ?? 'Service Unavailable';
                                $formattedAmount = $booking->amount > 0 ? '£' . number_format($booking->amount, 2) : 'N/A';
                            @endphp
                            <div class="bg-white p-4 rounded-xl shadow border border-gray-200">
                                <p class="text-sm font-semibold text-gray-500 mb-1">Booking ID: <span class="text-gray-800 font-bold">#{{ $booking->id }}</span></p>
                                <h3 class="text-lg font-extrabold text-theme-primary mb-3">{{ $serviceName }}</h3>
                                <div class="space-y-2">
                                    <p class="flex justify-between items-center text-sm"><span class="font-medium text-gray-600">Date:</span> <span class="font-semibold">{{ $booking->created_at->format('Y-m-d') }}</span></p>
                                    <p class="flex justify-between items-center text-sm"><span class="font-medium text-gray-600">Amount:</span> <span class="font-bold text-gray-900">{{ $formattedAmount }}</span></p>
                                    <p class="flex justify-between items-center text-sm"><span class="font-medium text-gray-600">Payment:</span> {!! get_status_badge($booking->payment_status) !!}</p>
                                    <p class="flex justify-between items-center text-sm"><span class="font-medium text-gray-600">Status:</span> {!! get_status_badge($booking->booking_status) !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center">
                        <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-theme-primary hover:bg-indigo-700 transition duration-150">
                            Load More Bookings
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>

    @endsection

@push('scripts')
     <script>
       
    </script>
@endpush