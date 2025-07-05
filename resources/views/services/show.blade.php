@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <h2>{{ $service->title }}</h2>
    </div>

    <div class="service-detail-grid">
        <div class="service-image-area">
            @if($service->image)
                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="service-main-image">
            @else
                <div class="no-image-placeholder">
                    <p>{{ __('No image available') }}</p>
                </div>
            @endif
        </div>

        <div class="service-info-area">
            <div class="card-section">
                <h3>{{ __('Deskripsi Layanan') }}</h3>
                <p class="service-description-text">{{ $service->description }}</p>
            </div>

            <div class="card-section mt-4">
                <h3>{{ __('Detail') }}</h3>
                <div class="detail-group">
                    <p class="detail-label"><strong>{{ __('Harga') }}:</strong></p>
                    <p class="detail-value">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                </div>
                <div class="detail-group">
                    <p class="detail-label"><strong>{{ __('Tipe Layanan') }}:</strong></p>
                    <p class="detail-value">{{ ucfirst($service->service_type) }}</p>
                </div>
                <div class="detail-group">
                    <p class="detail-label"><strong>{{ __('Kategori') }}:</strong></p>
                    <p class="detail-value">{{ $service->category->name ?? __('Tidak ada kategori') }}</p>
                </div>
                <div class="detail-group">
                    <p class="detail-label"><strong>{{ __('Status') }}:</strong></p>
                    <p class="detail-value">
                        <span class="status-badge {{ $service->availability_status ? 'success' : 'secondary' }}">
                            {{ $service->availability_status ? __('Tersedia') : __('Tidak Tersedia') }}
                        </span>
                    </p>
                </div>
                <div class="detail-group">
                    <p class="detail-label"><strong>{{ __('Ditawarkan oleh') }}:</strong></p>
                    <p class="detail-value">
                        @if($service->artist && $service->artist->user)
                            <a href="{{ route('artists.show', $service->artist->id) }}" class="table-link">
                                {{ $service->artist->user->name ?? $service->artist->user->username }}
                            </a>
                        @else
                            {{ __('Informasi artis tidak tersedia.') }}
                        @endif
                    </p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="service-actions-area mt-4">
                @if($service->availability_status && Auth::check() && (!Auth::user()->artist || Auth::user()->artist->id !== $service->artist_id))
                    {{-- Link to general commission creation, user can select service there if artist offers multiple --}}
                    {{-- Or, ideally, this would link to a route that pre-fills commission form with this service's details --}}
                    <a href="{{ route('commissions.create', ['service_id' => $service->id, 'artist_id' => $service->artist_id]) }}" class="btn-primary-custom full-width-btn">
                        {{ __('Pesan Layanan Ini') }}
                    </a>
                @elseif(Auth::check() && Auth::user()->artist && Auth::user()->artist->id == $service->artist_id)
                    {{-- Artis melihat layanan mereka sendiri --}}
                    <a href="{{ route('services.edit', $service->id) }}" class="btn-primary-custom full-width-btn edit-btn">
                        {{ __('Edit Layanan Ini') }}
                    </a>
                @elseif(!$service->availability_status)
                    <button class="btn-primary-custom disabled-btn" disabled>
                        {{ __('Layanan Tidak Tersedia') }}
                    </button>
                @else
                    <a href="{{ route('login') }}?redirect={{ route('services.show', $service->id) }}" class="btn-primary-custom full-width-btn">
                        {{ __('Login untuk Memesan') }}
                    </a>
                @endif
                
                @if($service->artist && Auth::check() && (!Auth::user()->artist || Auth::user()->artist->id !== $service->artist_id))
                    <a href="{{ route('chat.show', $service->artist->user->id) }}" class="btn-link-custom full-width-btn mt-2">
                        {{ __('Hubungi Artis') }}
                    </a>
                @endif
            </div>

            <div class="back-link-container mt-4">
                <a href="{{ url()->previous() }}" class="btn-link-custom">{{ __('Kembali') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection