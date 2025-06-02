@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>Kelola Pesanan Komisi Saya</h2>

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
    @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    @if($commissionsWithOrders->isEmpty())
        <p>Anda belum memiliki pesanan komisi aktif saat ini.</p>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Judul Komisi</th>
                        <th>Klien</th>
                        <th>Tanggal Pesan</th>
                        <th>Status Komisi</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commissionsWithOrders as $commission)
                        @php
                            // Asumsi mengambil order pertama yang paid, karena controller sudah memfilter
                            $order = $commission->orders->firstWhere('status', 'paid');
                        @endphp
                        @if($order)
                            <tr>
                                <td>
                                    <a href="{{ route('commissions.show', $commission->id) }}" target="_blank">
                                        {{ Str::limit($commission->title ?? $commission->description, 50) }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name ?? $order->user->username ?? 'N/A' }}</td>
                                <td>{{ $order->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td>
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
                                </td>
                                <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('artist.orders.show', $commission->id) }}" class="btn btn-sm btn-primary">
                                        Kelola Pesanan
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $commissionsWithOrders->links() }}
        </div>
    @endif
</div>
@endsection