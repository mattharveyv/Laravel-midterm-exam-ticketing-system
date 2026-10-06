@extends('layouts.auth')

@section('title', 'Create Account — Problinx')

@section('auth-content')
<div class="auth-heading"><span class="auth-kicker">GET SUPPORT WITHOUT THE RUNAROUND</span><h1>Create your account</h1><p>Submit and manage your support tickets in one place.</p></div>
<form id="register-form" class="auth-form" novalidate>
    <div id="register-error" class="form-alert" role="alert" hidden></div>
    <div class="form-two"><x-input label="First name" name="first_name" placeholder="John" required autocomplete="given-name" /><x-input label="Last name" name="last_name" placeholder="Davis" required autocomplete="family-name" /></div>
    <x-input label="Email address" name="email" type="email" placeholder="you@company.com" required autocomplete="email" />
    <div class="form-two"><x-input label="Phone number" name="phone" type="tel" placeholder="(555) 000-0000" /><div class="field-wrap"><label for="department">Department</label><select id="department" class="field-input" name="department"><option value="">Choose department</option><option>IT</option><option>Product</option><option>Design</option><option>Finance</option><option>Operations</option><option>People</option><option>Other</option></select></div></div>
    <div class="field-wrap"><label for="register-password">Password<span class="required-mark">*</span></label><div class="password-wrap"><input class="field-input" id="register-password" name="password" type="password" placeholder="At least 8 characters" minlength="8" required autocomplete="new-password"><button class="password-toggle" type="button" data-toggle-password="register-password">Show</button></div></div>
    <x-input label="Confirm password" name="password_confirmation" type="password" placeholder="Enter your password again" required autocomplete="new-password" />
    <label class="checkbox-row"><input type="checkbox" name="terms" required><span>I agree to the <a href="#terms">Terms of Service</a>.</span></label>
    <button class="btn btn-primary btn-wide" type="submit">Create account <span>→</span></button>
</form>
<p class="auth-switch">Already have an account? <a href="{{ route('login') }}">Login</a></p>
@endsection
