@extends('layouts.app')

@section('content')
<!-- Assuming a consistent background color is handled by layouts.app or a global CSS -->
<div class="container" style="margin-top: 5%;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card"> <!-- Removed inline style, assuming card styling is global -->
                <div class="card-header">{{ __('Login') }}</div> <!-- Removed inline style -->

                <div class="card-body"> <!-- Removed inline style -->
                    <form method="POST" action="#"> <!-- Action set to # for barebones -->
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="user@example.com" required autocomplete="email" autofocus placeholder="Enter your email">
                                <!-- Placeholder for email error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>Email is required and must be a valid email address.</strong>
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="Enter your password" value="password">
                                <!-- Placeholder for password error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>Password is required.</strong>
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                             <div class="col-md-8 offset-md-4">
                                <!-- Placeholder for general login error -->
                                <div class="alert alert-danger" role="alert" style="display: none;">
                                    Invalid login credentials. Please try again.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                <a class="btn btn-link" href="#"> <!-- Href set to # -->
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-8 offset-md-4">
                                <a class="btn btn-link" href="#"> <!-- Href set to # -->
                                    {{ __('Don\'t have an account? Register here') }}
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