<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\OrderResource;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TODO: Implement pagination and eager loading as per plan
        // Order::with(['user', 'commission.user', 'payments'])->latest()->paginate()
        $orders = Order::with(['user', 'commission.user', 'payments'])->latest()->paginate($request->input('limit', 10));
        return OrderResource::collection($orders);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        // TODO: Ensure all necessary relations are loaded as per plan
        $order->load(['user', 'commission.user', 'payments']);
        return new OrderResource($order);
    }

    /**
     * Update the status of the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    // public function updateStatus(Request $request, Order $order)
    // {
    //     // TODO: Implement status update logic and validation
    //     // $validated = $request->validate([
    //     //     'status' => 'required|string|in:pending,processing,shipped,completed,cancelled,paid,failed', // Add other valid statuses
    //     // ]);
    //
    //     // $order->status = $validated['status'];
    //     // $order->save();
    //
    //     // return new OrderResource($order);
    // }
}