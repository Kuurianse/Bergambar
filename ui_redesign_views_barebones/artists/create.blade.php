{{-- Original Path: resources/views/artists/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div>
    <div>
        <div>
            <div>
                <div>{{ __('Create Your Artist Profile') }}</div>

                <div>
                    <form method="POST" action="{{ route('artists.store') }}">
                        @csrf

                        <div>
                            <label for="portfolio_link">{{ __('Portfolio Link (Optional)') }}</label>

                            <div>
                                <input id="portfolio_link" type="url" name="portfolio_link" value="{{ old('portfolio_link') }}" autofocus>

                                @error('portfolio_link')
                                    <span>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div>
                                <button type="submit">
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