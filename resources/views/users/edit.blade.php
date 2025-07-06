@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="form-page-wrapper">
    <div class="form-card">
        <div class="form-card-header">
            <h2>{{ __('Edit Profil') }}</h2>
        </div>

        <div class="form-card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <div class="form-group">
                    <label for="username">{{ __('Username') }}</label>
                    <input id="username" type="text" class="form-input @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">{{ __('Name') }}</label>
                    <input id="name" type="text" class="form-input @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">{{ __('Email') }}</label>
                    <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __('Password (Biarkan kosong jika tidak ingin mengubah)') }}</label>
                    <input id="password" type="password" class="form-input @error('password') is-invalid @enderror" name="password">
                    @error('password')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bio">{{ __('Bio') }}</label>
                    <textarea id="bio" class="form-input @error('bio') is-invalid @enderror" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="profile_picture">{{ __('Profile Picture') }}</label>
                    <input type="file" name="profile_picture" id="profile_picture" class="form-input form-file-input @error('profile_picture') is-invalid @enderror">
                    @error('profile_picture')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror

                    @if($user->profile_picture)
                        <div class="current-image-preview profile-picture-edit-preview">
                            <p>{{ __('Current Profile Picture') }}:</p>
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-preview-image">
                        </div>
                    @endif
                </div>

                <div class="form-actions form-actions-single-button">
                    <button type="submit" class="btn-primary-custom">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection