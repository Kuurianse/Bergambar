@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 5%;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Confirm Password') }}</div>

                <div class="card-body">
                    <p>{{ __('Please confirm your password before continuing.') }}</p>

                    <form method="POST" action="#"> <!-- Action set to # -->
                        @csrf

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

                        <!-- Placeholder for general confirmation error -->
                        <div class="row mb-3">
                             <div class="col-md-6 offset-md-4">
                                <div class="alert alert-danger" role="alert" style="display: none;">
                                    Incorrect password. Please try again.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirm Password') }}
                                </button>

                                <a class="btn btn-link" href="#"> <!-- Href set to # -->
                                    {{ __('Forgot Your Password?') }}
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