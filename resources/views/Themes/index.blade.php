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

        <!-- Estekhara Page -->
        <div id="estekhara" class="page">
            <section class="container">
                <div class="hero">
                    <h1>Estekhara Guidance</h1>
                    <p>Seek divine guidance for life's important decisions through traditional Islamic consultation</p>
                </div>

                <div class="services-grid">
                    <div class="service-card">
                        <h3>Individual Estekhara</h3>
                        <div class="price-tag">First 30 min FREE • Then £50/hour</div>
                        <p>Individual spiritual consultation for personal life decisions.</p>
                        <ul style="text-align: left; margin-top: 1rem;">
                            <li>Marriage Decisions</li>
                            <li>Career Choices</li>
                            <li>Business Ventures</li>
                            <li>Life Path Guidance</li>
                        </ul>
                    </div>
                    <div class="service-card">
                        <h3>Family Estekhara</h3>
                        <div class="price-tag">First 30 min FREE • Then £80/hour</div>
                        <p>Spiritual guidance for family-related decisions and matters.</p>
                        <ul style="text-align: left; margin-top: 1rem;">
                            <li>Family Planning</li>
                            <li>Property Decisions</li>
                            <li>Children's Future</li>
                            <li>Family Harmony</li>
                        </ul>
                    </div>
                    <div class="service-card">
                        <h3>Business Estekhara</h3>
                        <div class="price-tag">First 30 min FREE • Then £80/hour</div>
                        <p>Divine guidance for business and financial decisions.</p>
                        <ul style="text-align: left; margin-top: 1rem;">
                            <li>Investment Decisions</li>
                            <li>Partnership Choices</li>
                            <li>Business Expansion</li>
                            <li>Financial Planning</li>
                        </ul>
                    </div>
                </div>

                <div class="form-container">
                    <h3>Request Estekhara Consultation</h3>
                    <form onsubmit="bookSession(event, 'estekhara')">
                        <div class="form-group">
                            <label for="estekhara-name">Full Name</label>
                            <input type="text" id="estekhara-name" required>
                        </div>
                        <div class="form-group">
                            <label for="estekhara-email">Email</label>
                            <input type="email" id="estekhara-email" required>
                        </div>
                        <div class="form-group">
                            <label for="estekhara-type">Consultation Type</label>
                            <select id="estekhara-type" required>
                                <option value="">Select Consultation Type</option>
                                <option value="individual">Individual Estekhara</option>
                                <option value="family">Family Estekhara</option>
                                <option value="business">Business Estekhara</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estekhara-decision">Decision You're Seeking Guidance For</label>
                            <textarea id="estekhara-decision" rows="4" placeholder="Please describe the decision you need guidance for..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Request Consultation</button>
                    </form>
                </div>
            </section>
        </div>

        <!-- Mental Health Page -->
        <div id="mental-health" class="page">
            <section class="container">
                <div class="hero">
                    <h1>Rukiya Services</h1>
                    <p>Traditional Islamic spiritual healing combining Quranic recitation with holistic wellness</p>
                </div>

                <div class="services-grid">
                    <div class="service-card">
                        <h3>Individual Rukiya</h3>
                        <div class="price-tag">£50 per session (45 minutes)</div>
                        <p>Traditional Rukiya for spiritual purification and protection from negative influences.</p>
                        <ul style="text-align: left; margin-top: 1rem;">
                            <li>Quranic Recitation</li>
                            <li>Evil Eye Protection</li>
                            <li>Spiritual Cleansing</li>
                            <li>Protective Prayers</li>
                        </ul>
                    </div>
                    <div class="service-card">
                        <h3>Group Healing Sessions</h3>
                        <div class="price-tag">£120 per session (up to 3 people)</div>
                        <p>Comprehensive Rukiya sessions for physical and emotional healing through Islamic practices.</p>
                        <ul style="text-align: left; margin-top: 1rem;">
                            <li>Quranic Healing</li>
                            <li>Prophetic Medicine</li>
                            <li>Spiritual Counseling</li>
                            <li>Islamic Meditation</li>
                        </ul>
                    </div>
                    <div class="service-card">
                        <h3>Family Protection</h3>
                        <div class="price-tag">£120 per session (up to 3 people)</div>
                        <p>Rukiya services for family harmony and protection from spiritual disturbances.</p>
                        <ul style="text-align: left; margin-top: 1rem;">
                            <li>Home Blessing</li>
                            <li>Family Healing</li>
                            <li>Marriage Protection</li>
                            <li>Children's Wellness</li>
                        </ul>
                    </div>
                </div>

                <div class="form-container">
                    <h3>Rukiya Session Request</h3>
                    <form onsubmit="bookSession(event, 'mental-health')">
                        <div class="form-group">
                            <label for="mh-name">Full Name</label>
                            <input type="text" id="mh-name" required>
                        </div>
                        <div class="form-group">
                            <label for="mh-email">Email</label>
                            <input type="email" id="mh-email" required>
                        </div>
                        <div class="form-group">
                            <label for="mh-concern">Type of Rukiya Needed</label>
                            <select id="mh-concern" required>
                                <option value="">Select Rukiya Type</option>
                                <option value="individual-rukiya">Individual Rukiya</option>
                                <option value="group-healing">Group Healing Sessions</option>
                                <option value="family-protection">Family Protection</option>
                                <option value="evil-eye">Evil Eye Protection</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mh-description">Description of Your Situation</label>
                            <textarea id="mh-description" rows="4" placeholder="Please share what you're experiencing..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Request Rukiya Session</button>
                    </form>
                </div>
            </section>
        </div>

      

       

        <!-- Member Dashboard -->
        <div id="dashboard" class="page">
            <section class="container">
                <div class="member-dashboard">
                    <h2>Welcome to Your Dashboard</h2>
                    <div id="member-info">
                        <p><strong>Status:</strong> <span id="member-status" class="status-badge status-pending">Pending Approval</span></p>
                        <p><strong>Member Since:</strong> <span id="member-date"></span></p>
                    </div>
                    
                    <div style="margin-top: 2rem;">
                        <h3>Your Appointments</h3>
                        <div id="member-appointments">
                            <p style="color: #666;">No appointments scheduled yet.</p>
                        </div>
                    </div>

                    <div style="margin-top: 2rem;">
                        <h3>Quick Actions</h3>
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <a href="#" class="btn btn-primary" onclick="showPage('counselling')">Book Counselling</a>
                            <a href="#" class="btn btn-primary" onclick="showPage('estekhara')">Request Estekhara</a>
                            <a href="#" class="btn btn-primary" onclick="showPage('mental-health')">Rukiya Services</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Admin Panel -->
        <div id="admin" class="page">
            <section class="container">
                <div class="admin-panel">
                    <h2>Administration Panel</h2>
                    
                    <div style="margin-bottom: 2rem;">
                        <h3>Pending Member Applications</h3>
                        <div class="member-list" id="pending-members">
                            <!-- Pending members will be populated here -->
                        </div>
                    </div>

                    <div>
                        <h3>All Members</h3>
                        <div class="member-list" id="all-members">
                            <!-- All members will be populated here -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
    

   
