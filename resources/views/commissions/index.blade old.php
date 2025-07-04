@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>All Commissions</h2>
            @auth {{-- Assuming only authenticated users can create commissions --}}
                <a href="{{ route('commissions.create') }}" class="btn btn-primary">Create New Commission</a>
            @endauth
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        @if($commissions->isEmpty())
            <p>No commissions are currently available.</p>
        @else
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Artist</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commissions as $commission)
                    <tr>
                        <td>{{ $commission->id }}</td>
                        <td>
                            <a href="{{ route('commissions.show', $commission->id) }}">
                                {{ Str::limit($commission->description, 70) }}
                            </a>
                        </td>
                        <td>{{ $commission->user->username ?? $commission->user->name ?? 'N/A' }}</td>
                        <td>Rp{{ number_format($commission->total_price, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $badgeClass = 'bg-secondary'; // Default for undefined or other statuses
                                switch ($commission->public_status) {
                                    case 'Available':
                                        $badgeClass = 'bg-success';
                                        break;
                                    case 'Ordered':
                                        $badgeClass = 'bg-warning';
                                        break;
                                    case 'Completed':
                                        $badgeClass = 'bg-primary'; // Or 'bg-info'
                                        break;
                                    case 'Status Undefined':
                                        $badgeClass = 'bg-danger';
                                        break;
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $commission->public_status }}</span>
                        </td>
                    <td>
                        <a href="{{ route('commissions.show', $commission->id) }}" class="btn btn-sm btn-info">View</a>
                        @auth
                            @if(Auth::id() == $commission->user_id) {{-- Only owner can edit/delete --}}
                                <a href="{{ route('commissions.edit', $commission->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('commissions.destroy', $commission->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this commission?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endif
                        @endauth
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $commissions->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
