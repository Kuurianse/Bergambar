@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $service->title }}</h3>
                </div>
                <div class="card-body">
                    <p><strong>Deskripsi:</strong></p>
                    <p>{{ $service->description }}</p>
                    <hr>
                    <p><strong>Harga:</strong> Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                    <p><strong>Tipe Layanan:</strong> {{ ucfirst($service->service_type) }}</p>
                    <p><strong>Kategori:</strong> {{ $service->category->name ?? 'Tidak ada kategori' }}</p>
                    <p>
                        <strong>Status:</strong>
                        @if($service->availability_status)
                            <span class="badge bg-success">Tersedia</span>
                        @else
                            <span class="badge bg-secondary">Tidak Tersedia</span>
                        @endif
                    </p>
                    <hr>
                    @if($service->artist && $service->artist->user)
                        <p><strong>Ditawarkan oleh:</strong>
                            <a href="{{ route('artists.show', $service->artist->id) }}">{{ $service->artist->user->name ?? $service->artist->user->username }}</a>
                        </p>
                    @else
                        <p><strong>Artis:</strong> Informasi artis tidak tersedia.</p>
                    @endif

                    {{-- Tombol Aksi Tambahan --}}
                    <div class="mt-4">
                        @if($service->availability_status && Auth::check() && (!Auth::user()->artist || Auth::user()->artist->id !== $service->artist_id))
                            {{-- Link to general commission creation, user can select service there if artist offers multiple --}}
                            {{-- Or, ideally, this would link to a route that pre-fills commission form with this service's details --}}
                            <a href="{{ route('commissions.create', ['service_id' => $service->id, 'artist_id' => $service->artist_id]) }}" class="btn btn-primary">Pesan Layanan Ini</a>
                        @elseif(Auth::check() && Auth::user()->artist && Auth::user()->artist->id == $service->artist_id)
                            {{-- Artist viewing their own service --}}
                        @elseif(!$service->availability_status)
                             <button class="btn btn-primary" disabled>Layanan Tidak Tersedia</button>
                        @else
                             <a href="{{ route('login') }}?redirect={{ route('services.show', $service->id) }}" class="btn btn-primary">Login untuk Memesan</a>
                        @endif
                        
                        @if($service->artist && Auth::check() && (!Auth::user()->artist || Auth::user()->artist->id !== $service->artist_id))
                            <a href="{{ route('chat.show', $service->artist->user->id) }}" class="btn btn-outline-secondary ms-2">Hubungi Artis</a>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                    @auth
                        @if(Auth::user()->artist && Auth::user()->artist->id == $service->artist_id)
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning ms-2">Edit Layanan Ini</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection