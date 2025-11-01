<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DK Healing Center - Inner Harmony and Peace</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Configure Tailwind for Inter font -->
    @stack('css')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        // Customizing colors for a more spiritual/calm theme
                        'theme-primary': '#4F46E5', // Indigo
                        'theme-accent': '#C7D2FE', // Indigo-200
                        'theme-gold': '#FBBF24', // Amber/Gold for highlights
                        'theme-dark-blue': '#1a202c', // Darker blue for background texture
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght400;600;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans bg-gray-50">
    @yield('content')
    @include('Themes.layouts.footer')
    @stack('scripts')
</body>
</html>