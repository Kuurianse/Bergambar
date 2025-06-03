{{-- Original Path: resources/views/artists/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div>
    <h2>Artist Profiles</h2>
    <div>
        @if($artists->isEmpty())
            <p>No artist profiles available at the moment.</p>
        @else
            @foreach($artists as $artistProfile)
            <div>
                <div>
                    <div>
                        @if($artistProfile->user)
                            <a href="{{ route('artists.show', $artistProfile->id) }}">
                                <h5>
                                    {{ $artistProfile->user->username ?? $artistProfile->user->name }}
                                    @if($artistProfile->is_verified)
                                        <span>Verified</span>
                                    @endif
                                </h5>
                            </a>
                            <p><strong>Full Name:</strong> {{ $artistProfile->user->name }}</p>
                            @if($artistProfile->portfolio_link)
                                <p><strong>Portfolio:</strong> <a href="{{ $artistProfile->portfolio_link }}" target="_blank">{{ $artistProfile->portfolio_link }}</a></p>
                            @endif
                            <p><strong>Commissions:</strong> {{ $artistProfile->user->commissions_count ?? 0 }}</p>
                            <p><strong>Services:</strong> {{ $artistProfile->services_count ?? 0 }}</p>
                            @if(!is_null($artistProfile->rating) && $artistProfile->rating > 0)
                                <p><strong>Rating:</strong> {{ number_format($artistProfile->rating, 1) }} <span>Star Icon</span></p>
                            @endif
                             <a href="{{ route('artists.show', $artistProfile->id) }}">View Profile</a>
                        @else
                            <p>Artist user data not found.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
    <div>
        {{ $artists->links() }}
    </div>
</div>
@endsection