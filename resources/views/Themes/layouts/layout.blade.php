<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DK Healing Centre - Spiritual & Mental Health Support</title>
    <link rel="stylesheet" href="{{ asset('themes/assets/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">
                <a href="{{ route('home') }}">
                🕊️ DK Healing Centre
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="{{route("home")}}" class="active">Home</a></li>
                <li><a href="{{route("service", ["name" => "counselling"])}}" >Counselling</a></li>
                <li><a href="{{route("service" , ["name" => "estekhara"])}}" >Estekhara</a></li>
                <li><a href="{{route("service", ["name" => "mental-health"])}}" >Rukiya</a></li>
                <li><a href="{{route("about")}}" >About</a></li>
                <li><a href="{{route("contact")}}" >Contact</a></li>
            </ul>
            <div class="auth-buttons">
                @auth("customer")
                <form action="{{route("customer.logout")}}" method="POST" id="logout-form">
                    @csrf
                </form>
                <a href="javascript:void(0)" onclick="document.getElementById('logout-form').submit()" class="btn btn-outline" onclick="logout()" id="logoutBtn">Logout</a>
                @else
                    <a href="{{route("customer.login")}}" class="btn btn-outline"  id="loginBtn">Login</a>
                    <a href="{{route("customer.register")}}" class="btn btn-primary"  id="registerBtn">Join Us</a>
                @endauth
            </div>
        </nav>
    </header>
    @yield('content')
     <footer>
        <div class="container">
            <p>&copy; 2024 DK Healing Centre. All rights reserved. | Healing minds, nurturing souls, building community.</p>
        </div>
    </footer>
<script src="{{asset("themes/assets/js/script.js")}}"></script>
</body>
</html>