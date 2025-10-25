@extends('themes.layouts.layout')
@section('content')
    <main>
        <!-- Home Page -->
        <div id="home" class="page active">
            <section class="hero">
                <div class="container">
                    <h1>Welcome to DK Healing Centre</h1>
                    <p>Your sanctuary for spiritual growth, mental wellness, and holistic healing</p>
                    <a href="#" class="btn btn-primary" onclick="showPage('register')">Begin Your Journey</a>
                </div>
            </section>

            <section class="container">
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">🧘‍♀️</div>
                        <h3>Counselling Services</h3>
                        <p>Professional guidance for life's challenges with compassionate support and proven therapeutic approaches.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">🤲</div>
                        <h3>Estekhara Guidance</h3>
                        <p>Spiritual consultation and divine guidance for important life decisions through traditional Islamic practices.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">💚</div>
                        <h3>Rukiya Services</h3>
                        <p>Comprehensive spiritual healing programs combining traditional Rukiya practices with holistic wellness approaches.</p>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
    

   
