@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add New Service') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('services.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Service Title') }}</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>
                                @error('title')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>
                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="5" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="price" class="col-md-4 col-form-label text-md-end">{{ __('Price (Rp)') }}</label>
                            <div class="col-md-6">
                                <input id="price" type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="service_type" class="col-md-4 col-form-label text-md-end">{{ __('Service Type') }}</label>
                            <div class="col-md-6">
                                <select id="service_type" class="form-select @error('service_type') is-invalid @enderror" name="service_type" required>
                                    <option value="illustration" {{ old('service_type') == 'illustration' ? 'selected' : '' }}>Illustration</option>
                                    <option value="music" {{ old('service_type') == 'music' ? 'selected' : '' }}>Music</option>
                                    <option value="rigging" {{ old('service_type') == 'rigging' ? 'selected' : '' }}>Rigging</option>
                                    <option value="other" {{ old('service_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('service_type')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="category_id" class="col-md-4 col-form-label text-md-end">{{ __('Category (Optional)') }}</label>
                            <div class="col-md-6">
                                <select id="category_id" class="form-select @error('category_id') is-invalid @enderror" name="category_id">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="availability_status" id="availability_status" value="1" {{ old('availability_status', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="availability_status">
                                        {{ __('Available for Commissions') }}
                                    </label>
                                </div>
                                @error('availability_status')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create Service') }}
                                </button>
                                <a href="{{ route('services.index') }}" class="btn btn-link">
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