@extends('layouts.app')

@section('content')
    <h1>Edit Commission</h1>

    <!-- Form untuk mengedit commission -->
    <form action="{{ route('commissions.update', $commission->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Laravel membutuhkan method PUT/PATCH untuk update -->

        <!-- Title -->
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" value="{{ old('title', $commission->title) }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="3" required>{{ old('description', $commission->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Total Price -->
        <div class="form-group">
            <label for="total_price">Total Price</label>
            <input type="number" class="form-control" name="total_price" id="total_price" value="{{ old('total_price', $commission->total_price) }}" required>
        </div>

        <!-- Link to Existing Service (Optional) -->
        <div class="form-group">
            <label for="service_id">Link to Existing Service (Optional)</label>
            <select class="form-control @error('service_id') is-invalid @enderror" name="service_id" id="service_id">
                <option value="">-- Select a Service --</option>
                @if($services && count($services) > 0)
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id', $commission->service_id) == $service->id ? 'selected' : '' }}>
                            {{ $service->title }} (Rp{{ number_format($service->price, 0, ',', '.') }})
                        </option>
                    @endforeach
                @else
                    <option value="" disabled>You have no available services to link.</option>
                @endif
            </select>
            @error('service_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
 
        <!-- Status -->
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" id="status" required>
                <option value="pending" {{ $commission->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="accepted" {{ $commission->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="completed" {{ $commission->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <!-- Image -->
        <div class="form-group">
            <label for="image">Upload New Image (optional)</label>
            <input type="file" class="form-control" name="image" id="image">
            @if($commission->image)
                <p>Current Image:</p>
                <img src="{{ asset('storage/' . $commission->image) }}" alt="Current Commission Image" style="width: 200px;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Commission</button>
    </form>
@endsection
