@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="form-page-wrapper">
    <div class="form-card">
        <div class="form-card-header">
            <h2>{{ __('Create New Commission') }}</h2>
        </div>

        <div class="form-card-body">
            <form action="{{ route('commissions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Tambahkan form-group untuk Title di sini --}}
                <div class="form-group">
                    <label for="title">{{ __('Title') }}</label>
                    <input id="title" type="text" class="form-input @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>
                    @error('title')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea id="description" class="form-input @error('description') is-invalid @enderror" name="description" rows="3" required>{{ old('description') }}</textarea>
                    @error('description')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="total_price">{{ __('Total Price') }}</label>
                    <input id="total_price" type="number" class="form-input @error('total_price') is-invalid @enderror" name="total_price" value="{{ old('total_price') }}" required>
                    @error('total_price')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                
                {{-- Link to Existing Service (Optional) --}}
                {{-- <div class="form-group">
                    <label for="service_id">{{ __('Link to Existing Service (Optional)') }}</label>
                    <select class="form-input form-select-custom @error('service_id') is-invalid @enderror" name="service_id" id="service_id">
                        <option value="">-- {{ __('Select a Service') }} --</option>
                        @if($services && count($services) > 0)
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
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
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="accepted" {{ old('status') == 'accepted' ? 'selected' : '' }}>{{ __('Accepted') }}</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    </select>
                    @error('status')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div> --}}

                <div class="form-group">
                    <label for="image">{{ __('Upload Image') }}</label>
                    <input type="file" class="form-input form-file-input @error('image') is-invalid @enderror" name="image" id="image" onchange="previewImage(event)">
                    @error('image')
                        <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    <div class="mt-2">
                        <img id="image-preview" src="#" alt="Image Preview" style="display:none; max-width: 200px; margin-top: 10px;"/>
                    </div>
                </div>

                <div class="form-actions form-actions-single-button">
                    <button type="submit" class="btn-primary-custom">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('image-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush