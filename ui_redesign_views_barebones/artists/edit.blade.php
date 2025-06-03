{{-- Original Path: resources/views/artists/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div>
    <div>
        <div>
            <div>
                <div>{{ __('Edit Your Artist Profile') }}</div>

                <div>
                    <form method="POST" action="{{ route('artists.update', $artist->id) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="username">{{ __('Username') }}</label>
                            <div>
                                <input id="username" type="text" value="{{ $artist->user->username ?? old('username', $artist->user->username) }}" disabled readonly>
                                <small>Username cannot be changed here. Edit via your main user profile.</small>
                            </div>
                        </div>

                        <div>
                            <label for="name">{{ __('Full Name') }}</label>
                            <div>
                                <input id="name" type="text" value="{{ $artist->user->name ?? old('name', $artist->user->name) }}" disabled readonly>
                                <small>Full name cannot be changed here. Edit via your main user profile.</small>
                            </div>
                        </div>
                        
                        <div>
                            <label for="portfolio_link">{{ __('Portfolio Link') }}</label>
                            <div>
                                <input id="portfolio_link" type="url" name="portfolio_link" value="{{ old('portfolio_link', $artist->portfolio_link) }}" autofocus>
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
                                    {{ __('Update Artist Profile') }}
                                </button>
                                <a href="{{ route('artists.show', $artist->id) }}">
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