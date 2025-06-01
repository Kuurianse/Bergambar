@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Your Artist Profile') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('artists.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="portfolio_link" class="col-md-4 col-form-label text-md-end">{{ __('Portfolio Link (Optional)') }}</label>

                            <div class="col-md-6">
                                <input id="portfolio_link" type="url" class="form-control @error('portfolio_link') is-invalid @enderror" name="portfolio_link" value="{{ old('portfolio_link') }}" autofocus>

                                @error('portfolio_link')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Add other artist-specific fields here if needed in the future --}}
                        {{-- For example, a short bio specific to their artist persona, preferred styles, etc. --}}
                        {{-- is_verified and rating are not typically set by the user --}}

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create Artist Profile') }}
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