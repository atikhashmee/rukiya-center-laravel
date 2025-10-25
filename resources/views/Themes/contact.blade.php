@extends('themes.layouts.layout')

@section('content')
    <main>
         <!-- Contact Page -->
        <div id="contact" class="page">
            <section class="container">
                <div class="hero">
                    <h1>Contact Us</h1>
                    <p>Reach out to begin your healing journey</p>
                </div>

                <div class="form-container">
                    <h3>Get in Touch</h3>
                    <form onsubmit="submitContact(event)">
                        <div class="form-group">
                            <label for="contact-name">Full Name</label>
                            <input type="text" id="contact-name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-email">Email</label>
                            <input type="email" id="contact-email" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-subject">Subject</label>
                            <input type="text" id="contact-subject" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-message">Message</label>
                            <textarea id="contact-message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                    </form>
                </div>

                <div style="text-align: center; margin-top: 3rem; color: white;">
                    <h3>Visit Our Centre</h3>
                    <p>📍 123 Healing Way, Wellness District<br>
                    📞 +1 (555) 123-4567<br>
                    ✉️ info@dkhealingcentre.com</p>
                </div>
            </section>
        </div>
    </main>
@endsection