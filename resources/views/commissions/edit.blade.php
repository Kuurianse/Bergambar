@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="form-page-wrapper">
    <div class="form-card">
        <div class="form-card-header">
            <h2>{{ __('Edit Commission') }}</h2>
        </div>

        <div class="form-card-body">
            <form action="{{ route('commissions.update', $commission->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <div class="form-group">
                    <label for="title">{{ __('Title') }}</label>
                    <input id="title" type="text" class="form-input @error('title') is-invalid @enderror" name="title" value="{{ old('title', $commission->title) }}" required>
                    @error('title')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea id="description" class="form-input @error('description') is-invalid @enderror" name="description" rows="3" required>{{ old('description', $commission->description) }}</textarea>
                    @error('description')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="total_price">{{ __('Total Price') }}</label>
                    <input id="total_price" type="number" class="form-input @error('total_price') is-invalid @enderror" name="total_price" value="{{ old('total_price', $commission->total_price) }}" required>
                    @error('total_price') {{-- Add error handling for total_price --}}
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                
                {{-- <div class="form-group">
                    <label for="service_id">{{ __('Link to Existing Service (Optional)') }}</label>
                    <select class="form-input form-select-custom @error('service_id') is-invalid @enderror" name="service_id" id="service_id">
                        <option value="">-- {{ __('Select a Service') }} --</option>
                        @if($services && count($services) > 0)
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $commission->service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->title }} (Rp{{ number_format($service->price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>{{ __('You have no available services to link.') }}</option>
                        @endif
                    </select>
                    @error('service_id')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="status">{{ __('Status') }}</label>
                    <select class="form-input form-select-custom @error('status') is-invalid @enderror" name="status" id="status" required>
                        <option value="pending" {{ old('status', $commission->status) == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="accepted" {{ old('status', $commission->status) == 'accepted' ? 'selected' : '' }}>{{ __('Accepted') }}</option>
                        <option value="completed" {{ old('status', $commission->status) == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    </select>
                    @error('status')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div> --}}

                <div class="form-group">
                    <label for="image">{{ __('Upload New Image (optional)') }}</label>
                    <input type="file" class="form-input form-file-input @error('image') is-invalid @enderror" name="image" id="image">
                    @error('image')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    @if($commission->image)
                        <div class="current-image-preview">
                            <p>{{ __('Current Image') }}:</p>
                            <img src="{{ asset('storage/' . $commission->image) }}" alt="{{ __('Current Commission Image') }}" class="commission-preview-image">
                        </div>
                    @endif
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary-custom">{{ __('Update Commission') }}</button>
                    <a href="{{ url()->previous() }}" class="btn-link-custom">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection