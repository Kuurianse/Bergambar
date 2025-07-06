@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="form-page-wrapper">
    <div class="form-card">
        <div class="form-card-header">
            <h2>{{ __('Edit Your Artist Profile') }}</h2>
        </div>

        <div class="form-card-body">
            <form method="POST" action="{{ route('artists.update', $artist->id) }}">
                @csrf
                @method('PUT') {{-- Important for RESTful update --}}

                <div class="form-group">
                    <label for="username">{{ __('Username') }}</label>
                    <input id="username" type="text" class="form-input form-input-disabled" value="{{ $artist->user->username ?? old('username', $artist->user->username) }}" disabled readonly>
                    <small class="form-text-hint">{{ __('Username cannot be changed here. Edit via your main user profile.') }}</small>
                </div>

                <div class="form-group">
                    <label for="name">{{ __('Full Name') }}</label>
                    <input id="name" type="text" class="form-input form-input-disabled" value="{{ $artist->user->name ?? old('name', $artist->user->name) }}" disabled readonly>
                    <small class="form-text-hint">{{ __('Full name cannot be changed here. Edit via your main user profile.') }}</small>
                </div>
                
                <div class="form-group">
                    <label for="portfolio_link">{{ __('Portfolio Link') }}</label>
                    <input id="portfolio_link" type="url" class="form-input @error('portfolio_link') is-invalid @enderror" name="portfolio_link" value="{{ old('portfolio_link', $artist->portfolio_link) }}" autofocus>
                    @error('portfolio_link')
                        <span class="form-error-message" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Other artist-specific fields like 'is_verified' or 'rating' are typically managed by admins --}}

                <div class="form-actions">
                    <button type="submit" class="btn-primary-custom">
                        {{ __('Update Artist Profile') }}
                    </button>
                    <a href="{{ route('artists.show', $artist->id) }}" class="btn-link-custom">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection