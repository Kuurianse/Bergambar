@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <h2>{{ __('My Services') }}</h2>
        @if(Auth::user()->artist)
            <a href="{{ route('services.create') }}" class="btn-primary-custom">Add New Service</a>
        @endif
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="alert-message success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('info'))
        <div class="alert-message info">
            {{ session('info') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert-message danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Conditional Content --}}
    @if(!$artist && Auth::user()->artist === null)
        <div class="alert-message warning">
            You need to <a href="{{ route('artists.create') }}" class="alert-link">create an artist profile</a> before you can add or manage services.
        </div>
    @elseif($services->isEmpty())
        <div class="empty-state">
            <p>You have not added any services yet.</p>
            @if(Auth::user()->artist)
                <p><a href="{{ route('services.create') }}" class="btn-primary-custom">Add your first service now!</a></p>
            @endif
        </div>
    @else
        <div class="table-container">
            <table class="data-table">
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
                            <td><a href="{{ route('services.show', $service->id) }}" class="table-link">{{ $service->title }}</a></td>
                            <td>{{ $service->category->name ?? 'N/A' }}</td>
                            <td>Rp{{ number_format($service->price, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($service->service_type) }}</td>
                            <td>
                                @if($service->availability_status)
                                    <span class="status-badge success">Available</span>
                                @else
                                    <span class="status-badge secondary">Unavailable</span>
                                @endif
                            </td>
                            <td class="actions-cell">
                                <a href="{{ route('services.edit', $service->id) }}" class="btn-action edit">Edit</a>
                                <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection