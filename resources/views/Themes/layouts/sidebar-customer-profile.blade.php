 <!-- LEFT NAVIGATION BAR (Sidebar) - Made more professional and separated logout -->
            <nav class="lg:w-1/4 bg-white p-6 rounded-xl shadow-2xl h-fit sticky top-10 flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 mb-4 pb-2 border-b border-gray-200">User Dashboard</h2>
                    <ul class="space-y-6">
                        <li>
                            <a href="{{ route("customer.profile") }}" class="w-full text-left py-3 px-4 rounded-lg text-sm transition duration-150 font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active-nav">
                                Profile Information
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.mybooking') }}" class="w-full text-left py-3 px-4 rounded-lg text-sm transition duration-150 font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                Services Booked
                            </a>
                        </li>
                        <li>
                            <a href="#" class="w-full text-left py-3 px-4 rounded-lg text-sm transition duration-150 font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                Payments Made
                            </a>
                        </li>
                        <li>
                            <a href="#" class="w-full text-left py-3 px-4 rounded-lg text-sm transition duration-150 font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                Account Settings
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- LOGOUT BUTTON - Separated at the bottom of the dashboard menu -->
                <div class="mt-8 pt-4 border-t border-gray-200">
                    <form action="{{route("customer.logout")}}" method="POST" id="logout-form">
                        @csrf
                    </form>
                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-full text-center py-3 px-4 rounded-lg text-sm font-bold transition duration-150 text-red-700 bg-red-50 hover:bg-red-100 border border-red-200">
                        Logout
                    </button>
                </div>
            </nav>