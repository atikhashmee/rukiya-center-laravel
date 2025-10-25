@extends('themes.layouts.layout')

@section('content')
    <main>
        <div id="login" class="page">
            <section class="container">
                <div class="form-container">
                    <h2 style="text-align: center; margin-bottom: 2rem;">Member Login</h2>
                    <form method="POST" action="{{ route("customer.login.auth") }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" >
                            @error('email')
                               <small class="text-red-800">{{ $message }}</small> 
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" >
                            @error('password')
                               <small class="text-red-800">{{ $message }}</small> 
                            @enderror
                        </div>
                         @if ($errors->any())
                         @php
                            $errors->forget("email");
                            $errors->forget("password");
                         @endphp
                            <div>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-900">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                    </form>
                    <p style="text-align: center; margin-top: 1rem;">
                        Don't have an account? <a href="{{ route("customer.register") }}"  style="color: #764ba2;">Join us</a>
                    </p>
                </div>
            </section>
        </div>
    </main>
@endsection