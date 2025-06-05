@extends('layouts.app')

@section('content')
<!-- Assuming a consistent background color is handled by layouts.app or a global CSS -->
<div class="container" style="margin-top: 5%;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card"> <!-- Removed inline style -->
                <div class="card-header">{{ __('Register') }}</div> <!-- Removed inline style -->

                <div class="card-body"> <!-- Removed inline style -->
                    <form method="POST" action="#"> <!-- Action set to # for barebones -->
                        @csrf

                        <!-- Input Username -->
                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="newuser" required autocomplete="username" autofocus placeholder="Choose a username">
                                <!-- Placeholder for username error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>Username is required and may only contain letters, numbers, and underscores.</strong>
                                </span>
                            </div>
                        </div>

                        <!-- Input Name -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="New User" required autocomplete="name" placeholder="Enter your full name">
                                <!-- Placeholder for name error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>Name is required.</strong>
                                </span>
                            </div>
                        </div>

                        <!-- Input Email -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="newuser@example.com" required autocomplete="email" placeholder="Enter your email address">
                                <!-- Placeholder for email error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>A valid email address is required.</strong>
                                </span>
                            </div>
                        </div>

                        <!-- Input Password -->
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password" placeholder="Create a password" value="password123">
                                <!-- Placeholder for password error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>Password must be at least 8 characters long.</strong>
                                </span>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" value="password123">
                                <!-- Placeholder for password confirmation error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>Passwords do not match.</strong>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Placeholder for general registration error -->
                        <div class="row mb-3">
                             <div class="col-md-6 offset-md-4">
                                <div class="alert alert-danger" role="alert" style="display: none;">
                                    An error occurred during registration. Please check your input.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4 d-flex justify-content-start align-items-center">
                                <button type="submit" class="btn btn-primary me-3">
                                    {{ __('Register') }}
                                </button>
                                <a class="btn btn-link" href="#"> <!-- Href set to # -->
                                    {{ __('Have an account? Click Here!') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection