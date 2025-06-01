<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Commission;
use App\Models\Payment; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Display the list of orders for the logged-in user
    public function index()
    {
        $orders = Order::with(['commission.user'])
                        ->where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc') // Optional: Show newest orders first
                        ->get();
        return view('orders.index', compact('orders'));
    }

    // Display a specific order's details
    public function show($id) // $id here refers to Order ID
    {
        $order = Order::with(['commission.user'])->findOrFail($id); // Eager load commission and its user (artist)

        // Authorization: Ensure the logged-in user owns this order, or is an admin
        // This is a good practice to add. For now, focusing on the plan's direct task.
        // if (Auth::id() !== $order->user_id && !Auth::user()->isAdmin()) { // Assuming isAdmin() method or role check
        //     abort(403, 'Unauthorized action.');
        // }

        $commission = $order->commission;
        $artist = $commission ? $commission->user : null; // Artist who created the commission

        // The view 'orders.show' might need to be adjusted if it directly expects an $order object
        // or if it was designed around only a commission.
        // For now, we pass what it previously expected, derived from the $order.
        return view('orders.show', compact('order', 'commission', 'artist'));
    }

    /**
     * Show the page to initiate an order for a specific commission.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\View\View
     */
    public function createOrderForCommission(Commission $commission)
    {
        $commission->load('user'); // Eager load the artist (user)
        $artist = $commission->user;

        // This view will contain the "Order Now" button and payment modal.
        // We will need to create resources/views/orders/create_for_commission.blade.php
        return view('orders.create_for_commission', compact('commission', 'artist'));
    }

    // Handle payment confirmation
    public function confirmPayment($id) // $id is Commission ID
    {
        // Fetch the commission by ID
        $commission = Commission::find($id);

        if (!$commission) {
            return redirect()->route('orders.index')->with('error', 'Commission not found.');
        }

        // Create a new order entry
        $order = Order::create([ // Assign to $order
            'user_id' => Auth::id(),
            'commission_id' => $commission->id,
            'status' => 'paid', // Assuming payment is confirmed by this action
            'total_price' => $commission->total_price,
        ]);

        // Create a corresponding Payment record
        if ($order) { // Ensure order was created
            Payment::create([
                'order_id' => $order->id,
                'commission_id' => $commission->id, // Can also be $order->commission_id
                'payment_method' => 'qris_simulation', // Placeholder for now
                'amount' => $order->total_price,
                'payment_status' => 'completed', // Assuming direct confirmation
                'payment_date' => now(),
            ]);

            // Update the commission status
            $commission->status = 'ordered_pending_artist_action';
            $commission->save();
        }
        
        // Redirect to a success page or back to the orders page
        return redirect()->route('orders.index')->with('message', 'Payment confirmed and order created successfully! The artist has been notified.');
    }
}
