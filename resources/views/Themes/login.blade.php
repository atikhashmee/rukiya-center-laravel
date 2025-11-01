    @extends('themes.layouts.app')

    @section('content')
    <div class="flex flex-col min-h-screen">
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
                    <div class="flex space-x-3 items-center">
                        <a href="{{ route("customer.register") }}"  class="px-3 py-2 md:px-4 md:py-2 text-indigo-900 bg-indigo-300 rounded-lg text-sm md:text-base font-semibold hover:bg-indigo-400 transition duration-300">
                            Register
                        </a>
                    </div>
                </div>
            </nav>
        </header>
        <!-- HEADER END -->

        <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md bg-white p-8 md:p-10 rounded-2xl shadow-2xl border-t-8 border-indigo-600">
                <div class="text-center">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Sign in to your account
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Or <a href="{{ route("customer.register") }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            register for a new account
                        </a>
                    </p>
                </div>
                
                <form class="mt-8 space-y-6" action="{{ route("customer.login.auth") }}" method="POST">
                    @csrf
                    <input type="hidden" name="remember" value="true">
                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="email-address" class="sr-only">Email address</label>
                            <input id="email-address" name="email" type="email" autocomplete="email"  
                                class="appearance-none rounded-t-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                placeholder="Email address">
                        </div>
                        <div>
                            <label for="password" class="sr-only">Password</label>
                            <input id="password" name="password" type="password" autocomplete="current-password"  
                                class="appearance-none rounded-b-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                                placeholder="Password">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                                Forgot your password?
                            </a>
                        </div>
                    </div>

                    <div>
                         @if ($errors->any())
                            <div>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-900">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <!-- Lock icon -->
                                <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2V7a3 3 0 00-6 0v2h6z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    @endsection
   
