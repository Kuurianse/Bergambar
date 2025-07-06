@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <h2>{{ __('Order Details') }} #{{ $order->id }}</h2>
    </div>

    {{-- Alert Messages --}}
    @if (session('message'))
        <div class="alert-message success">
            {{ session('message') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert-message danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="content-grid-half"> {{-- New grid for main content + image --}}
        <div class="image-column">
            @if($order->commission && $order->commission->image)
                <img src="{{ asset($order->commission->image) }}" alt="{{ $order->commission->title }}" class="commission-detail-image">
            @else
                <div class="no-image-placeholder">
                    <p>{{ __('No image available') }}</p>
                </div>
            @endif
        </div>

        <div class="details-column">
            <div class="card-section">
                <h3>{{ __('Order Information') }}</h3>
                <div class="detail-group">
                    <p class="detail-label"><strong>{{ __('Status Pesanan') }}:</strong></p>
                    <p class="detail-value">
                        <span class="status-badge {{ $order->status == 'paid' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
                <div class="detail-group">
                    <p class="detail-label"><strong>{{ __('Tanggal Pesan') }}:</strong></p>
                    <p class="detail-value">{{ $order->created_at->format('F j, Y, g:i a') }}</p>
                </div>
                <div class="detail-group">
                    <p class="detail-label"><strong>{{ __('Pembeli') }}:</strong></p>
                    <p class="detail-value">{{ $order->user->username ?? $order->user->name ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card-section mt-4">
                <h3>{{ __('Commission Details') }}</h3>
                @if($order->commission)
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Judul Komisi') }}:</strong></p>
                        <p class="detail-value">{{ $order->commission->title }}</p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Seniman') }}:</strong></p>
                        <p class="detail-value">
                            <a href="{{ route('artists.show', $order->commission->user->id) }}" class="table-link">
                                {{ $order->commission->user->username ?? $order->commission->user->name ?? 'Unknown Artist' }}
                            </a>
                        </p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Deskripsi') }}:</strong></p>
                        <p class="detail-value">{{ $order->commission->description ?? 'No description available' }}</p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Harga Dibayar') }}:</strong></p>
                        <p class="detail-value">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="contact-artist-button">
                        <a href="{{ route('chat.show', $order->commission->user->id) }}" class="btn-primary-custom full-width-btn">{{ __('Contact Artist') }}</a>
                    </div>
                @else
                    <div class="alert-message danger">
                        <p>{{ __('Commission details not available.') }}</p>
                    </div>
                @endif
            </div>

            {{-- Client Actions for Submitted Work --}}
            @if($order->commission && $order->commission->status == 'submitted_for_client_review' && Auth::id() == $order->user_id)
                <div class="card-section client-actions-section">
                    <h3>{{ __('Tindakan Klien: Hasil Karya Telah Dikirim') }}</h3>
                    @if($order->delivery_link)
                        <p>{{ __('Seniman telah mengirimkan hasil karya. Anda dapat melihatnya melalui link berikut') }}:</p>
                        <p><a href="{{ $order->delivery_link }}" target="_blank" rel="noopener noreferrer" class="link-external">{{ $order->delivery_link }}</a></p>
                    @else
                        <div class="alert-message warning">
                            <p>{{ __('Seniman menandai karya telah dikirim, namun link hasil karya belum tersedia.') }}</p>
                        </div>
                    @endif

                    <div class="action-buttons-group">
                        {{-- Form Setujui Hasil Karya --}}
                        <form action="{{ route('orders.approveDelivery', $order->id) }}" method="POST" class="action-form">
                            @csrf
                            <button type="submit" class="btn-primary-custom success-btn">{{ __('Setujui & Selesaikan Pesanan') }}</button>
                        </form>

                        {{-- Form Minta Revisi --}}
                        <form action="{{ route('orders.requestRevision', $order->id) }}" method="POST" class="action-form">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="revision_notes" class="form-label">{{ __('Catatan Revisi (Jika ada yang perlu diperbaiki)') }}:</label>
                                <textarea name="revision_notes" id="revision_notes" class="form-input @error('revision_notes') is-invalid @enderror" rows="3" placeholder="{{ __('Jelaskan bagian mana yang perlu direvisi...') }}">{{ old('revision_notes') }}</textarea>
                                @error('revision_notes')
                                    <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <button type="submit" class="btn-primary-custom warning-btn">{{ __('Minta Revisi') }}</button>
                        </form>
                    </div>
                </div>
            @elseif($order->commission && $order->commission->status == 'needs_revision' && Auth::id() == $order->user_id)
                <div class="alert-message info">
                    <p>{{ __('Anda telah meminta revisi untuk komisi ini. Mohon tunggu kabar dari seniman.') }}</p>
                </div>
            @elseif($order->commission && $order->commission->status == 'completed')
                <div class="alert-message success">
                    <p>{{ __('Komisi ini telah selesai.') }}</p>
                </div>
            @endif
            {{-- End Client Actions --}}
        </div>
    </div>

    @if($order->revisions && $order->revisions->count() > 0)
    <div class="card-section mt-4">
        <h3>{{ __('Histori Permintaan Revisi') }}</h3>
        <div class="revision-history-list">
            @foreach($order->revisions as $revision)
                <div class="revision-item">
                    <p class="revision-date"><strong>{{ __('Diminta pada') }}:</strong> {{ $revision->requested_at->translatedFormat('d M Y, H:i') }}</p>
                    <p class="revision-notes"><strong>{{ __('Catatan') }}:</strong> {{ nl2br(e($revision->notes)) }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- dikomen karna fitur komen belum bisa --}}
    {{-- <div class="card-section mt-4">
        <h3>{{ __('Comments') }}</h3>
        <div class="comments-list">
            @if($order->commission && $order->commission->reviews->isNotEmpty())
                @foreach($order->commission->reviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <strong>{{ $review->user->username ?? $review->user->name ?? 'Unknown' }}</strong>
                            
                            <span class="review-date">{{ $review->created_at->format('d M Y') }}</span>
                        </div>
                        <p class="review-content">{{ $review->review }}</p>
                    </div>
                @endforeach
            @else
                <p>{{ __('No Comments yet for this commission.') }}</p>
            @endif
        </div>

        @auth
            <div class="add-comment-section">
                <h4>{{ __('Add Your Comment') }}</h4>
                <form action="{{ route('commissions.addReview', $order->commission->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="review" class="form-label">{{ __('Your Comment') }}</label>
                        <textarea name="review" class="form-input" rows="3" required placeholder="{{ __('Write your comment here...') }}"></textarea>
                    </div>
                    <button type="submit" class="btn-primary-custom ml-auto">{{ __('Submit Comment') }}</button>
                </form>
            </div>
        @endauth
    </div> --}}
</div>

{{-- Bootstrap JavaScript (if not already included in your layout) - Remove this line if it's already in layouts.app --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
@endsection