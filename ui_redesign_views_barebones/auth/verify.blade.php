@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 5%;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    <!-- Placeholder for 'resent' session message -->
                    <div class="alert alert-success" role="alert" style="display: block;"> <!-- Display block for barebones visibility -->
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                    
                    <p>{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                    <p>{{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="#"> <!-- Action set to # -->
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                        </form>
                    </p>

                    <!-- Additional helpful information or links -->
                    <hr>
                    <p class="text-muted small">
                        Make sure to check your spam or junk folder if you don't see the email in your inbox.
                        If you continue to have issues, please contact support.
                    </p>
                    <a href="/" class="btn btn-outline-secondary btn-sm mt-2">Back to Homepage</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection