<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
@extends('layouts.app')

@section('content')
<body style="background-color: #ffffff;">


    <div class="container d-flex justify-content-center align-items-center row-g-10">
        <div class="w-50">
            <h1 class="text-center text-black fs-1 p-4">Create New Commission</h1>

    <form action="{{ route('commissions.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="description" class="text-black fs-3 p-3">Description</label>
        <input type="text" class="form-control" name="description" id="description" required>
    </div>

    <div class="form-group">
        <label for="total_price" class="text-black fs-3 p-3">Total Price</label>
        <input type="number" class="form-control" name="total_price" id="total_price" required>
    </div>

    <div class="form-group">
        <label for="service_id" class="text-black fs-3 p-3">Link to Existing Service (Optional)</label>
        <select class="form-control @error('service_id') is-invalid @enderror" name="service_id" id="service_id">
            <option value="">-- Select a Service --</option>
            @if($services && count($services) > 0)
                @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
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

    <div class="form-group">
        <label for="status" class="text-black fs-3 p-3">Status</label>
        <select class="form-control" name="status" id="status" required>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    <div class="form-group">
        <label for="image" class="text-black fs-3 p-3">Upload Image</label>
        <input type="file" class="form-control" name="image" id="image">
    </div>
    <div class="container p-5 align-items-center position-relative ">
        <button type="submit" class="btn btn-primary fs-4 position-absolute top-50 start-50 translate-middle fluid ">Submit</button>
    </div>
</form>

        </div>
        </div>
    </div>
</body>
    <!-- Footer
    <footer class="position-absolute bottom-0 text-white py-3 w-100" style="background-color: #252539;">
        <div class="container text-center">
            <small>&copy; 2024 Bergambar. All Rights Reserved.</small>
        </div>
    </footer> -->
@endsection
