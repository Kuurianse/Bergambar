@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="row">
            <!-- Column for Commission Image -->
            <div class="col-md-5 text-center mb-4 mb-md-0">
                @if($order->commission && $order->commission->image)
                    <img src="{{ asset('storage/' . $order->commission->image) }}" alt="{{ $order->commission->title }}" class="img-fluid rounded" style="max-width: 100%; height: auto;">
                @else
                    <p>No image available</p>
                @endif
            </div>

            <!-- Column for Order and Commission Details -->
            <div class="col-md-7">
                <h3>Order #{{ $order->id }} Details</h3>
                <p><strong>Status:</strong> <span class="badge bg-{{ $order->status == 'paid' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($order->status) }}</span></p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y, g:i a') }}</p>
                <p><strong>Buyer:</strong> {{ $order->user->username ?? $order->user->name ?? 'N/A' }}</p>
                <hr>
                <h4>Commission Details</h4>
                @if($order->commission)
                    <h5>{{ $order->commission->title }}</h5>
                    <p><strong>Artist:</strong> {{ $order->commission->user->username ?? $order->commission->user->name ?? 'Unknown Artist' }}</p>
                    <p><strong>Description:</strong> {{ $order->commission->description ?? 'No description available' }}</p>
                    <p><strong>Price Paid:</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                     <a href="{{ route('chat.show', $order->commission->user->id) }}" class="btn btn-success mb-2 mt-2">Contact Artist</a>
                @else
                    <p>Commission details not available.</p>
                @endif

                {{-- Payment Modal and Order Now button removed as this is an order detail page --}}
                {{-- Add other relevant order actions here if needed, e.g., view invoice, track shipment (future) --}}
            </div>
        </div>

        <!-- Comments Section -->
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h4>Comments</h4>
                    @if($order->commission && $order->commission->reviews->isNotEmpty())
                        @foreach($order->commission->reviews as $review)
                            <div class="review mb-4">
                                <strong>{{ $review->user->username ?? $review->user->name ?? 'Unknown' }}</strong>
                                {{-- Add rating display if available, e.g., $review->rating --}}
                                <p>{{ $review->review }}</p>
                            </div>
                        @endforeach
                    @else
                        <p>No Comments yet for this commission.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add Review Form -->
        @auth
            <form action="{{ route('commissions.addReview', $order->commission->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="review" class="form-label">Your Comment</label>
                    <textarea name="review" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary d-flex ms-auto me-0">Submit Comment</button>
            </form>
        @endauth
    </div>
</div>

{{-- Modal for Payment has been removed as this page now shows existing order details.
    Payment confirmation flow would typically happen before reaching this order detail page,
    or this page might show payment status / retry options if applicable. --}}

<!-- Bootstrap JavaScript (if not already included in your layout) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
