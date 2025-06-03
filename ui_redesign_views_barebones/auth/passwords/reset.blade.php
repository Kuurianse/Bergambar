@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 5%;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="#"> <!-- Action set to # -->
                        @csrf

                        <input type="hidden" name="token" value="dummy_token_placeholder"> <!-- Placeholder token -->

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="user@example.com" required autocomplete="email" autofocus placeholder="Your email address">
                                <!-- Placeholder for email error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>Please enter a valid email address.</strong>
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password" placeholder="Enter your new password" value="newpassword123">
                                <!-- Placeholder for password error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>Password must be at least 8 characters.</strong>
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm New Password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your new password" value="newpassword123">
                                <!-- Placeholder for password confirmation error -->
                                <span class="invalid-feedback" role="alert" style="display: none;">
                                    <strong>Passwords do not match.</strong>
                                </span>
                            </div>
                        </div>

                        <!-- Placeholder for general reset error -->
                        <div class="row mb-3">
                             <div class="col-md-6 offset-md-4">
                                <div class="alert alert-danger" role="alert" style="display: none;">
                                    Could not reset password. Please check your input or token.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection