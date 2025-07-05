@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="form-page-wrapper">
    <div class="form-card">
        <div class="form-card-header">
            <h2>{{ __('Add New Service') }}</h2>
        </div>

        <div class="form-card-body">
            <form method="POST" action="{{ route('services.store') }}">
                @csrf

                <div class="form-group">
                    <label for="title">{{ __('Service Title') }}</label>
                    <input id="title" type="text" class="form-input @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>
                    @error('title')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea id="description" class="form-input @error('description') is-invalid @enderror" name="description" rows="5" required>{{ old('description') }}</textarea>
                    @error('description')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">{{ __('Price (Rp)') }}</label>
                    <input id="price" type="number" step="0.01" class="form-input @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required>
                    @error('price')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="service_type">{{ __('Service Type') }}</label>
                    <select id="service_type" class="form-input form-select-custom @error('service_type') is-invalid @enderror" name="service_type" required>
                        <option value="illustration" {{ old('service_type') == 'illustration' ? 'selected' : '' }}>Illustration</option>
                        <option value="music" {{ old('service_type') == 'music' ? 'selected' : '' }}>Music</option>
                        <option value="rigging" {{ old('service_type') == 'rigging' ? 'selected' : '' }}>Rigging</option>
                        <option value="other" {{ old('service_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('service_type')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id">{{ __('Category (Optional)') }}</label>
                    <select id="category_id" class="form-input form-select-custom @error('category_id') is-invalid @enderror" name="category_id">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group form-check-group">
                    <input class="form-checkbox" type="checkbox" name="availability_status" id="availability_status" value="1" {{ old('availability_status', true) ? 'checked' : '' }}>
                    <label class="form-checkbox-label" for="availability_status">
                        {{ __('Available for Commissions') }}
                    </label>
                    @error('availability_status')
                        <span class="form-error-message d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary-custom">
                        {{ __('Create Service') }}
                    </button>
                    <a href="{{ route('services.index') }}" class="btn-link-custom">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection