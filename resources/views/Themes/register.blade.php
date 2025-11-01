<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DK Healing Center - Register</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Configure Tailwind for Inter font -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'theme-primary': '#4F46E5', // Indigo
                        'theme-accent': '#C7D2FE', // Indigo-200
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght400;600;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans bg-gray-50 flex flex-col min-h-screen">

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
                    <a href="{{ route("customer.login") }}" class="px-3 py-2 md:px-4 md:py-2 text-white border border-white rounded-lg text-sm md:text-base hover:bg-white hover:text-indigo-900 transition duration-300">
                        Login
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <!-- HEADER END -->

    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-3xl bg-white p-8 md:p-12 rounded-2xl shadow-2xl border-t-8 border-indigo-600">
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Create Your Account
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Already registered? <a href="{{ route("customer.login") }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Sign in here
                    </a>
                </p>
            </div>
            
            <form class="mt-8 space-y-8" action="{{route("customer.store")}}" method="POST">
                @csrf
                
                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input id="name" name="name" type="text" autocomplete="name"  
                               class="mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="John Doe">
                        @error('name')
                            <small class="text-red-500">{{ $message }}</small> 
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email"  
                               class="mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="you@example.com">
                        @error('email')
                            <small class="text-red-500">{{ $message }}</small> 
                        @enderror
                    </div>
                </div>

                <!-- Phone Number and Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="mt-1 flex rounded-lg shadow-sm">
                            <select id="country-code" name="phone_prefix" class="border-y border-l border-gray-300 rounded-l-lg p-3 text-gray-500 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option>+1</option>
                                <option>+44</option>
                                <option>+91</option>
                                <option>+61</option>
                                <option>+00</option>
                            </select>
                            <input id="phone" name="phone" type="tel" autocomplete="tel"  
                                   class="flex-1 block w-full px-3 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                   placeholder="555-123-4567">
                           
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password"  
                               class="mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="Min 8 characters">
                    </div>
                </div>
                @error('phone')
                    <small class="text-red-500">{{ $message }}</small> 
                @enderror
                @error('password')
                    <small class="text-red-500">{{ $message }}</small> 
                @enderror

                <!-- Interests Checkboxes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Interests in Services (Select all that apply)</label>
                    <div class="mt-1 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <input id="interest-listening" name="interests[]" type="checkbox" value="listening" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="interest-listening" class="ml-3 text-sm font-medium text-gray-700">Sacred Listening</label>
                        </div>
                        <div class="flex items-center">
                            <input id="interest-rukiya" name="interests[]" type="checkbox" value="rukiya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="interest-rukiya" class="ml-3 text-sm font-medium text-gray-700">Personalized Rukiya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="interest-istikhara" name="interests[]" type="checkbox" value="istikhara" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="interest-istikhara" class="ml-3 text-sm font-medium text-gray-700">Istikhara Guidance</label>
                        </div>
                    </div>
                    @error('interests')
                        <small class="text-red-500">{{ $message }}</small> 
                    @enderror
                </div>

                <!-- About Himself -->
                <div>
                    <label for="about" class="block text-sm font-medium text-gray-700">Tell us a little about yourself (Optional)</label>
                    <div class="mt-1">
                        <textarea id="about" name="about" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-lg p-3" placeholder="I am looking for guidance on a major life decision..."></textarea>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Briefly share your goals or why you're seeking guidance.</p>
                </div>
                
                <!-- Submit Button -->
                <div>
                    @if ($errors->any())
                        @php
                            $errors->forget("name");
                            $errors->forget("email");
                            $errors->forget("phone_prefix");
                            $errors->forget("phone");
                            $errors->forget("password");
                            $errors->forget("interests");
                        @endphp
                        <div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-900">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent text-lg font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out shadow-xl">
                        Register and Begin Your Healing Journey
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- FOOTER (Consistent) -->
    <footer class="bg-gray-900 text-white py-10 text-center">
        <p>&copy; 2023 DK Healing Center. All rights reserved.</p>
        <p class="text-sm text-gray-400 mt-1">Built with spiritual guidance and modern technology.</p>
    </footer>

</body>
</html>
