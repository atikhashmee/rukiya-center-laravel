@extends('themes.layouts.layout')
@section('content')
    <main>
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