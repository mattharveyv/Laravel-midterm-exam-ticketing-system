@extends('layouts.auth')

@section('title', 'Staff Login — Problinx')

@section('auth-content')
<div class="auth-heading"><span class="auth-kicker">PROBLINX · STAFF CONSOLE</span><h1>Admin sign in</h1><p>Use the demo administrator account to open the support console.</p></div>
<form id="admin-login-form" class="auth-form" novalidate>
    <div id="admin-login-error" class="form-alert" role="alert" hidden>Invalid email or password.</div>
    <x-input label="Admin email" name="email" type="email" placeholder="admin@gmail.com" required autocomplete="username" />
    <div class="field-wrap"><label for="admin-password">Password<span class="required-mark">*</span></label><div class="password-wrap"><input class="field-input" type="password" id="admin-password" name="password" placeholder="Enter admin password" required autocomplete="current-password"><button class="password-toggle" type="button" data-toggle-password="admin-password">Show</button></div></div>
    <button class="btn btn-primary btn-wide" type="submit"><span class="button-label">Open support console</span><span class="button-spinner" hidden></span></button>
</form><p class="auth-switch"><a href="{{ route('login') }}">← Back to customer login</a></p><div class="demo-credentials"><span class="demo-label">DEMO ADMIN</span><p>admin@gmail.com <span>·</span> admin123</p></div>
@endsection
