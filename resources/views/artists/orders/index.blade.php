@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <h2>{{ __('Kelola Pesanan Komisi Saya') }}</h2>
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
    @if (session('info'))
        <div class="alert-message info">
            {{ session('info') }}
        </div>
    @endif

    @if($commissionsWithOrders->isEmpty())
        <div class="empty-state">
            <p>{{ __('Anda belum memiliki pesanan komisi aktif saat ini.') }}</p>
        </div>
    @else
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('Judul Komisi') }}</th>
                        <th>{{ __('Klien') }}</th>
                        <th>{{ __('Tanggal Pesan') }}</th>
                        <th>{{ __('Status Komisi') }}</th>
                        <th>{{ __('Total Harga') }}</th>
                        <th>{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commissionsWithOrders as $commission)
                        @php
                            // Assuming taking the first paid order, as the controller already filters
                            $order = $commission->orders->firstWhere('status', 'paid');
                        @endphp
                        @if($order)
                            <tr>
                                <td>
                                    <a href="{{ route('commissions.show', $commission->id) }}" target="_blank" class="table-link">
                                        {{ Str::limit($commission->title ?? $commission->description, 50) }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name ?? $order->user->username ?? 'N/A' }}</td>
                                <td>{{ $order->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td>
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
                                </td>
                                <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="actions-cell">
                                    <a href="{{ route('artist.orders.show', $commission->id) }}" class="btn-action primary">
                                        {{ __('Kelola Pesanan') }}
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $commissionsWithOrders->links() }}
        </div>
    @endif
</div>
@endsection