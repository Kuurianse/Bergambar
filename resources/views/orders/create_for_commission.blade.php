@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10"> {{-- Adjusted width for better layout --}}
            <div class="card">
                <div class="card-header">
                    <h3>Order Commission: {{ $commission->description }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Column for Image -->
                        <div class="col-md-5 text-center mb-4 mb-md-0">
                            @if($commission->image)
                                <img src="{{ asset('storage/' . $commission->image) }}" alt="Commission Image for {{ $commission->description }}" class="img-fluid rounded" style="max-width: 100%; height: auto; max-height: 400px; object-fit: contain;">
                            @else
                                <p>No image provided for this commission.</p>
                            @endif
                        </div>

                        <!-- Column for Details and Description -->
                        <div class="col-md-7">
                            <h4>{{ $commission->description }}</h4>
                            <p><strong>Artist:</strong> {{ $artist->username ?? $artist->name ?? 'Unknown Artist' }}</p>
                            <p><strong>Description:</strong> {{ $commission->description ?? 'No description available' }}</p>
                            <p><strong>Price:</strong> Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>

                            <!-- Order and Chat Buttons -->
                            <div class="order-buttons mt-3">
                                @auth
                                    <!-- Order Now Button triggers the modal -->
                                    <button type="button" class="btn btn-primary me-2 mb-2" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                        Proceed to Order
                                    </button>
                                    <!-- Contact Artist Button (direct link) -->
                                    <a href="{{ route('chat.show', $artist->id) }}" class="btn btn-success mb-2">Contact Artist</a>
                                @else
                                    <p><a href="{{ route('login') }}">Login</a> or <a href="{{ route('register') }}">Register</a> to order this commission.</p>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reviews section can be added here if desired, similar to commission show page --}}
            {{-- For now, keeping it focused on initiating the order --}}
        </div>
    </div>
</div>

@auth
<!-- Modal for Payment -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Confirm Order & Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are about to order the commission: <strong>{{ $commission->description }}</strong></p>
                <p><strong>Artist:</strong> {{ $artist->username ?? $artist->name ?? 'Unknown Artist' }}</p>
                <p><strong>Price:</strong> Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>
                <p><strong>Buyer:</strong> {{ Auth::user()->username ?? Auth::user()->name }}</p>

                <!-- QR Code for Payment -->
                <div class="text-center my-4">
                    <img src="{{asset('assets/qris-image.png')}}" alt="QRIS QR Code" class="img-fluid" style="max-width: 200px;">
                    <p>Scan the QR code to simulate payment.</p>
                    <small class="text-muted">(This is a simulated payment for demonstration purposes)</small>
                </div>

                <!-- Payment Confirmation Button -->
                <form action="{{ route('orders.confirmPayment', $commission->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">Confirm Payment & Place Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth

<!-- Ensure Bootstrap JS is loaded (usually in layouts.app) -->
@endsection