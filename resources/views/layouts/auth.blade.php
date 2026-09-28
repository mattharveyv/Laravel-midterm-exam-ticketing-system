@extends('layouts.guest')

@section('content')
    <main class="auth-page">
        <a class="brand brand-light" href="{{ route('home') }}"><span class="brand-symbol">N</span><span><strong>NEXA Desk</strong><small>SMART TICKETING & SERVICE HUB</small></span></a>
        <section class="auth-card">
            @yield('auth-content')
        </section>
        <p class="auth-foot">Simple support. Clear answers. <a href="{{ route('home') }}">Back to NEXA Desk</a></p>
    </main>
@endsection
