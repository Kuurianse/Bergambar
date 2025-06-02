<?php

namespace App\Http\Controllers;

use App\Models\Artist; // Added
use App\Models\Commission;
use App\Models\User;
use Illuminate\Http\Request; // Added
use Illuminate\Support\Facades\Auth; // Added

class ArtistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']); // Added for artist profile management
    }

    // Display a list of artists (users with commissions)
    public function index()
    {
        // Retrieve all Artist profiles, with their associated User model and counts of commissions and services
        $artists = Artist::with(['user' => function ($query) {
                            $query->withCount('commissions'); // Loads user_commissions_count on the user model
                         }])
                         ->withCount('services') // Loads services_count on the artist model
                         ->latest()
                         ->paginate(10);

        // Pass the artist data to the 'artists.index' view
        // This view will need to be updated to work with Artist models
        return view('artists.index', compact('artists'));
    }

    /**
     * Display the specified artist profile.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\View\View
     */
    public function show(Artist $artist) // Route model binding for Artist
    {
        // Eager load necessary relationships for the artist's profile page
        $artist->load([
            'user.commissions' => function ($query) {
                $query->with(['reviews.user', 'loves'])->latest();
            },
            'services'
        ]);

        // The view 'artists.show' will need to be updated to work with an Artist model
        // and its relationships (e.g., $artist->user->name, $artist->portfolio_link, $artist->user->commissions)
        return view('artists.show', compact('artist'));
    }

    /**
     * Show the form for creating a new artist profile.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Check if the user already has an artist profile
        $existingArtistProfile = Artist::where('user_id', Auth::id())->first();
        if ($existingArtistProfile) {
            // Redirect to their existing profile, or an edit page
            // For now, let's assume we redirect to a conceptual 'artists.show' for Artist model
            return redirect()->route('artists.show', $existingArtistProfile->id)->with('info', 'You already have an artist profile.');
        }
        return view('artists.create'); // Needs artists.create.blade.php
    }

    /**
     * Store a newly created artist profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $existingArtistProfile = Artist::where('user_id', Auth::id())->first();
        if ($existingArtistProfile) {
            return redirect()->route('artists.show', $existingArtistProfile->id)->with('info', 'You already have an artist profile.');
        }

        $validatedData = $request->validate([
            'portfolio_link' => 'nullable|url|max:255',
            // 'is_verified' and 'rating' are typically not set by users directly.
        ]);

        $artist = new Artist();
        $artist->user_id = Auth::id();
        $artist->portfolio_link = $validatedData['portfolio_link'] ?? null;
        // is_verified and rating will have defaults or be handled by admin/system
        $artist->save();

        // We'll need to adjust routes and the show method later to correctly handle Artist model.
        // For now, this conceptual redirect assumes artists.show takes an Artist ID.
        return redirect()->route('artists.show', $artist->id)->with('success', 'Artist profile created successfully!');
    }

    /**
     * Show the form for editing the specified artist profile.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Artist $artist)
    {
        // Authorization: Ensure the logged-in user owns this artist profile
        if (Auth::id() !== $artist->user_id) {
            abort(403, 'Unauthorized action. You can only edit your own artist profile.');
        }
        // It's good practice to also load the user relationship if needed in the edit form
        $artist->load('user');
        return view('artists.edit', compact('artist')); // Needs artists.edit.blade.php
    }

    /**
     * Update the specified artist profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Artist $artist)
    {
        // Authorization: Ensure the logged-in user owns this artist profile
        if (Auth::id() !== $artist->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'portfolio_link' => 'nullable|url|max:255',
            // User details (name, username, bio, profile_picture) are edited via UserController
            // Admin might handle 'is_verified' and 'rating'
        ]);

        $artist->portfolio_link = $validatedData['portfolio_link'] ?? null;
        $artist->save();

        return redirect()->route('artists.show', $artist->id)->with('success', 'Artist profile updated successfully!');
    }

    public function listArtistOrders()
    {
        $user = Auth::user();
        if (!$user->artist) {
            // Jika pengguna tidak memiliki profil artis, mungkin redirect atau tampilkan pesan.
            // Untuk saat ini, kita bisa redirect ke home atau halaman pembuatan profil artis.
            return redirect()->route('artists.create')->with('info', 'Buat profil seniman untuk mengelola pesanan.');
        }

        // Ambil komisi milik artis yang memiliki order berbayar dan statusnya relevan
        $commissionsWithOrders = Commission::where('user_id', $user->id)
                                    ->whereHas('orders', function ($query) {
                                        $query->where('status', 'paid'); // Hanya order yang sudah dibayar
                                    })
                                    ->whereIn('status', [
                                        'ordered_pending_artist_action',
                                        'artist_accepted',
                                        'in_progress',
                                        'submitted_for_client_review',
                                        'needs_revision'
                                    ])
                                    ->with(['orders' => function($query){
                                        $query->where('status', 'paid')->with('user')->latest(); // Ambil order terbaru yang paid & user (klien) nya
                                    }])
                                    ->orderBy('updated_at', 'desc')
                                    ->paginate(10);

        return view('artists.orders.index', compact('commissionsWithOrders'));
    }

    public function showArtistOrderDetails(Commission $commission)
    {
        $user = Auth::user();
        // Otorisasi: Pastikan komisi ini milik artis yang login
        if (!$user->artist || $commission->user_id !== $user->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Ambil order terkait yang sudah dibayar (asumsi satu order aktif per komisi untuk saat ini)
        // dan pastikan order tersebut memang ada dan untuk komisi ini.
        $order = $commission->orders()->where('status', 'paid')->with('user')->first();

        if (!$order) {
            return redirect()->route('artist.orders.index')->with('error', 'Detail pesanan tidak ditemukan untuk komisi ini.');
        }

        // Tentukan aksi yang diizinkan berdasarkan status komisi saat ini
        $allowedActions = [];
        switch ($commission->status) {
            case 'ordered_pending_artist_action':
                $allowedActions['Terima & Mulai Kerjakan'] = 'artist_accepted'; // Atau langsung 'in_progress'
                // $allowedActions['Tolak Pesanan'] = 'cancelled_by_artist'; // Perlu pertimbangan refund
                break;
            case 'artist_accepted':
                // $allowedActions['Mulai Pengerjaan'] = 'in_progress'; // Jika 'artist_accepted' adalah langkah terpisah
                $allowedActions['Kirim untuk Review Klien'] = 'submitted_for_client_review';
                break;
            case 'in_progress':
                $allowedActions['Kirim untuk Review Klien'] = 'submitted_for_client_review';
                break;
            case 'needs_revision':
                $allowedActions['Kirim Ulang untuk Review'] = 'submitted_for_client_review';
                break;
            // Tidak ada aksi default untuk 'submitted_for_client_review' atau 'completed' dari sisi seniman di sini
        }

        return view('artists.orders.show', compact('commission', 'order', 'allowedActions'));
    }

    public function updateArtistOrderStatus(Request $request, Commission $commission)
    {
        $user = Auth::user();
        // Otorisasi: Pastikan komisi ini milik artis yang login
        if (!$user->artist || $commission->user_id !== $user->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $newStatus = $request->input('new_status');
        $deliveryLink = $request->input('delivery_link');

        // Validasi status baru yang diizinkan
        // Ini bisa lebih ketat dengan memeriksa transisi status yang valid
        $validStatusesToSetByArtist = [
            'artist_accepted',
            'in_progress',
            'submitted_for_client_review',
            // 'completed', // 'completed' idealnya di-trigger oleh klien atau admin setelah approval
            // 'cancelled_by_artist', // Perlu logika tambahan
        ];

        if (!in_array($newStatus, $validStatusesToSetByArtist)) {
            return back()->with('error', 'Status baru tidak valid atau aksi tidak diizinkan.');
        }
        
        // Logika spesifik berdasarkan transisi status
        if ($newStatus === 'submitted_for_client_review') {
            $request->validate([
                'delivery_link' => 'required|url|max:2048',
            ],[
                'delivery_link.required' => 'Link hasil karya wajib diisi saat mengirim untuk review.',
                'delivery_link.url' => 'Link hasil karya harus berupa URL yang valid.',
            ]);

            $order = $commission->orders()->where('status', 'paid')->first();
            if ($order) {
                $order->delivery_link = $deliveryLink;
                $order->save();
            } else {
                return back()->with('error', 'Pesanan terkait tidak ditemukan untuk menyimpan link pengiriman.');
            }
        }
        
        // Contoh sederhana: langsung update status komisi
        // Dalam aplikasi nyata, Anda mungkin ingin menambahkan lebih banyak pemeriksaan,
        // misalnya, apakah transisi status ini valid dari status saat ini.
        // Contoh: if ($commission->status === 'ordered_pending_artist_action' && $newStatus === 'artist_accepted') { ... }

        $commission->status = $newStatus;
        $commission->save();

        // TODO: Kirim notifikasi ke klien jika relevan (misalnya, saat 'submitted_for_client_review')

        return redirect()->route('artist.orders.show', $commission->id)->with('success', 'Status komisi berhasil diperbarui.');
    }
}
