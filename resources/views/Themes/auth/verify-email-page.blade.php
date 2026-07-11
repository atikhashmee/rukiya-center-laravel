<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DK Healing Centre - Verify Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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
                    Back to Login
                </a>
            </div>
        </div>
    </header>

    <main class="py-20 bg-white">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-cream/50 border border-brand-gold/20 p-8 sm:p-10 rounded-2xl text-center space-y-6">
                <div class="mx-auto w-16 h-16 bg-brand-gold/10 rounded-full flex items-center justify-center">
                    <i data-lucide="mail-check" class="w-8 h-8 text-brand-gold"></i>
                </div>

                <h1 class="text-2xl font-serif font-bold text-brand-teal">Verify Your Email Address</h1>

                <p class="text-sm text-slate-600">
                    We've sent a verification link to <strong id="user-email-display" class="text-brand-teal">{{ $email }}</strong>.
                    Please check your inbox and click the link to activate your account.
                </p>

                <div class="text-left bg-white border border-brand-gold/20 p-6 rounded-2xl space-y-2">
                    <p class="font-semibold text-brand-teal text-sm">What to do next:</p>
                    <ul class="text-xs text-slate-600 space-y-1 ml-4">
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> Check your inbox for an email from DK Healing Centre.</li>
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> If you don't see it, please check your spam or junk folder.</li>
                        <li class="flex items-center gap-2"><span class="text-brand-gold">✓</span> The verification link is valid for 24 hours.</li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <form action="{{ route('customer.verification.send') }}" id="resend-form" method="POST">
                        @csrf
                    </form>
                    <button id="resend-button" onclick="document.querySelector('#resend-form').submit()" class="w-full bg-brand-teal hover:bg-brand-navy text-white px-6 py-3 rounded-xl font-semibold text-sm transition shadow disabled:opacity-50 disabled:cursor-not-allowed">
                        Resend Verification Email
                    </button>
                    <button onclick="window.location.href='{{ route('customer.profile') }}';" class="w-full border border-slate-200 text-slate-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-slate-50 transition">
                        Continue to Dashboard (After Verification)
                    </button>
                </div>

                <p id="resend-message" class="text-xs text-green-600 font-medium hidden">
                    Verification email successfully resent! Check your inbox.
                </p>
                <p id="countdown-message" class="text-xs text-slate-400 hidden">
                    You can resend the email again in <span id="countdown">60</span> seconds.
                </p>
            </div>
        </div>
    </main>

    @include('Themes.layouts.footer')

    <script>
        const userEmail = "<?=$email?>";
        document.getElementById('user-email-display').textContent = userEmail;
        lucide.createIcons();

        let resendTimer = null;
        const RESEND_TIMEOUT = 60;

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
        window.onload = function() { startResendCountdown(); }
    </script>
</body>
</html>