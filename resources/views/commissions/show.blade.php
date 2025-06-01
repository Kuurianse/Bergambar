@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if($commission)
                <div class="card">
                    <div class="card-header">
                        <h3>Commission Details: {{ Str::limit($commission->description, 50) }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                @if($commission->image)
                                    <img src="{{ asset('storage/' . $commission->image) }}" alt="Commission Image for {{ $commission->description }}" class="img-fluid rounded" style="max-height: 400px; width: 100%; object-fit: contain;">
                                @else
                                    <p>No image provided.</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h4>{{ $commission->description }}</h4>
                                <p>
                                    <strong>Artist:</strong> 
                                    <a href="{{ route('artists.show', $commission->user->artist->id ?? $commission->user_id) }}"> {{-- Adjust if artist profile link differs --}}
                                        {{ $commission->user->username ?? $commission->user->name ?? 'N/A' }}
                                    </a>
                                </p>
                                <p><strong>Status:</strong> <span class="badge bg-{{ $commission->status == 'completed' ? 'success' : ($commission->status == 'accepted' ? 'info' : 'warning') }}">{{ ucfirst($commission->status) }}</span></p>
                                <p><strong>Price:</strong> Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>
                                <p><strong>Created:</strong> {{ $commission->created_at->format('F j, Y') }}</p>
                                <p><strong>Loves:</strong> {{ $commission->loved_count ?? 0 }}</p>

                                @auth
                                <div class="mt-3">
                                    {{-- Love Button --}}
                                    <form action="{{ route('commissions.toggleLove', $commission->id) }}" method="POST" style="display: inline-block;" id="loveForm-{{$commission->id}}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm love-button" data-commission-id="{{$commission->id}}">
                                            <i class="fa fa-heart"></i> <span id="loveCount-{{$commission->id}}">{{ $commission->loved_count ?? 0 }}</span>
                                        </button>
                                    </form>

                                    {{-- Order Button (if applicable, e.g., if status is 'accepted' or 'pending' and not owned by current user) --}}
                                    @if(Auth::id() !== $commission->user_id && ($commission->status == 'pending' || $commission->status == 'accepted'))
                                        <a href="{{ route('commissions.order', $commission->id) }}" class="btn btn-primary btn-sm ms-2">Order This Commission</a>
                                    @endif
                                     @if(Auth::id() == $commission->user_id)
                                        <a href="{{ route('commissions.edit', $commission->id) }}" class="btn btn-warning btn-sm ms-2">Edit Commission</a>
                                    @endif
                                </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h4>Reviews</h4>
                    @if($commission->reviews && $commission->reviews->count() > 0)
                        @foreach($commission->reviews as $review)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p class="card-text">{{ $review->review }}</p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            By: {{ $review->user->username ?? $review->user->name ?? 'Anonymous' }} 
                                            on {{ $review->created_at->format('M d, Y') }}
                                        </small>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No reviews yet for this commission.</p>
                    @endif
                </div>

                @auth
                <div class="mt-4">
                    <h5>Leave a Review</h5>
                    <form action="{{ route('commissions.addReview', $commission->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="review" class="form-control @error('review') is-invalid @enderror" rows="3" placeholder="Write your review..." required>{{ old('review') }}</textarea>
                            @error('review')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Submit Review</button>
                    </form>
                </div>
                @endauth

            @else
                <p>Commission not found.</p>
            @endif
        </div>
    </div>
</div>

{{-- Include Font Awesome if not already globally included --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}

{{-- Basic AJAX for love button (optional, for better UX) --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.love-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            let form = this.closest('form');
            let commissionId = this.dataset.commissionId;
            let loveCountSpan = document.getElementById('loveCount-' + commissionId);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (loveCountSpan) {
                    loveCountSpan.textContent = data.loved_count;
                }
                // Optionally change button style (e.g., filled heart)
                if(data.loved) {
                    this.classList.remove('btn-outline-danger');
                    this.classList.add('btn-danger');
                } else {
                    this.classList.remove('btn-danger');
                    this.classList.add('btn-outline-danger');
                }
            })
            .catch(error => console.error('Error toggling love:', error));
        });
    });
});
</script>
@endpush

@endsection