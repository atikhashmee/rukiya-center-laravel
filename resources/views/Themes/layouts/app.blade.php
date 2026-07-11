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
    @stack('scripts')
</body>
</html>