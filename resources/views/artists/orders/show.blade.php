@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <h2>{{ __('Kelola Pesanan') }}: {{ Str::limit($commission->title ?? $commission->description, 70) }}</h2>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="alert-message success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert-message danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="content-grid">
        <div class="main-content-area">
            <div class="card-section">
                <h3>{{ __('Detail Komisi') }}</h3>
                <div class="detail-group">
                    <p class="detail-label"><strong>{{ __('Judul/Deskripsi') }}:</strong></p>
                    <p class="detail-value">{{ $commission->title ?? $commission->description }}</p>
                </div>
                <div class="detail-group">
                    <p class="detail-label"><strong>{{ __('Status Saat Ini') }}:</strong></p>
                    <p class="detail-value">
                        <span class="status-badge 
                            @switch($commission->status)
                                @case('ordered_pending_artist_action') warning @break
                                @case('artist_accepted') info @break
                                @case('in_progress') primary @break
                                @case('submitted_for_client_review') success @break
                                @case('needs_revision') danger @break
                                @default secondary @break
                            @endswitch">
                            {{ Str::ucfirst(str_replace('_', ' ', $commission->status)) }}
                        </span>
                    </p>
                </div>
                @if($commission->image)
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Gambar Komisi') }}:</strong></p>
                        <p class="detail-value">
                            <img src="{{ asset('storage/' . $commission->image) }}" alt="Gambar Komisi" class="commission-image">
                        </p>
                    </div>
                @endif
            </div>

            <div class="card-section mt-4">
                <h3>{{ __('Detail Pesanan') }}</h3>
                @if($order)
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Dipesan oleh') }}:</strong></p>
                        <p class="detail-value">{{ $order->user->name ?? $order->user->username }}</p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Tanggal Pesan') }}:</strong></p>
                        <p class="detail-value">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Total Harga') }}:</strong></p>
                        <p class="detail-value">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="detail-group">
                        <p class="detail-label"><strong>{{ __('Status Pembayaran') }}:</strong></p>
                        <p class="detail-value">
                            <span class="status-badge success">{{ Str::ucfirst($order->status) }}</span>
                        </p>
                    </div>
                    
                    @if($order->delivery_link && $commission->status == 'submitted_for_client_review')
                        <div class="alert-message info">
                            <p><strong>{{ __('Link Hasil Karya yang Telah Dikirim') }}:</strong></p>
                            <p><a href="{{ $order->delivery_link }}" target="_blank" rel="noopener noreferrer" class="alert-link">{{ $order->delivery_link }}</a></p>
                        </div>
                    @endif

                    {{-- Menampilkan Catatan Revisi dari Klien --}}
                    @if($commission->status == 'needs_revision' && $order->revisions->isNotEmpty())
                        <div class="alert-message warning revision-notes-card">
                            <h5><i class="fas fa-exclamation-triangle"></i> {{ __('Permintaan Revisi dari Klien') }}</h5>
                            @foreach($order->revisions->sortByDesc('requested_at') as $revision)
                                <div class="revision-item">
                                    <p class="revision-date"><strong>{{ __('Diminta pada') }}:</strong> {{ $revision->requested_at->translatedFormat('d M Y, H:i') }}</p>
                                    <p class="revision-notes"><strong>{{ __('Catatan') }}:</strong> {{ nl2br(e($revision->notes)) }}</p>
                                </div>
                                @if($loop->first) @break @endif {{-- Hanya tampilkan yang terbaru di sini, atau loop semua jika mau --}}
                            @endforeach
                        </div>
                    @endif
                    {{-- Akhir Catatan Revisi --}}
                @else
                    <p class="text-danger">{{ __('Informasi pesanan tidak ditemukan.') }}</p>
                @endif
            </div>

            <div class="card-section mt-4">
                <h3>{{ __('Aksi Seniman') }}</h3>
                @if(!empty($allowedActions))
                    <p>{{ __('Pilih aksi untuk memperbarui status komisi ini') }}:</p>
                    <div class="artist-actions-group">
                        @foreach($allowedActions as $actionText => $statusValue)
                            <form action="{{ route('artist.orders.updateStatus', $commission->id) }}" method="POST" class="action-form">
                                @csrf
                                <input type="hidden" name="new_status" value="{{ $statusValue }}">

                                @if($statusValue === 'submitted_for_client_review')
                                    <div class="form-group mb-3">
                                        <label for="delivery_link" class="form-label">{{ __('Link Hasil Karya (Wajib diisi)') }}</label>
                                        <input type="url" class="form-input @error('delivery_link') is-invalid @enderror" 
                                               id="delivery_link" name="delivery_link" 
                                               value="{{ old('delivery_link', $order->delivery_link ?? '') }}" 
                                               placeholder="https://contoh.com/hasilkarya" required>
                                        @error('delivery_link')
                                            <span class="form-error-message" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                @endif
                                <button type="submit" class="btn-primary-custom">{{ $actionText }}</button>
                            </form>
                        @endforeach
                    </div>
                @else
                    <p>{{ __('Tidak ada aksi yang tersedia untuk status komisi saat ini dari sisi Anda.') }}</p>
                    @if($commission->status === 'submitted_for_client_review')
                        <p>{{ __('Menunggu review dari klien.') }}</p>
                    @elseif($commission->status === 'completed')
                        <p>{{ __('Komisi ini telah selesai.') }}</p>
                    @endif
                @endif
            </div>

            <div class="back-link-container">
                <a href="{{ route('artist.orders.index') }}" class="btn-link-custom">{{ __('Kembali ke Daftar Pesanan') }}</a>
            </div>
        </div>

        <div class="sidebar-content-area">
            <div class="card-section">
                <h3>{{ __('Catatan Penting') }}</h3>
                <p class="sidebar-note">
                    <small>{{ __('Pastikan untuk selalu berkomunikasi dengan klien Anda melalui fitur chat jika ada pertanyaan atau klarifikasi yang diperlukan.') }}</small>
                </p>
                {{-- Anda bisa menambahkan link chat di sini jika ada --}}
                {{-- <a href="{{ route('chat.with', $order->user->id) }}" class="btn-primary-custom full-width-btn mt-3">Mulai Chat dengan Klien</a> --}}
            </div>
        </div>
    </div>
</div>
@endsection