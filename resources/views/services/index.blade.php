@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Services</h2>
        @if(Auth::user()->artist)
            <a href="{{ route('services.create') }}" class="btn btn-primary">Add New Service</a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(!$artist && Auth::user()->artist === null)
        <div class="alert alert-warning">
            You need to <a href="{{ route('artists.create') }}">create an artist profile</a> before you can add or manage services.
        </div>
    @elseif($services->isEmpty())
        <p>You have not added any services yet.</p>
        @if(Auth::user()->artist)
            <p><a href="{{ route('services.create') }}">Add your first service now!</a></p>
        @endif
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td><a href="{{ route('services.show', $service->id) }}">{{ $service->title }}</a></td>
                            <td>{{ $service->category->name ?? 'N/A' }}</td>
                            <td>Rp{{ number_format($service->price, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($service->service_type) }}</td>
                            <td>
                                @if($service->availability_status)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-secondary">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection