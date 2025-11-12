    @extends('Themes.layouts.app')

    @section('content')

    <!-- HEADER / NAVIGATION (Consistent) -->
    <header class="relative bg-indigo-900 pt-8 pb-16 overflow-hidden">
        @include('Themes.layouts.nav') 
    </header>
    <!-- HEADER END -->

    <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Welcome Back, <span style="text-transform: capitalize;">{{ auth()->user()->name}}</span> !</h1>

        <div class="flex flex-col lg:flex-row gap-8">
            @include('themes.layouts.sidebar-customer-profile') 
            <!-- MAIN CONTENT AREA - Slight style refinement -->
           <!-- Transaction List/Table Container -->
            <div id="transactions-container" class="bg-white shadow-xl rounded-xl overflow-hidden">
                
                <!-- Table Header (Desktop) -->
                <div class="hidden md:grid grid-cols-6 lg:grid-cols-7 gap-4 p-4 text-sm font-semibold text-gray-600 border-b border-gray-100 bg-gray-50">
                    <div class="col-span-2">Service / Item</div>
                    <div class="col-span-1 text-center">Amount</div>
                    <div class="col-span-1 text-center">Date</div>
                    <div class="col-span-1 text-center">Status</div>
                    <div class="col-span-1 lg:col-span-2 text-right">Transaction ID</div>
                </div>
                
                <!-- Transaction List Body (Mock Data) -->
                <div id="transaction-list">

                    <!-- MOCK TRANSACTION ROW 1: Payment Completed -->
                    @foreach ($transactions  as $transaction)
                       <div class="transaction-row grid grid-cols-6 lg:grid-cols-7 gap-4 p-4 border-b border-gray-100 transition-colors hover:bg-gray-50">
                        
                        <!-- Service / Item (Mobile & Desktop) -->
                        <div class="col-span-4 md:col-span-2 flex flex-col justify-center">
                            <span class="font-semibold text-gray-900"> {{ $transaction?->booking?->service?->title ?? "N/A"}} </span>
                            <span class="md:hidden text-xs text-gray-500 mt-0.5">  {{ $transaction->payment_intent_id }}</span>
                        </div>

                        <!-- Amount (Mobile & Desktop) -->
                        <div class="col-span-2 md:col-span-1 flex flex-col justify-center text-right md:text-center">
                            <span class="font-extrabold text-lg text-theme-primary">+£{{ $transaction->formatted_amount }}</span>
                            <span class="md:hidden text-xs text-gray-500"> {{ $transaction->created_at->format("d M Y") }}</span>
                        </div>

                        <!-- Date (Desktop Only) -->
                        <div class="hidden md:col-span-1 md:flex flex-col justify-center text-center text-sm text-gray-600">
                            {{ $transaction->created_at->format("d M Y") }}
                        </div>

                        <!-- Status (Desktop Only) -->
                        <div class="hidden md:col-span-1 md:flex flex-col justify-center text-center">
                            <span class="text-xs font-bold py-1 px-4 rounded-full border text-theme-success bg-theme-success/10 border-theme-success">
                                {{ $transaction->status }}
                            </span>
                        </div>
                        
                        <!-- Transaction ID (Desktop Only) -->
                        <div class="hidden md:col-span-2 lg:flex flex-col justify-center text-right text-sm text-gray-500 font-mono">
                            {{ $transaction->payment_intent_id }}
                        </div>
                    </div> 
                    @endforeach
                </div>
                
                <!-- Empty State (Hidden, for Laravel logic to display when data is empty) -->
                <div id="empty-state" class="hidden text-center p-12 text-gray-500">
                    <i class="fas fa-wallet text-6xl mb-4"></i>
                    <h2 class="text-xl font-semibold">No Transactions Found</h2>
                    <p>It looks like you haven't made any payments yet.</p>
                </div>
            </div>
        </div>
    </main>

    @endsection

@push('scripts')
     <script>
       
    </script>
@endpush