@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-black fw-semibold">Artist Profiles</h2>
    <div class="row">
        @if($artists->isEmpty())
            <p>No artist profiles available at the moment.</p>
        @else
            @foreach($artists as $artistProfile)
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        @if($artistProfile->user)
                            <a href="{{ route('artists.show', $artistProfile->id) }}">
                                <h5 class="card-title fw-bold fst-italic">
                                    {{ $artistProfile->user->username ?? $artistProfile->user->name }}
                                    @if($artistProfile->is_verified)
                                        <span class="badge bg-success ms-2">Verified</span>
                                    @endif
                                </h5>
                            </a>
                            <p><strong class="fw-bold fst-italic">Full Name:</strong> {{ $artistProfile->user->name }}</p>
                            @if($artistProfile->portfolio_link)
                                <p><strong class="fw-bold fst-italic">Portfolio:</strong> <a href="{{ $artistProfile->portfolio_link }}" target="_blank">{{ $artistProfile->portfolio_link }}</a></p>
                            @endif
                            <p><strong class="fw-bold fst-italic">Commissions:</strong> {{ $artistProfile->user->commissions_count ?? 0 }}</p>
                            <p><strong class="fw-bold fst-italic">Services:</strong> {{ $artistProfile->services_count ?? 0 }}</p>
                            @if(!is_null($artistProfile->rating) && $artistProfile->rating > 0)
                                <p><strong class="fw-bold fst-italic">Rating:</strong> {{ number_format($artistProfile->rating, 1) }} <i class="fas fa-star text-warning"></i></p>
                            @endif
                             <a href="{{ route('artists.show', $artistProfile->id) }}" class="btn btn-sm btn-primary">View Profile</a>
                        @else
                            <p>Artist user data not found.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
    <div class="d-flex justify-content-center">
        {{ $artists->links() }}
    </div>
</div>
@endsection
