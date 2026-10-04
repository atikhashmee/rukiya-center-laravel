<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DK Healing Centre - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            cream: '#fdfbf7',
                            gold: '#c5a880',
                            goldDark: '#b29369',
                            teal: '#0a3c5c',
                            crimson: '#b31b1b',
                            navy: '#0b253a',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-brand-cream text-brand-navy selection:bg-brand-gold selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-brand-teal text-brand-cream py-2 px-4 text-center text-xs tracking-wider font-semibold">
        🕌 AUTHENTIC RUQYAH CLINIC • 100% SECURE UK GDPR COMPLIANT • ACCORDING TO QUR'AN & SUNNAH
    </div>

    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 bg-brand-cream/90 backdrop-blur-md border-b border-brand-gold/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <svg class="w-14 h-14" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="46" fill="white" stroke="#c5a880" stroke-width="2"/>
                    <circle cx="50" cy="50" r="42" fill="none" stroke="#b31b1b" stroke-width="1.5"/>
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#0a3c5c" stroke-width="1.5"/>
                    <text x="24" y="58" font-family="'Playfair Display', serif" font-size="28" font-weight="bold" fill="#0a3c5c">D</text>
                    <text x="48" y="58" font-family="'Playfair Display', serif" font-size="28" font-weight="bold" fill="#b31b1b">K</text>
                </svg>
                <div>
                    <span class="block text-lg font-bold tracking-tight text-brand-teal uppercase">DK Healing Centre</span>
                    <span class="block text-[10px] tracking-widest text-brand-gold font-semibold uppercase">Ruqyah & Prophetic Medicine</span>
                </div>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('customer.login') }}" class="bg-brand-teal hover:bg-brand-navy text-white px-5 py-2.5 rounded-full text-sm font-semibold transition">
                    Login
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-cream/50 border border-brand-gold/20 p-8 md:p-12 rounded-2xl space-y-8">
                <div class="text-center space-y-2">
                    <h2 class="text-2xl font-serif font-bold text-brand-teal">Create Your Account</h2>
                    <p class="text-xs text-slate-500">
                        Already registered? <a href="{{ route('customer.login') }}" class="font-semibold text-brand-gold hover:text-brand-goldDark transition">
                            Sign in here
                        </a>
                    </p>
                </div>

                <form class="space-y-6" action="{{ route('customer.store') }}" method="POST">
                    @csrf

                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-xs font-bold text-brand-teal mb-1">Full Name</label>
                            <input id="name" name="name" type="text" autocomplete="name"  
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold" 
                                placeholder="John Doe">
                            @error('name')
                                <small class="text-brand-crimson text-xs">{{ $message }}</small> 
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-bold text-brand-teal mb-1">Email Address</label>
                            <input id="email" name="email" type="email" autocomplete="email"  
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold" 
                                placeholder="you@example.com">
                            @error('email')
                                <small class="text-brand-crimson text-xs">{{ $message }}</small> 
                            @enderror
                        </div>
                    </div>

                    <!-- Phone Number and Password -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-xs font-bold text-brand-teal mb-1">Phone Number</label>
                            <div class="flex rounded-xl border border-slate-200 overflow-hidden">
                                <select id="country-code" name="phone_prefix" class="border-r border-slate-200 px-3 py-3 text-slate-500 text-sm focus:outline-none focus:border-brand-gold bg-white">
                                    <option>+1</option>
                                    <option>+44</option>
                                    <option>+91</option>
                                    <option>+61</option>
                                    <option>+00</option>
                                </select>
                                <input id="phone" name="phone" type="tel" autocomplete="tel"  
                                    class="flex-1 w-full px-4 py-3 border-0 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold" 
                                    placeholder="555-123-4567">
                            </div>
                        </div>
                        <div>
                            <label for="password" class="block text-xs font-bold text-brand-teal mb-1">Password</label>
                            <input id="password" name="password" type="password" autocomplete="new-password"  
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold" 
                                placeholder="Min 8 characters">
                        </div>
                    </div>
                    @error('phone')
                        <small class="text-brand-crimson text-xs">{{ $message }}</small> 
                    @enderror
                    @error('password')
                        <small class="text-brand-crimson text-xs">{{ $message }}</small> 
                    @enderror

                    <!-- Interests Checkboxes -->
                    <div>
                        <label class="block text-xs font-bold text-brand-teal mb-3">Interests in Services (Select all that apply)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="flex items-center">
                                <input id="interest-listening" name="interests[]" type="checkbox" value="listening" class="h-4 w-4 text-brand-teal border-slate-300 rounded focus:ring-brand-gold">
                                <label for="interest-listening" class="ml-3 text-sm text-slate-600">Sacred Listening</label>
                            </div>
                            <div class="flex items-center">
                                <input id="interest-rukiya" name="interests[]" type="checkbox" value="rukiya" class="h-4 w-4 text-brand-teal border-slate-300 rounded focus:ring-brand-gold">
                                <label for="interest-rukiya" class="ml-3 text-sm text-slate-600">Personalized Rukiya</label>
                            </div>
                            <div class="flex items-center">
                                <input id="interest-istikhara" name="interests[]" type="checkbox" value="istikhara" class="h-4 w-4 text-brand-teal border-slate-300 rounded focus:ring-brand-gold">
                                <label for="interest-istikhara" class="ml-3 text-sm text-slate-600">Istikhara Guidance</label>
                            </div>
                        </div>
                        @error('interests')
                            <small class="text-brand-crimson text-xs">{{ $message }}</small> 
                        @enderror
                    </div>

                    <!-- About Himself -->
                    <div>
                        <label for="about" class="block text-xs font-bold text-brand-teal mb-1">Tell us a little about yourself (Optional)</label>
                        <textarea id="about" name="about" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand-gold" placeholder="I am looking for guidance on a major life decision..."></textarea>
                        <p class="mt-2 text-xs text-slate-400">Briefly share your goals or why you're seeking guidance.</p>
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
                            <div class="mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-brand-crimson text-xs">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button type="submit" class="w-full bg-brand-teal hover:bg-brand-navy text-white font-bold py-3.5 rounded-xl transition shadow text-sm">
                            Register and Begin Your Healing Journey
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-12 bg-brand-navy text-slate-400 text-xs border-t border-brand-gold/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3 text-center">
            <p class="uppercase text-brand-gold font-bold tracking-widest">Important Legal Healthcare Notice</p>
            <p class="leading-relaxed text-[11px]">
                DK Healing Centre offers traditional supplementary spiritual consultations based strictly upon authentic Ruqyah Shariah practices and prophetic sunnah guidelines.
            </p>
            <p class="pt-4">&copy; {{ date('Y') }} DK Healing Centre. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>