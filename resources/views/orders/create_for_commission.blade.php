@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-header">
            <h2>{{ __('Order Commission') }}: {{ $commission->title }}</h2>
        </div>

        <div class="commission-order-grid">
            <div class="commission-image-area">
                @if($commission->image)
                    {{-- <img src="{{ asset('storage/' . $commission->image) }}" alt="Commission Image for {{ $commission->description }}" class="commission-display-image"> --}}
                    <img src="{{ asset($commission->image) }}" alt="Commission Image for {{ $commission->description }}" class="commission-display-image" style="width: 100%; height: 100%; object-fit: cover;">
                    {{-- <img src="{{ asset($commission->image) }}" alt="Commission Image" style="width: 100%; height: 100%; object-fit: cover;"> --}}
                @else
                    <div class="no-image-placeholder">
                        <p>{{ __('No image provided for this commission.') }}</p>
                    </div>
                @endif
            </div>

            <div class="commission-order-info-area">
                <div class="card-section">
                    <h3>{{ $commission->description }}</h3>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Artist') }}:</strong></p>
                        <p class="detail-value">
                            <a href="{{ route('artists.show', $artist->id) }}" class="table-link">
                                {{ $artist->username ?? $artist->name ?? 'Unknown Artist' }}
                            </a>
                        </p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Description') }}:</strong></p>
                        <p class="detail-value">{{ $commission->description ?? __('No description available') }}</p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Price') }}:</strong></p>
                        <p class="detail-value">Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="commission-order-actions-area mt-4">
                    @auth
                        <a href="{{ route('chat.show', $artist->id) }}" class="btn-link-custom full-width-btn mt-2 btn-stroke-e3e3e3">
                            {{ __('Contact Artist') }}
                        </a>
                        <button type="button" class="btn-primary-custom full-width-btn" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            {{ __('Proceed to Order') }}
                        </button>
                        
                    @else
                        <div class="alert-message info">
                            <p>{{ __('Login') }} or {{ __('Register') }} to order this commission.</p>
                            <p class="mt-2">
                                <a href="{{ route('login') }}" class="btn-link-custom mr-1 btn-putih-white">{{ __('Login') }}</a>
                                <a href="{{ route('register') }}" class="btn-primary-custom">{{ __('Register') }}</a>
                            </p>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    @auth
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-custom-width"> {{-- Added custom class --}}
            <div class="modal-content modal-content-custom"> {{-- Added custom class --}}
                <div class="modal-header modal-header-custom"> {{-- Added custom class --}}
                    <h5 class="modal-title" id="paymentModalLabel">{{ __('Confirm Order & Payment') }}</h5>
                    <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-custom"> {{-- Added custom class --}}
                    <p>{{ __('You are about to order the commission') }}: <strong>{{ $commission->description }}</strong></p>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Artist') }}:</strong></p>
                        <p class="detail-value">{{ $artist->username ?? $artist->name ?? 'Unknown Artist' }}</p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Price') }}:</strong></p>
                        <p class="detail-value">Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Buyer') }}:</strong></p>
                        <p class="detail-value">{{ Auth::user()->username ?? Auth::user()->name }}</p>
                    </div>

                    <div class="payment-qr-section">
                        <img src="{{asset('assets/qris-image.png')}}" alt="QRIS QR Code" class="payment-qr-code">
                        <p>{{ __('Scan the QR code to simulate payment.') }}</p>
                        <small class="text-muted">{{ __('(This is a simulated payment for demonstration purposes)') }}</small>
                    </div>

                    <form id="payment-form" action="{{ route('orders.confirmPayment', $commission->id) }}" method="POST">
                        @csrf
                        <button type="button" id="confirm-payment-btn" class="btn-primary-custom full-width-btn">{{ __('Confirm Payment & Place Order') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endauth

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmButton = document.getElementById('confirm-payment-btn');
        const paymentForm = document.getElementById('payment-form');

        if (confirmButton && paymentForm) {
            confirmButton.addEventListener('click', function() {
                paymentForm.submit();
            });
        }
    });
</script>
@endpush
@endsection