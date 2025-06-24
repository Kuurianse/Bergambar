@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="row">
            <!-- Column for Commission Image -->
            <div class="col-md-5 text-center mb-4 mb-md-0">
                @if($order->commission && $order->commission->image)
                    <img src="{{ asset($order->commission->image) }}" alt="{{ $order->commission->title }}" class="img-fluid rounded" style="max-width: 100%; height: auto;">
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

                {{-- Client Actions for Submitted Work --}}
                @if($order->commission && $order->commission->status == 'submitted_for_client_review' && Auth::id() == $order->user_id)
                    <div class="mt-4 p-3 border rounded bg-light">
                        <h4>Tindakan Klien: Hasil Karya Telah Dikirim</h4>
                        @if($order->delivery_link)
                            <p>Seniman telah mengirimkan hasil karya. Anda dapat melihatnya melalui link berikut:</p>
                            <p><a href="{{ $order->delivery_link }}" target="_blank" rel="noopener noreferrer">{{ $order->delivery_link }}</a></p>
                            <hr>
                        @else
                            <p class="text-warning">Seniman menandai karya telah dikirim, namun link hasil karya belum tersedia.</p>
                        @endif

                        {{-- Form Setujui Hasil Karya --}}
                        <form action="{{ route('orders.approveDelivery', $order->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success">Setujui & Selesaikan Pesanan</button>
                        </form>

                        {{-- Form Minta Revisi --}}
                        <form action="{{ route('orders.requestRevision', $order->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="revision_notes" class="form-label">Catatan Revisi (Jika ada yang perlu diperbaiki):</label>
                                <textarea name="revision_notes" id="revision_notes" class="form-control @error('revision_notes') is-invalid @enderror" rows="3" placeholder="Jelaskan bagian mana yang perlu direvisi...">{{ old('revision_notes') }}</textarea>
                                @error('revision_notes')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-warning">Minta Revisi</button>
                        </form>
                    </div>
                @elseif($order->commission && $order->commission->status == 'needs_revision' && Auth::id() == $order->user_id)
                    <div class="alert alert-warning mt-3">
                        <p>Anda telah meminta revisi untuk komisi ini. Mohon tunggu kabar dari seniman.</p>
                    </div>
                @elseif($order->commission && $order->commission->status == 'completed')
                     <div class="alert alert-success mt-3">
                        <p>Komisi ini telah selesai.</p>
                    </div>
                @endif
                {{-- End Client Actions --}}
            </div>
        </div>

        <!-- Revision History Section -->
        @if($order->revisions && $order->revisions->count() > 0)
        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <h4>Histori Permintaan Revisi</h4>
                <ul class="list-group">
                    @foreach($order->revisions as $revision)
                        <li class="list-group-item">
                            <p class="mb-1"><strong>Diminta pada:</strong> {{ $revision->requested_at->translatedFormat('d M Y, H:i') }}</p>
                            <p class="mb-0"><strong>Catatan:</strong> {{ $revision->notes }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        <!-- End Revision History Section -->

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
