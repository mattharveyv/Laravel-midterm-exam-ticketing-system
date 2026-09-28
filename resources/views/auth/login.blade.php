@extends('layouts.auth')

@section('title', 'Login — NEXA Desk')

@section('auth-content')
<div class="auth-heading"><span class="auth-kicker">YOUR SUPPORT, ALL IN ONE PLACE</span><h1>Welcome back</h1><p>Sign in to check your requests and get help.</p></div>
<form id="login-form" class="auth-form" novalidate>
    <div id="login-error" class="form-alert" role="alert" hidden>Invalid email or password.</div>
    <x-input label="Email address" name="email" type="email" placeholder="you@company.com" required autocomplete="email" />
    <div class="field-wrap"><div class="field-label-row"><label for="login-password">Password<span class="required-mark">*</span></label><a href="{{ route('forgot-password') }}">Forgot password?</a></div><div class="password-wrap"><input class="field-input" type="password" id="login-password" name="password" placeholder="Enter your password" required autocomplete="current-password"><button class="password-toggle" type="button" data-toggle-password="login-password">Show</button></div></div>
    <label class="checkbox-row"><input type="checkbox" name="remember"><span>Remember me</span></label>
    <button class="btn btn-primary btn-wide" id="login-submit" type="submit"><span class="button-label">Login</span><span class="button-spinner" hidden></span></button>
</form>
<p class="auth-switch">Don't have an account? <a href="{{ route('register') }}">Create account</a></p>
<div class="demo-credentials"><span class="demo-label">DEMO ACCESS</span><p><strong>Customer</strong> user@nexadesk.com <span>·</span> password</p><p><strong>Admin</strong> admin@gmail.com <span>·</span> admin123</p></div>
@endsection
