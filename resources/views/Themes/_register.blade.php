@extends('themes.layouts.layout')

@section('content')
   <main>
     <!-- Register Page -->
        <div id="register" class="page">
            <section class="container">
                <div class="form-container">
                    <h2 style="text-align: center; margin-bottom: 2rem;">Join DK Healing Centre</h2>
                    <form method="POST" action="{{route("customer.store")}}">
                        @csrf
                        <div class="form-group">
                            <label for="reg-name">Full Name</label>
                            <input type="text" name="name">
                            @error('name')
                                <small class="text-red-500">{{ $message }}</small> 
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="reg-email">Email</label>
                            <input type="email" name="email">
                            @error('email')
                                <small class="text-red-500">{{ $message }}</small> 
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="reg-phone">Phone Number</label>
                            <input type="hidden" name="phone_prefix" value="880">
                            <input type="tel" name="phone">
                            @error('phone')
                                <small class="text-red-500">{{ $message }}</small> 
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="interest">Primary Interest</label>
                            <select name="interests[]" multiple>
                                <option value="">Select Your Interests</option>
                                <option value="counselling">Counselling Services</option>
                                <option value="estekhara">Estekhara Guidance</option>
                                <option value="mental-health">Rukiya Services</option>
                                <option value="all">All Services</option>
                            </select>
                            @error('interests')
                                <small class="text-red-500">{{ $message }}</small> 
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password">
                            @error('password')
                                <small class="text-red-500">{{ $message }}</small> 
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="reg-message">Tell us about yourself (optional)</label>
                            <textarea name="about" rows="3" placeholder="Share what brings you to our healing centre..."></textarea>
                        </div>
                        @if ($errors->any())
                            <div class="bg-green-800">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-900">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Application</button>
                    </form>
                    <p style="text-align: center; margin-top: 1rem;">
                        Already a member? <a href="{{ route("customer.login") }}" style="color: #764ba2;">Login here</a>
                    </p>
                </div>
            </section>
        </div>
    </main> 
@endsection