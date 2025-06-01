@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('My Orders') }}</div>

                <div class="card-body">
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($orders->isEmpty())
                        <p>You have no orders yet.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Commission Title</th>
                                    <th scope="col">Artist</th>
                                    <th scope="col">Total Price</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Order Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            @if($order->commission)
                                                <a href="{{ route('commissions.show', $order->commission_id) }}">{{ $order->commission->title ?? 'N/A' }}</a>
                                            @else
                                                Commission Data Missing
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->commission && $order->commission->user)
                                                <a href="{{ route('artists.show', $order->commission->user->id) }}">{{ $order->commission->user->name ?? 'N/A' }}</a>
                                            @else
                                                Artist Data Missing
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($order->total_price, 2, ',', '.') }}</td>
                                        <td>{{ ucfirst($order->status) }}</td>
                                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">View Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection