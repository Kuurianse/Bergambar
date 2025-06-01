@extends('layouts.app')

@section('content')
    <div class="container my-5">
        @if($artist && $artist->user)
            <div class="row">
                <div class="col-md-4 text-center">
                    @if($artist->user->profile_picture)
                        <img src="{{ asset('storage/' . $artist->user->profile_picture) }}" alt="{{ $artist->user->username }}'s Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/default-avatar.png') }}" alt="Default Avatar" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;"> {{-- Placeholder for default avatar --}}
                    @endif
                    <h3>{{ $artist->user->username ?? $artist->user->name }}</h3>
                    <p class="text-muted">{{ $artist->user->name }}</p>
                    @if($artist->portfolio_link)
                        <p><a href="{{ $artist->portfolio_link }}" target="_blank" class="btn btn-outline-primary btn-sm">View Portfolio</a></p>
                    @endif
                     @auth
                        @if(Auth::id() == $artist->user_id)
                            <a href="{{ route('artists.edit', $artist->id) }}" class="btn btn-secondary btn-sm mb-2">Edit Artist Profile</a>
                        @else
                            <a href="{{ route('chat.show', $artist->user->id) }}" class="btn btn-success btn-sm mb-2">Contact Artist</a>
                        @endif
                    @endauth
                </div>
                <div class="col-md-8">
                    <h4>About {{ $artist->user->username }}</h4>
                    <p>{{ $artist->user->bio ?? 'No bio provided.' }}</p>
                    <p><strong>Email:</strong> {{ $artist->user->email }}</p>
                    @if($artist->is_verified)
                        <p><span class="badge bg-success">Verified Artist</span></p>
                    @endif
                    @if(!is_null($artist->rating))
                        <p><strong>Rating:</strong> {{ number_format($artist->rating, 1) }}/5.0</p>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            @if($artist->services && $artist->services->count() > 0)
            <h3 class="mt-4">Services Offered</h3>
            <div class="row">
                @foreach($artist->services as $service)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $service->title }}</h5>
                                <p class="card-text">{{ Str::limit($service->description, 100) }}</p>
                                <p class="card-text"><strong>Price:</strong> Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                                <p class="card-text"><small class="text-muted">Type: {{ ucfirst($service->service_type) }}</small></p>
                                {{-- <a href="#" class="btn btn-primary">View Service</a> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr class="my-4">
            @endif
            

            <h3 class="mt-4">Commissions by {{ $artist->user->username }}</h3>
            @if($artist->user->commissions && $artist->user->commissions->count() > 0)
                <div class="row">
                    @foreach($artist->user->commissions as $commission)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                @if($commission->image)
                                <a href="{{ route('commissions.show', $commission->id) }}">
                                    <img src="{{ asset('storage/' . $commission->image) }}" alt="{{ $commission->description }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                </a>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ route('commissions.show', $commission->id) }}">{{ Str::limit($commission->description, 50) }}</a>
                                    </h5>
                                    <p class="card-text"><small class="text-muted">Price: Rp{{ number_format($commission->total_price, 0, ',', '.') }}</small></p>
                                    <p class="card-text"><small class="text-muted">Status: {{ ucfirst($commission->status) }}</small></p>
                                    <p class="card-text"><small class="text-muted">Loves: {{ $commission->loved_count ?? 0 }}</small></p>
                                </div>
                                <div class="card-footer">
                                     <a href="{{ route('commissions.show', $commission->id) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>This artist has not posted any commissions yet.</p>
            @endif

        @else
            <p>Artist profile not found.</p>
        @endif
    </div>
@endsection
