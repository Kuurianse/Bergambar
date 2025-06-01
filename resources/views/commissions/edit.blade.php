@extends('layouts.app')

@section('content')
    <h1>Edit Commission</h1>

    <!-- Form untuk mengedit commission -->
    <form action="{{ route('commissions.update', $commission->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Laravel membutuhkan method PUT/PATCH untuk update -->

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" name="description" id="description" value="{{ old('description', $commission->description) }}" required>
        </div>

        <!-- Total Price -->
        <div class="form-group">
            <label for="total_price">Total Price</label>
            <input type="number" class="form-control" name="total_price" id="total_price" value="{{ old('total_price', $commission->total_price) }}" required>
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
