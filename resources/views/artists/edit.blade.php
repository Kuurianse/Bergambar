@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Your Artist Profile') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('artists.update', $artist->id) }}">
                        @csrf
                        @method('PUT') {{-- Important for RESTful update --}}

                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" value="{{ $artist->user->username ?? old('username', $artist->user->username) }}" disabled readonly>
                                <small class="form-text text-muted">Username cannot be changed here. Edit via your main user profile.</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Full Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" value="{{ $artist->user->name ?? old('name', $artist->user->name) }}" disabled readonly>
                                <small class="form-text text-muted">Full name cannot be changed here. Edit via your main user profile.</small>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="portfolio_link" class="col-md-4 col-form-label text-md-end">{{ __('Portfolio Link') }}</label>
                            <div class="col-md-6">
                                <input id="portfolio_link" type="url" class="form-control @error('portfolio_link') is-invalid @enderror" name="portfolio_link" value="{{ old('portfolio_link', $artist->portfolio_link) }}" autofocus>
                                @error('portfolio_link')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Other artist-specific fields like 'is_verified' or 'rating' are typically managed by admins --}}

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Artist Profile') }}
                                </button>
                                <a href="{{ route('artists.show', $artist->id) }}" class="btn btn-link">
                                    {{ __('Cancel') }}
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