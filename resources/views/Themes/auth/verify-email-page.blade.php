<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DK Healing Center - Verify Email</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans bg-gray-100 flex flex-col min-h-screen">

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
                <div class="flex space-x-3 items-center">
                    <!-- Verification is a critical step, only showing a back link to login/home -->
                    <a href="{{ route("customer.login") }}" class="px-3 py-2 md:px-4 md:py-2 text-white border border-white rounded-lg text-sm md:text-base hover:bg-white hover:text-indigo-900 transition duration-300">
                        Back to Login
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <!-- HEADER END -->

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="max-w-xl w-full bg-white p-8 sm:p-10 rounded-xl shadow-2xl text-center border-t-4 border-indigo-500">
            
            <div class="mx-auto w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6">
                <!-- Lucide: Mail Check Icon -->
                <i data-lucide="mail-check" class="w-8 h-8 text-indigo-600"></i>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 mb-4">Verify Your Email Address</h1>
            
            <p class="text-gray-600 mb-8">
                We've sent a verification link to <strong id="user-email-display">{{ $email }}</strong>.
                Please check your inbox and click the link to activate your account.
            </p>

            <!-- Instructions -->
            <div class="text-left bg-indigo-50 p-6 rounded-lg mb-8 space-y-3">
                <p class="font-semibold text-indigo-700 flex items-center">
                    <i data-lucide="alert-circle" class="w-5 h-5 mr-2 text-indigo-500"></i>
                    What to do next:
                </p>
                <ul class="list-disc list-inside text-sm text-gray-700 space-y-1 ml-4">
                    <li>Check your <strong class="font-medium">inbox</strong> for an email from DK Healing Center.</li>
                    <li>If you don't see it, please check your <strong class="font-medium">spam or junk folder</strong>.</li>
                    <li>The verification link is valid for 24 hours.</li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <form action="{{ route('customer.verification.send') }}" id="resend-button" method="POST">
                    @csrf
                </form>
                <button id="resend-button" onclick="document.querySelector('#resend-button').submit()" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 font-semibold shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    Resend Verification Email
                </button>
                <button onclick="window.location.href='profile.html';" class="w-full px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150 font-semibold">
                    Continue to Dashboard (After Verification)
                </button>
            </div>
            
            <p id="resend-message" class="mt-4 text-sm text-green-600 font-medium hidden">
                Verification email successfully resent! Check your inbox.
            </p>
            <p id="countdown-message" class="mt-4 text-sm text-gray-500 hidden">
                You can resend the email again in <span id="countdown">60</span> seconds.
            </p>

        </div>
    </main>

   @include('Themes.layouts.footer')

    <!-- JavaScript for Resend Logic and Icon Initialization -->
    <script>
        // Placeholder for the user's registered email
        const userEmail = "<?=$email?>"; 

        document.getElementById('user-email-display').textContent = userEmail;
        
        // Initialize Lucide icons
        lucide.createIcons();

        let resendTimer = null;
        const RESEND_TIMEOUT = 60; // 60 seconds

        function startResendCountdown() {
            let timeLeft = RESEND_TIMEOUT;
            const button = document.getElementById('resend-button');
            const countdownSpan = document.getElementById('countdown');
            const countdownMessage = document.getElementById('countdown-message');

            button.disabled = true;
            countdownMessage.classList.remove('hidden');

            resendTimer = setInterval(() => {
                timeLeft--;
                countdownSpan.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(resendTimer);
                    button.disabled = false;
                    countdownMessage.classList.add('hidden');
                    button.textContent = "Resend Verification Email";
                }
            }, 1000);
        }
        // Start countdown immediately on page load to prevent accidental rapid clicks
        window.onload = function() {
            startResendCountdown();
        }
    </script>
</body>
</html>
