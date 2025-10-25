@extends('themes.layouts.layout')

@section('content')
    <main>
        <div id="about" class="page">
            <section class="container">
                <div class="hero">
                   @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                        
                    <h1>Your email is not verified</h1>
                    <p>Please check your email for the verification email</p>
                    <form action="{{ route('customer.verification.send') }}" method="POST">
                        @csrf
                        <button type="submit">Send again</button>
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection