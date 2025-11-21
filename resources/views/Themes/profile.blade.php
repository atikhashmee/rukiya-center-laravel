    @extends('Themes.layouts.app')

    @section('content')

    <!-- HEADER / NAVIGATION (Consistent) -->
    <header class="relative bg-indigo-900 pt-8 pb-16 overflow-hidden">
        @include('Themes.layouts.nav') 
    </header>
    <!-- HEADER END -->

    <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Welcome Back, <span style="text-transform: capitalize;">{{ auth()->user()->name}}</span>!</h1>

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

                // --- MOCK PAYMENTS (Replace with your actual $payments collection loop) ---
                // This is a simple mock collection to ensure the payments section renders dynamically.
                $payments_mock = $payments ?? collect([
                    (object)['id' => 1001, 'description' => 'Sacred Listening Session', 'date' => '2023-11-10', 'amount' => 99.00, 'status' => 'paid'],
                    (object)['id' => 1002, 'description' => 'Personalized Rukiya Package', 'date' => '2023-10-25', 'amount' => 249.00, 'status' => 'paid'],
                    (object)['id' => 1003, 'description' => 'Free Consultation (0 Min)', 'date' => '2023-10-01', 'amount' => 0.00, 'status' => 'free'],
                ]);
            @endphp

            <div class="lg:w-3/4 flex-1">
                
                <div id="profile" class="content-section bg-white p-6 md:p-8 rounded-xl shadow-lg border-t-4 border-theme-primary">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-3 flex items-center">
                        <i class="fas fa-user-circle text-theme-primary mr-3"></i> Your Profile Information
                    </h2>
                    
                    <div class="space-y-6 text-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 font-medium">Name</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $customer_name }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 font-medium">Email</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $customer_email }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 font-medium">Phone</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $customer_phone }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                <p class="text-xs text-gray-500 font-medium">Registered On</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $customer_registered }}</p>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                            <p class="text-sm text-indigo-700 font-medium mb-1 flex items-center">
                                <i class="fas fa-heart text-indigo-500 mr-2"></i> Interests
                            </p>
                            <p class="text-base text-gray-800 font-medium">{{ implode(', ', $customer->interests) }}</p>
                        </div>
                        
                        <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                            <p class="text-sm text-indigo-700 font-medium mb-1 flex items-center">
                                <i class="fas fa-book-open text-indigo-500 mr-2"></i> About Myself
                            </p>
                            <p class="text-base text-gray-800 font-medium">{{ $customer_about }}</p>
                        </div>
                        
                        <button class="mt-6 px-8 py-3 bg-theme-primary text-white rounded-xl hover:bg-indigo-700 transition duration-150 font-semibold shadow-lg shadow-indigo-200/50">
                            <i class="fas fa-edit mr-2"></i> Edit Profile
                        </button>
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
