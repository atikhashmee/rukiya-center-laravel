@extends('themes.layouts.layout')

@section('content')
    <main>
        <div class="flex justify-between bg-white gap-2 px-4">
            <div class="border min-w-[200px] min-h-[200px]">
                <h1 class="text-center">Hello world</h1>
            </div>
            <div class="border min-w-[200px] min-h-[200px]">
                <h1 class="text-center">Hello world, asdfd</h1>
            </div>
        </div>
         <!-- Counselling Page -->
        <div id="counselling" class="page">
            <section class="container">
                <div class="hero">
                    <h1>Counselling Services</h1>
                    <p>Professional therapeutic support for your emotional and psychological well-being</p>
                </div>

                <div class="services-grid">
                    <div class="service-card">
                        <h3>Individual Counselling</h3>
                        <div class="price-tag">£50 per session (60 minutes)</div>
                        <p>One-on-one sessions tailored to your personal needs and goals.</p>
                        <ul style="text-align: left; margin-top: 1rem;">
                            <li>Anxiety and Depression Support</li>
                            <li>Stress Management</li>
                            <li>Life Transitions</li>
                            <li>Relationship Issues</li>
                        </ul>
                    </div>
                    <div class="service-card">
                        <h3>Family Counselling</h3>
                        <div class="price-tag">£80 per session (90 minutes)</div>
                        <p>Healing relationships and strengthening family bonds through guided therapy.</p>
                        <ul style="text-align: left; margin-top: 1rem;">
                            <li>Marriage Counselling</li>
                            <li>Parent-Child Relationships</li>
                            <li>Family Communication</li>
                            <li>Conflict Resolution</li>
                        </ul>
                    </div>
                    <div class="service-card">
                        <h3>Group Therapy</h3>
                        <div class="price-tag">£40 per session (90 minutes)</div>
                        <p>Supportive group sessions for shared healing and community support.</p>
                        <ul style="text-align: left; margin-top: 1rem;">
                            <li>Support Groups</li>
                            <li>Healing Circles</li>
                            <li>Mindfulness Sessions</li>
                            <li>Peer Support</li>
                        </ul>
                    </div>
                </div>

                <div class="form-container">
                    <h3>Book a Counselling Session</h3>
                    <form onsubmit="bookSession(event, 'counselling')">
                        <div class="form-group">
                            <label for="counselling-name">Full Name</label>
                            <input type="text" id="counselling-name" required>
                        </div>
                        <div class="form-group">
                            <label for="counselling-email">Email</label>
                            <input type="email" id="counselling-email" required>
                        </div>
                        <div class="form-group">
                            <label for="counselling-type">Session Type</label>
                            <select id="counselling-type" required>
                                <option value="">Select Session Type</option>
                                <option value="individual">Individual Counselling</option>
                                <option value="family">Family Counselling</option>
                                <option value="group">Group Therapy</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="counselling-message">Brief Description of Your Needs</label>
                            <textarea id="counselling-message" rows="4" placeholder="Please share what you'd like to work on..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Book Session</button>
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection