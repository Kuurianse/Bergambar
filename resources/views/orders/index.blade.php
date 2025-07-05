@extends('layouts.app')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/others.css') }}" />
@endsection

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <h2>{{ __('My Orders') }}</h2>
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

    @if($orders->isEmpty())
        <div class="empty-state">
            <p>{{ __('You have no orders yet.') }}</p>
        </div>
    @else
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Commission Title') }}</th>
                        <th scope="col">{{ __('Artist') }}</th>
                        <th scope="col">{{ __('Total Price') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Order Date') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($order->commission)
                                    <a href="{{ route('commissions.show', $order->commission_id) }}" class="table-link">{{ $order->commission->title ?? 'N/A' }}</a>
                                @else
                                    {{ __('Commission Data Missing') }}
                                @endif
                            </td>
                            <td>
                                @if($order->commission && $order->commission->user)
                                    <a href="{{ route('artists.show', $order->commission->user->id) }}" class="table-link">{{ $order->commission->user->name ?? 'N/A' }}</a>
                                @else
                                    {{ __('Artist Data Missing') }}
                                @endif
                            </td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td> {{-- Adjusted to 0 decimal places for consistency --}}
                            <td>
                                <span class="status-badge 
                                    @switch($order->commission->status ?? 'default')
                                        @case('paid') success @break
                                        @case('ordered_pending_artist_action') warning @break
                                        @case('artist_accepted') info @break
                                        @case('in_progress') primary @break
                                        @case('submitted_for_client_review') info @break {{-- Client review can be info for client's view --}}
                                        @case('needs_revision') danger @break
                                        @case('completed') success @break
                                        @default secondary @break
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $order->commission->status ?? $order->status)) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="actions-cell">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn-action primary">{{ __('View Details') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- If you have pagination for orders --}}
        {{-- <div class="pagination-container">
            {{ $orders->links() }}
        </div> --}}
    @endif
</div>
@endsection