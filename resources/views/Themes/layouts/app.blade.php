<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DK Healing Centre - Authentic Ruqyah & Sunnah Remedies</title>
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
    @stack('css')
</head>
<body class="bg-brand-cream text-brand-navy selection:bg-brand-gold selection:text-white">
    @yield('content')
    @include('Themes.layouts.footer')

    @php($whatsappNumber = \App\Models\Setting::get('whatsapp_number'))
    @if ($whatsappNumber)
        <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
           class="fixed bottom-5 right-5 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg transition hover:scale-110">
            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.64.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.21 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35zM12.05 21.5h-.01a9.4 9.4 0 0 1-4.8-1.31l-.34-.2-3.56.93.95-3.47-.23-.36a9.4 9.4 0 0 1-1.44-5.02C2.62 6.87 6.84 2.65 12.05 2.65c2.52 0 4.9.98 6.68 2.77a9.36 9.36 0 0 1 2.76 6.67c0 5.2-4.23 9.41-9.44 9.41zm8.03-17.45A11.27 11.27 0 0 0 12.05.75C5.8.75.71 5.84.71 12.1c0 2 .52 3.95 1.52 5.67L.62 23.25l5.6-1.47a11.3 11.3 0 0 0 5.83 1.48h.01c6.25 0 11.34-5.09 11.34-11.35 0-3.03-1.18-5.88-3.32-8.02z"/></svg>
        </a>
    @endif

    @stack('scripts')
</body>
</html>