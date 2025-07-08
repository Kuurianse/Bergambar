<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Commission;
use App\Models\Payment; // Added
use App\Models\OrderRevision; // Ditambahkan
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
        $order = Order::with(['commission.user', 'revisions.user'])->findOrFail($id); // Eager load commission, its user, and revisions with their users

        // Authorization: Ensure the logged-in user owns this order
        if (Auth::id() !== $order->user_id) {
            // Jika ingin admin bisa melihat semua order, tambahkan kondisi: && !Auth::user()->isAdmin()
            abort(403, 'Aksi tidak diizinkan. Anda hanya dapat melihat pesanan Anda sendiri.');
        }

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
    // public function confirmPayment(Commission $commission)
    // {
    //     // Create a new order entry
    //     $order = Order::create([ // Assign to $order
    //         'user_id' => Auth::id(),
    //         'commission_id' => $commission->id,
    //         'status' => 'paid', // Assuming payment is confirmed by this action
    //         'total_price' => $commission->total_price,
    //     ]);

    //     // Create a corresponding Payment record
    //     if ($order) { // Ensure order was created
    //         Payment::create([
    //             'order_id' => $order->id,
    //             'commission_id' => $commission->id,
    //             'payment_method' => 'qris_simulation', // Placeholder for now
    //             'amount' => $order->total_price,
    //             'payment_status' => 'completed', // Assuming direct confirmation
    //             'payment_date' => now(),
    //         ]);

    //         // Update the commission status
    //         $commission->status = 'ordered_pending_artist_action';
    //         $commission->save();
    //     }
        
    //     // Redirect to a success page or back to the orders page
    //     return redirect()->route('orders.index')->with('message', 'Payment confirmed and order created successfully! The artist has been notified.');
    // }

    // app/Http/Controllers/OrderController.php

public function confirmPayment(Commission $commission)
{
    // --- LANGKAH 1: Cek apakah pesanan untuk komisi ini sudah ada dari user yang sama ---
    $existingOrder = Order::where('commission_id', $commission->id)
                            ->where('user_id', Auth::id())
                            ->first();

    // Jika pesanan sudah ada, jangan buat yang baru. Langsung redirect.
    if ($existingOrder) {
        return redirect()->route('orders.show', $existingOrder->id)
                         ->with('info', 'You have already placed an order for this commission.');
    }

    // --- LANGKAH 2: Jika belum ada, baru buat pesanan baru ---
    // Kode ini hanya akan berjalan jika pesanan belum ada
    $order = Order::create([
        'user_id' => Auth::id(),
        'commission_id' => $commission->id,
        'status' => 'paid', // Asumsi pembayaran langsung dikonfirmasi
        'total_price' => $commission->total_price,
    ]);

    // Buat record Pembayaran terkait
    if ($order) {
        Payment::create([
            'order_id' => $order->id,
            'commission_id' => $commission->id,
            'payment_method' => 'qris_simulation', // Placeholder
            'amount' => $order->total_price,
            'payment_status' => 'completed', // Asumsi pembayaran langsung selesai
            'payment_date' => now(),
        ]);

        // Update status komisi menjadi dipesan
        $commission->status = 'ordered_pending_artist_action';
        $commission->save();
    }
    
    // Arahkan ke halaman My Orders setelah berhasil membuat pesanan baru
    return redirect()->route('orders.index')->with('success', 'Payment confirmed and order created successfully!');
}



    public function approveDelivery(Order $order)
    {
        // Otorisasi: Pastikan pengguna yang login adalah pemilik order
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Validasi: Pastikan status komisi adalah 'submitted_for_client_review'
        if (!$order->commission || $order->commission->status !== 'submitted_for_client_review') {
            return redirect()->route('orders.show', $order->id)->with('error', 'Tidak dapat menyetujui hasil karya pada status komisi saat ini.');
        }

        // Aksi
        $commission = $order->commission;
        $commission->status = 'completed';
        $commission->save();

        $order->status = 'completed'; // Atau 'client_approved' jika ada status order yang lebih spesifik
        $order->save();

        // TODO: Kirim notifikasi ke seniman bahwa karya telah disetujui

        return redirect()->route('orders.show', $order->id)->with('success', 'Hasil karya telah disetujui dan pesanan diselesaikan.');
    }

    public function requestRevision(Request $request, Order $order)
    {
        // Otorisasi: Pastikan pengguna yang login adalah pemilik order
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Validasi: Pastikan status komisi adalah 'submitted_for_client_review'
        if (!$order->commission || $order->commission->status !== 'submitted_for_client_review') {
            return redirect()->route('orders.show', $order->id)->with('error', 'Tidak dapat meminta revisi pada status komisi saat ini.');
        }

        $validated = $request->validate([
            'revision_notes' => 'required|string|max:5000',
        ],[
            'revision_notes.required' => 'Catatan revisi tidak boleh kosong.',
            'revision_notes.max' => 'Catatan revisi tidak boleh lebih dari 5000 karakter.',
        ]);

        // Aksi
        OrderRevision::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'notes' => $validated['revision_notes'],
            'requested_at' => now(),
        ]);

        $commission = $order->commission;
        $commission->status = 'needs_revision';
        $commission->save();

        // TODO: Kirim notifikasi ke seniman bahwa revisi diminta

        return redirect()->route('orders.show', $order->id)->with('success', 'Permintaan revisi telah dikirim ke seniman.');
    }
}
