@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 5%;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <!-- Placeholder for session status message -->
                    <div class="alert alert-success" role="alert" style="display: block;"> <!-- Display block for barebones visibility -->
                        {{ __('We have emailed your password reset link!') }} <!-- Example status -->
                    </div>

                    <form method="POST" action="#"> <!-- Action set to # -->
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="user@example.com" required autocomplete="email" autofocus placeholder="Enter your registered email">
                                <!-- Placeholder for email error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>Please enter a valid email address.</strong>
                                </span>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6 offset-md-4">
                                <a class="btn btn-link" href="#"> <!-- Href to login page -->
                                    {{ __('Back to Login') }}
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