@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <h2>Kelola Pesanan: {{ Str::limit($commission->title ?? $commission->description, 70) }}</h2>
            <hr>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <h4>Detail Komisi</h4>
            <p><strong>Judul/Deskripsi:</strong> {{ $commission->title ?? $commission->description }}</p>
            <p><strong>Status Saat Ini:</strong> 
                <span class="badge 
                    @switch($commission->status)
                        @case('ordered_pending_artist_action') bg-warning text-dark @break
                        @case('artist_accepted') bg-info text-dark @break
                        @case('in_progress') bg-primary @break
                        @case('submitted_for_client_review') bg-success @break
                        @case('needs_revision') bg-danger @break
                        @default bg-secondary @break
                    @endswitch">
                    {{ Str::ucfirst(str_replace('_', ' ', $commission->status)) }}
                </span>
            </p>
            @if($commission->image)
                <p><img src="{{ asset('storage/' . $commission->image) }}" alt="Gambar Komisi" style="max-width: 200px; height: auto;"></p>
            @endif

            <h4 class="mt-4">Detail Pesanan</h4>
            @if($order)
                <p><strong>Dipesan oleh:</strong> {{ $order->user->name ?? $order->user->username }}</p>
                <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                <p><strong>Total Harga:</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                <p><strong>Status Pembayaran:</strong> <span class="badge bg-success">{{ Str::ucfirst($order->status) }}</span></p>
                @if($order->delivery_link && $commission->status == 'submitted_for_client_review')
                    <div class="alert alert-info">
                        <p><strong>Link Hasil Karya yang Telah Dikirim:</strong></p>
                        <p><a href="{{ $order->delivery_link }}" target="_blank" rel="noopener noreferrer">{{ $order->delivery_link }}</a></p>
                    </div>
                @endif

                {{-- Menampilkan Catatan Revisi dari Klien --}}
                @if($commission->status == 'needs_revision' && $order->revisions->isNotEmpty())
                    <div class="alert alert-warning mt-3">
                        <h5><i class="fas fa-exclamation-triangle"></i> Permintaan Revisi dari Klien</h5>
                        @foreach($order->revisions->sortByDesc('requested_at') as $revision)
                            <div class="mb-2 p-2 border-bottom">
                                <p class="mb-1"><strong>Diminta pada:</strong> {{ $revision->requested_at->translatedFormat('d M Y, H:i') }}</p>
                                <p class="mb-0"><strong>Catatan:</strong> {{ nl2br(e($revision->notes)) }}</p>
                            </div>
                            @if($loop->first) @break @endif {{-- Hanya tampilkan yang terbaru di sini, atau loop semua jika mau --}}
                        @endforeach
                    </div>
                @endif
                {{-- Akhir Catatan Revisi --}}
            @else
                <p class="text-danger">Informasi pesanan tidak ditemukan.</p>
            @endif

            <h4 class="mt-4">Aksi Seniman</h4>
            @if(!empty($allowedActions))
                <p>Pilih aksi untuk memperbarui status komisi ini:</p>
                @foreach($allowedActions as $actionText => $statusValue)
                    <form action="{{ route('artist.orders.updateStatus', $commission->id) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="new_status" value="{{ $statusValue }}">

                        @if($statusValue === 'submitted_for_client_review')
                            <div class="mb-3">
                                <label for="delivery_link" class="form-label">Link Hasil Karya (Wajib diisi)</label>
                                <input type="url" class="form-control @error('delivery_link') is-invalid @enderror" 
                                       id="delivery_link" name="delivery_link" 
                                       value="{{ old('delivery_link', $order->delivery_link ?? '') }}" 
                                       placeholder="https://contoh.com/hasilkarya" required>
                                @error('delivery_link')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary">{{ $actionText }}</button>
                    </form>
                @endforeach
            @else
                <p>Tidak ada aksi yang tersedia untuk status komisi saat ini dari sisi Anda.</p>
                @if($commission->status === 'submitted_for_client_review')
                <p>Menunggu review dari klien.</p>
                @elseif($commission->status === 'completed')
                <p>Komisi ini telah selesai.</p>
                @endif
            @endif
            <hr>
            <a href="{{ route('artist.orders.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Pesanan</a>
        </div>
        <div class="col-md-4">
            {{-- Sidebar atau informasi tambahan jika perlu --}}
            <h5>Catatan:</h5>
            <p><small>Pastikan untuk selalu berkomunikasi dengan klien Anda melalui fitur chat jika ada pertanyaan atau klarifikasi yang diperlukan.</small></p>
        </div>
    </div>
</div>
@endsection