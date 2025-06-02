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

                    {{-- Tombol Aksi Tambahan (Contoh) --}}
                    {{--
                    <div class="mt-4">
                        <a href="#" class="btn btn-primary">Pesan Layanan Ini</a>
                        <a href="#" class="btn btn-outline-secondary ms-2">Hubungi Artis</a>
                    </div>
                    --}}
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