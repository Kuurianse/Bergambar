<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Artist;
use App\Models\Commission;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB; // Added for potential complex queries if needed

class DashboardController extends Controller
{
    /**
     * Display a listing of dashboard statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $totalUsers = User::count();
        $totalArtists = Artist::count(); // Assumes Artist model represents artists directly
        $totalCommissions = Commission::count();
        $totalOrders = Order::count();

        $recentUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get(['id', 'name', 'email', 'created_at']);

        $recentCommissions = Commission::with(['artist.user', 'service']) // Eager load artist's user and service
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($commission) {
                return [
                    'id' => $commission->id,
                    'title' => $commission->title,
                    // Assuming Commission model has 'artist' relationship which in turn has 'user' relationship
                    'artist_name' => $commission->artist && $commission->artist->user ? $commission->artist->user->name : 'N/A',
                    'service_title' => $commission->service ? $commission->service->title : 'N/A', // Include service title
                    'status' => $commission->status,
                    'price' => $commission->total_price, // Corrected field name
                    'created_at' => $commission->created_at,
                ];
            });

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalArtists' => $totalArtists,
            'totalCommissions' => $totalCommissions,
            'totalOrders' => $totalOrders,
            'recentUsers' => $recentUsers,
            'recentCommissions' => $recentCommissions,
        ]);
    }
}