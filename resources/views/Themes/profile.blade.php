    @extends('themes.layouts.app')

    @section('content')

    <!-- HEADER / NAVIGATION (Consistent) -->
    <header class="relative bg-indigo-900 pt-8 pb-8 overflow-hidden">
        <nav class="p-4 md:p-6 bg-transparent">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <a href="{{ route("home") }}" class="flex flex-col items-start transition duration-300 hover:text-indigo-300 text-white">
                    <svg class="h-8 w-auto" viewBox="0 0 100 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <text x="0" y="28" font-family="Inter, sans-serif" font-size="35" font-weight="800" fill="currentColor">DK</text>
                        <text x="0" y="38" font-family="Inter, sans-serif" font-size="10" font-weight="400" fill="#C7D2FE">healing center</text>
                    </svg>
                </a>
                <div class="hidden md:flex space-x-6 lg:space-x-10 items-center text-lg">
                    <a href="{{ route("home") }}" class="text-white hover:text-indigo-300 transition duration-300">Home</a>
                    <a href="{{ route("services") }}" class="text-white hover:text-indigo-300 transition duration-300">Services</a>
                    <a href="{{ route("about") }}" class="text-white hover:text-indigo-300 transition duration-300">About Us</a>
                    <a href="{{ route("contact") }}" class="text-white hover:text-indigo-300 transition duration-300">Contact Us</a>
                    <a href="{{ route("free.counselling") }}" class="text-indigo-300 font-semibold hover:text-white transition duration-300">Free Counseling</a>
                </div>
                <!-- Login/Logout button area - Now just shows "Login" link for consistency if not logged in, or is empty if handled by sidebar -->
                <div class="flex space-x-3 items-center">
                    <a href="login.html" class="px-3 py-2 md:px-4 md:py-2 text-white border border-white rounded-lg text-sm md:text-base hover:bg-white hover:text-indigo-900 transition duration-300">
                        Logout
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <!-- HEADER END -->

    <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Welcome Back, User Name!</h1>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- LEFT NAVIGATION BAR (Sidebar) - Made more professional and separated logout -->
            <nav class="lg:w-1/4 bg-white p-6 rounded-xl shadow-2xl h-fit sticky top-10 flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 mb-4 pb-2 border-b border-gray-200">User Dashboard</h2>
                    <ul class="space-y-2">
                        <li>
                            <button onclick="showSection('profile')" class="w-full text-left py-3 px-4 rounded-lg text-sm transition duration-150 font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active-nav">
                                Profile Information
                            </button>
                        </li>
                        <li>
                            <button onclick="showSection('services')" class="w-full text-left py-3 px-4 rounded-lg text-sm transition duration-150 font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                Services Booked
                            </button>
                        </li>
                        <li>
                            <button onclick="showSection('payments')" class="w-full text-left py-3 px-4 rounded-lg text-sm transition duration-150 font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                Payments Made
                            </button>
                        </li>
                        <li>
                            <button onclick="showSection('settings')" class="w-full text-left py-3 px-4 rounded-lg text-sm transition duration-150 font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                Account Settings
                            </button>
                        </li>
                    </ul>
                </div>
                
                <!-- LOGOUT BUTTON - Separated at the bottom of the dashboard menu -->
                <div class="mt-8 pt-4 border-t border-gray-200">
                    <button onclick="window.location.href='login.html';" class="w-full text-center py-3 px-4 rounded-lg text-sm font-bold transition duration-150 text-red-700 bg-red-50 hover:bg-red-100 border border-red-200">
                        Logout
                    </button>
                </div>
            </nav>

            <!-- MAIN CONTENT AREA - Slight style refinement -->
            <div class="lg:w-3/4 bg-white p-8 rounded-xl shadow-2xl border-t-4 border-indigo-500">
                
                <!-- Profile Information Section (Default View) -->
                <div id="profile" class="content-section">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Your Profile Information</h2>
                    <div class="space-y-4 text-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Name</p>
                                <p class="font-semibold text-gray-900">John Doe</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="font-semibold text-gray-900">john.doe@example.com</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Phone</p>
                                <p class="font-semibold text-gray-900">+1 555-123-4567</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Registered On</p>
                                <p class="font-semibold text-gray-900">2023-10-01</p>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-indigo-50 rounded-lg">
                            <p class="text-xs text-indigo-700 font-medium mb-1">Interests</p>
                            <p class="text-sm text-gray-800">Sacred Listening, Personalized Rukiya</p>
                        </div>
                        
                        <div class="p-4 bg-indigo-50 rounded-lg">
                            <p class="text-xs text-indigo-700 font-medium mb-1">About Myself</p>
                            <p class="text-sm text-gray-800">Seeking clarity on career path and spiritual grounding.</p>
                        </div>
                        
                        <button class="mt-6 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 font-semibold shadow-md">Update Profile</button>
                    </div>
                </div>

                <!-- Services Booked Section -->
                <div id="services" class="content-section hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Your Booked Services</h2>
                    <div class="space-y-6">
                        <div class="p-5 border border-gray-200 rounded-xl shadow-md bg-indigo-50 flex justify-between items-center">
                            <div>
                                <p class="font-extrabold text-lg text-indigo-800">Sacred Listening Session</p>
                                <p class="text-sm text-gray-600">2023-11-15 | 10:00 AM (EST)</p>
                            </div>
                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 font-bold">Confirmed</span>
                        </div>
                        <div class="p-5 border border-gray-200 rounded-xl shadow-md bg-white flex justify-between items-center">
                            <div>
                                <p class="font-extrabold text-lg text-gray-800">Istikhara Guidance (Major Decision)</p>
                                <p class="text-sm text-gray-600">2023-11-20 | 3:00 PM (EST)</p>
                            </div>
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700 font-bold">Pending</span>
                        </div>
                        <p class="mt-4 text-gray-500 text-sm">Need to book a new service? <a href="services.html" class="text-indigo-600 hover:underline font-semibold">View all services.</a></p>
                    </div>
                </div>

                <!-- Payments Made Section -->
                <div id="payments" class="content-section hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Payment History</h2>
                    <ul class="divide-y divide-gray-200">
                        <li class="py-4 flex justify-between items-center">
                            <div>
                                <p class="text-gray-900 font-medium">Invoice #1001: Sacred Listening</p>
                                <p class="text-sm text-gray-500">Paid on 2023-11-10</p>
                            </div>
                            <span class="text-xl font-bold text-green-700">$99.00</span>
                        </li>
                        <li class="py-4 flex justify-between items-center">
                            <div>
                                <p class="text-gray-900 font-medium">Invoice #1002: Personalized Rukiya Package</p>
                                <p class="text-sm text-gray-500">Paid on 2023-10-25</p>
                            </div>
                            <span class="text-xl font-bold text-green-700">$249.00</span>
                        </li>
                        <li class="py-4 flex justify-between items-center">
                            <div>
                                <p class="text-gray-900 font-medium">Invoice #1003: Free Consultation (0 Min)</p>
                                <p class="text-sm text-gray-500">Booked on 2023-10-01</p>
                            </div>
                            <span class="text-xl font-bold text-indigo-700">FREE</span>
                        </li>
                    </ul>
                </div>

                <!-- Account Settings Section -->
                <div id="settings" class="content-section hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Account Settings</h2>
                    <p class="text-gray-700">Manage your account preferences, change your password, and update communication settings here.</p>
                    <!-- Simulated settings form elements -->
                    <div class="mt-6 space-y-4">
                        <button class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-150">Change Password</button>
                        <button class="px-5 py-2 border border-red-300 rounded-lg text-red-600 hover:bg-red-50 transition duration-150">Deactivate Account</button>
                    </div>
                </div>

            </div>
        </div>
    </main>


    @endsection


@push('scripts')
     <script>
        function showSection(sectionId) {
            // Hide all content sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('hidden');
            });

            // Remove active class from all buttons
            document.querySelectorAll('nav ul button').forEach(button => {
                button.classList.remove('bg-indigo-600', 'text-white');
                button.classList.add('text-gray-700', 'hover:bg-indigo-50', 'hover:text-indigo-600');
            });

            // Show the selected section
            document.getElementById(sectionId).classList.remove('hidden');

            // Add active class to the clicked button
            const button = document.querySelector(`button[onclick="showSection('${sectionId}')"]`);
            if (button) {
                button.classList.add('bg-indigo-600', 'text-white');
                button.classList.remove('text-gray-700', 'hover:bg-indigo-50', 'hover:text-indigo-600');
            }
        }

        // Set default active button/section on load
        window.onload = function() {
            showSection('profile');
        }
    </script>
@endpush
