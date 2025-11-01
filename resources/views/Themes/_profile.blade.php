@extends('themes.layouts.layout')
@section('content')
    <main>
        <div class="flex flex-col lg:flex-row">

            <!-- Left Sidebar / Navigation (Fixed on Desktop, Top Bar on Mobile) -->
            <aside id="sidebar" class="w-full lg:w-72 bg-white shadow-lg lg:min-h-screen lg:sticky lg:top-0 p-4 border-r border-gray-100">

                <!-- Mobile Menu Header & Toggle (Visible on small screens) -->
                <div class="flex justify-between items-center lg:hidden pb-3 border-b border-gray-100 mb-4">
                    <h1 class="text-xl font-bold text-text-dark">Dashboard</h1>
                    <button id="menu-toggle" class="p-2 text-gray-500 hover:text-primary-blue rounded-full transition-colors">
                        <!-- Icon for Menu -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                    </button>
                </div>

                <!-- Profile Card (Always visible) -->
                <div class="flex items-center space-x-3 mb-8 p-3 bg-gray-50 rounded-lg">
                    <img src="https://placehold.co/40x40/10b981/ffffff?text=U" alt="Profile Image" class="w-10 h-10 rounded-full object-cover ring-2 ring-primary-blue">
                    <div>
                        <p class="text-sm font-semibold text-text-dark">Alex Johnson</p>
                        <p class="text-xs text-gray-500">Member since 2022</p>
                    </div>
                </div>

                <!-- Navigation Links (Hidden on mobile by default, shown when toggled) -->
                <nav id="nav-links" class="hidden lg:block">
                    <h2 class="text-xs font-semibold uppercase text-gray-400 mb-3 ml-2">Services & Activity</h2>
                    <ul class="space-y-1">
                        <!-- Booking Session -->
                        <li>
                            <a href="javascript:void(0)" class="sidebar-item flex items-center p-3 text-text-dark bg-primary-blue/10 font-medium rounded-lg hover:bg-primary-blue/20">
                                <span data-lucide="calendar-check" class="w-5 h-5 mr-3 text-primary-blue"></span>
                                Booking Sessions
                            </a>
                        </li>
                        <!-- Order Products -->
                        <li>
                            <a href="javascript:void(0)" class="sidebar-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-gray-100 hover:text-primary-blue">
                                <span data-lucide="package" class="w-5 h-5 mr-3"></span>
                                Order Products
                            </a>
                        </li>
                        <!-- Transactions -->
                        <li>
                            <a href="javascript:void(0)" class="sidebar-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-gray-100 hover:text-primary-blue">
                                <span data-lucide="credit-card" class="w-5 h-5 mr-3"></span>
                                Transactions
                            </a>
                        </li>
                    </ul>

                    <h2 class="text-xs font-semibold uppercase text-gray-400 mb-3 ml-2 mt-8 border-t pt-4 border-gray-100">Account</h2>
                    <ul class="space-y-1">
                        <!-- Profile Settings -->
                        <li>
                            <a href="javascript:void(0)" class="sidebar-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-gray-100 hover:text-primary-blue">
                                <span data-lucide="settings" class="w-5 h-5 mr-3"></span>
                                Settings
                            </a>
                        </li>
                        <!-- Logout -->
                        <li>
                            <a href="javascript:void(0)" class="sidebar-item flex items-center p-3 text-red-500 rounded-lg hover:bg-red-50 hover:text-red-600">
                                <span data-lucide="log-out" class="w-5 h-5 mr-3"></span>
                                Logout
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <h1 class="text-3xl font-extrabold text-text-dark mb-8">My Dashboard Overview</h1>

                <!-- Basic Information Detail Card -->
                <div class="bg-white p-6 rounded-xl shadow-lg mb-8 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-text-dark flex items-center">
                            <span data-lucide="user" class="w-6 h-6 mr-2 text-primary-blue"></span>
                            Basic Information Detail
                        </h2>
                        <button class="text-sm text-primary-blue hover:text-primary-blue/80 font-medium transition-colors">Edit Profile</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-gray-700">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Full Name</p>
                            <p class="font-semibold">Alex M. Johnson</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Email Address</p>
                            <p class="font-semibold">alex.johnson@example.com</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Phone Number</p>
                            <p class="font-semibold">(555) 123-4567</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Location</p>
                            <p class="font-semibold">San Francisco, CA</p>
                        </div>
                    </div>
                </div>

                <!-- Services Overview Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Booking Sessions Card -->
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-primary-blue">Booking Sessions</h3>
                            <span data-lucide="calendar-check" class="w-6 h-6 text-primary-blue"></span>
                        </div>
                        <p class="text-4xl font-extrabold text-text-dark mb-2">3</p>
                        <p class="text-sm text-gray-500 mb-4">Active & upcoming sessions</p>
                        <a href="#" class="text-sm font-medium text-primary-blue hover:text-primary-blue/80">View Booking History &rarr;</a>
                    </div>

                    <!-- Order Products Card -->
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-text-dark">Order Products</h3>
                            <span data-lucide="package" class="w-6 h-6 text-yellow-500"></span>
                        </div>
                        <p class="text-4xl font-extrabold text-text-dark mb-2">12</p>
                        <p class="text-sm text-gray-500 mb-4">Total product orders placed</p>
                        <a href="#" class="text-sm font-medium text-primary-blue hover:text-primary-blue/80">Track Orders &rarr;</a>
                    </div>

                    <!-- Transactions Card -->
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-text-dark">Transactions</h3>
                            <span data-lucide="credit-card" class="w-6 h-6 text-red-500"></span>
                        </div>
                        <p class="text-4xl font-extrabold text-text-dark mb-2">$1,450</p>
                        <p class="text-sm text-gray-500 mb-4">Total spent this year</p>
                        <a href="#" class="text-sm font-medium text-primary-blue hover:text-primary-blue/80">View Statement &rarr;</a>
                    </div>
                </div>

                <!-- Recent Activity Table (Optional Detail) -->
                <div class="mt-8 bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <h2 class="text-xl font-semibold text-text-dark mb-4">Recent Activity</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2025-10-25</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-blue/10 text-primary-blue">Booking</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Scheduled Yoga Session</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-text-dark">$50.00</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2025-10-22</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Product</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Order #A783 (Protein Powder)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-text-dark">$45.99</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2025-10-15</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Payment</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Subscription Renewal</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-text-dark">$99.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </main>
@endsection