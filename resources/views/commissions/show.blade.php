@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="page-wrapper">
    @if($commission)
        <div class="page-header">
            {{-- <h2>{{ __('Commission Title') }}: {{ $commission->title }}</h2> --}}
            <h2>{{ $commission->title }}</h2>
        </div>

        <div class="commission-detail-grid-public">
            <div class="commission-image-section">
                @if($commission->image)
                    <img src="{{ asset( 'storage/'. $commission->image) }}" alt="Commission Image for {{ $commission->description }}" class="commission-display-image">
                @else
                    <div class="no-image-placeholder">
                        <p>{{ __('No image has been provided for this commission.') }}</p>
                    </div>
                @endif
            </div>

            <div class="commission-info-section">
                <div class="card-section">
                    <h3>{{ $commission->description }}</h3>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Artist') }}:</strong></p>
                        <p class="detail-value">
                            <a href="{{ route('artists.show', $commission->user->artist->id ?? $commission->user_id) }}" class="table-link">
                                {{ $commission->user->username ?? $commission->user->name ?? 'N/A' }}
                            </a>
                        </p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Status') }}:</strong></p>
                        <p class="detail-value">
                            @php
                                $badgeClass = 'secondary'; // Default
                                switch ($commission->public_status) {
                                    case 'Available':
                                        $badgeClass = 'success';
                                        break;
                                    case 'Ordered':
                                        $badgeClass = 'warning';
                                        break;
                                    case 'Completed':
                                        $badgeClass = 'primary';
                                        break;
                                    case 'Status Undefined':
                                        $badgeClass = 'danger';
                                        break;
                                }
                            @endphp
                            <span class="status-badge {{ $badgeClass }}">{{ $commission->public_status }}</span>
                        </p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Price') }}:</strong></p>
                        <p class="detail-value">Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Created') }}:</strong></p>
                        <p class="detail-value">{{ $commission->created_at->format('F j, Y') }}</p>
                    </div>
                    {{-- Perbarui ini untuk menggunakan ID yang unik jika perlu, atau gunakan .love-count-global jika ada di banyak tempat --}}
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Loves') }}:</strong></p>
                        <p class="detail-value"><span id="globalLoveCount-{{$commission->id}}">{{ $commission->loved_count ?? 0 }}</span></p>
                    </div>

                    @auth
                    <div class="commission-action-buttons">
                        {{-- Love Button --}}
                        {{-- Menggunakan class `love-form` untuk identifikasi, dan `data-commission-id` pada tombol --}}
                        <form action="{{ route('commissions.toggleLove', $commission->id) }}" method="POST" class="love-form">
                            @csrf
                            {{-- Ubah type menjadi button agar tidak submit form secara default --}}
                            <button type="button" class="btn-action love-button
                                {{ $commission->loves->contains(Auth::user()) ? 'danger' : 'outline-danger' }}"
                                data-commission-id="{{$commission->id}}">
                                {{-- Gunakan kondisi untuk ikon hati awal --}}
                                <i class="fa-{{ $commission->loves->contains(Auth::user()) ? 'solid' : 'regular' }} fa-heart"></i>
                                {{-- Spans for text and count are inside the button --}}
                                <span class="love-text">{{ $commission->loves->contains(Auth::user()) ? 'Loved' : 'Love' }}</span>
                                (<span class="love-count">{{ $commission->loved_count ?? 0 }}</span>)
                            </button>
                        </form>

                        {{-- Order Button --}}
                        @if(Auth::id() !== $commission->user_id && $commission->public_status == 'Available')
                            <a href="{{ route('commissions.order', $commission->id) }}" class="btn-primary-custom">{{ __('Order This Commission') }}</a>
                        @endif
                        
                        {{-- Edit Button for Owner --}}
                        @if(Auth::id() == $commission->user_id)
                            <a href="{{ route('commissions.edit', $commission->id) }}" class="btn-primary-custom edit-btn">{{ __('Edit Commission') }}</a>
                        @endif
                    </div>
                    @endauth
                </div>
            </div>
        </div>

        <div class="comments-section-container">
            <div class="card-section mt-4">
                <h3>{{ __('Reviews') }}</h3>
                <div class="comments-list">
                    @if($commission->reviews && $commission->reviews->count() > 0)
                        @foreach($commission->reviews as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <strong>{{ $review->user->username ?? $review->user->name ?? 'Unknown' }}</strong>
                                    @if($review->rating)
                                        <span class="review-rating">Rating: {{ $review->rating }} / 5</span>
                                    @endif
                                    <span class="review-date">{{ $review->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                                <p class="review-content">{{ $review->review }}</p>
                            </div>
                        @endforeach
                    @else
                        <p>{{ __('There are no reviews for this commission yet.') }}</p>
                    @endif
                </div>
            </div>

            @auth
            <div class="card-section mt-4 add-comment-section">
                <h4>{{ __('Leave a Review') }}</h4>
                <form action="{{ route('commissions.addReview', $commission->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="review">{{ __('Your Review') }}</label>
                        <textarea name="review" id="review" class="form-input @error('review') is-invalid @enderror" rows="3" placeholder="{{ __('Write your review...') }}" required>{{ old('review') }}</textarea>
                        @error('review')
                            <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="rating">{{ __('Rating') }}</label>
                        <select name="rating" id="rating" class="form-input form-select-custom @error('rating') is-invalid @enderror" required>
                            <option value="">{{ __('Select Rating') }}</option>
                            <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 {{ __('Star') }}</option>
                            <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 {{ __('Stars') }}</option>
                            <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 {{ __('Stars') }}</option>
                            <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 {{ __('Stars') }}</option>
                            <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 {{ __('Stars') }}</option>
                        </select>
                        @error('rating')
                            <span class="form-error-message" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-actions form-actions-single-button">
                        <button type="submit" class="btn-primary-custom">{{ __('Submit Review') }}</button>
                    </div>
                </form>
            </div>
            @endauth
        </div>

    @else
        <div class="empty-state">
            <p>{{ __('The requested commission could not be found.') }}</p>
        </div>
    @endif
</div>

@push('scripts')
    <script src="{{ asset('js/love.js') }}"></script>
@endpush
@endsection