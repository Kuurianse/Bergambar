<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Added

class PaymentController extends Controller
{
    public function __construct()
    {
        // Restrict all payment management to authenticated users for now.
        // Further role-based authorization (e.g., admin) should be added.
        $this->middleware('auth');
        // Example for admin-only access (requires admin role/middleware setup):
        // $this->middleware('admin');
    }

    // Menampilkan daftar semua pembayaran
    public function index()
    {
        $payments = Payment::with('commission')->get();
        return view('payments.index', compact('payments'));
    }

    // Menampilkan detail pembayaran tertentu
    public function show($id)
    {
        $payment = Payment::with('commission')->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    // Form untuk menambah pembayaran
    public function create()
    {
        $commissions = Commission::all(); // Menampilkan daftar commission untuk dipilih
        return view('payments.create', compact('commissions'));
    }

    // Menyimpan pembayaran baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'commission_id' => 'required|exists:commissions,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'payment_status' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        Payment::create($validatedData);

        return redirect()->route('payments.index');
    }

    // Mengupdate pembayaran
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        $validatedData = $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'payment_status' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        $payment->update($validatedData);

        return redirect()->route('payments.show', $id);
    }

    // Menghapus pembayaran
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.index');
    }
}
