<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Review;
use App\Models\Service; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Illuminate\Validation\Rule; // Potentially for more complex rules if needed

class CommissionController extends Controller
{
    // Menampilkan semua commission
    public function index()
    {
        // Memuat data commission beserta relasi user
        $commissions = Commission::with('user')->latest()->paginate(9); // Added latest() and paginate()
        // Memuat data commission berdasarkan user yang sedang login - This line was removed to show all commissions
        // $commissions = Commission::where('user_id', Auth::id())->get();
        return view('commissions.index', compact('commissions'));
    }

    // Menampilkan form untuk menambah commission
    public function create()
    {
        $services = collect(); // Default to empty collection
        $user = Auth::user();
        // Ensure user has an artist profile to offer services to link commissions to
        if ($user && $user->artist) {
            $services = Service::where('artist_id', $user->artist->id)
                                ->where('availability_status', true) // Only available services
                                ->orderBy('title')
                                ->get();
        }
        return view('commissions.create', compact('services'));
    }

    // Menyimpan commission baru ke database
    public function store(Request $request)
    {
        $user = Auth::user();
        $artistId = $user && $user->artist ? $user->artist->id : null;

        $validatedData = $request->validate([
            // Hapus 'status' dari validasi jika kamu tidak mengirimnya dari form
            // atau atur default di sini jika perlu
            'title' => 'required|string|max:255', // <-- Tambahkan validasi untuk title
            'total_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Hapus 'service_id' dari validasi jika kamu tidak mengirimnya dari form
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('commissions', 'public');
            $validatedData['image'] = $imagePath;
        }

        $validatedData['user_id'] = $user->id; // The user creating the commission is the artist

        // Set nilai default untuk 'status' jika kamu menghapusnya dari form
        $validatedData['status'] = 'pending'; // Contoh: set default ke 'pending'

        // Jika kamu ingin service_id bisa kosong secara default atau tidak ada hubungannya
        // kamu bisa hapus baris ini, atau pastikan nilainya null jika tidak ada di request
        // $validatedData['service_id'] = $request->input('service_id'); // Jika kamu ingin mengirimnya sebagai null

        Commission::create($validatedData);

        return redirect()->route('commissions.index')->with('success', 'Commission berhasil ditambahkan!');
    }

    // public function store(Request $request)
    // {
    //     $user = Auth::user();
    //     $artistId = $user && $user->artist ? $user->artist->id : null;

    //     $validatedData = $request->validate([
    //         'status' => 'required|string|in:pending,accepted,completed',
    //         'total_price' => 'required|numeric|min:0',
    //         'description' => 'required|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'service_id' => [
    //             'nullable',
    //             'exists:services,id',
    //             function ($attribute, $value, $fail) use ($artistId, $user) {
    //                 if ($value) { // Only validate if service_id is provided
    //                     if (!$user || !$user->artist) {
    //                         $fail('You must have an artist profile to link a service.');
    //                         return;
    //                     }
    //                     $service = Service::find($value);
    //                     if (!$service || $service->artist_id !== $artistId) {
    //                         $fail('The selected service is invalid or does not belong to you.');
    //                     }
    //                 }
    //             },
    //         ],
    //     ]);

    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('commissions', 'public');
    //         $validatedData['image'] = $imagePath;
    //     }

    //     $validatedData['user_id'] = $user->id; // The user creating the commission is the artist

    //     Commission::create($validatedData);

    //     return redirect()->route('commissions.index')->with('success', 'Commission berhasil ditambahkan!');
    // }

    // Menampilkan detail commission
    public function show($id)
    {
        $commission = Commission::with('user')->findOrFail($id);
        return view('commissions.show', compact('commission'));
    }

    public function edit($id)
    {
        $commission = Commission::findOrFail($id);
        $user = Auth::user();

        // Pastikan hanya user yang memiliki commission yang bisa mengedit
        if ($commission->user_id !== $user->id) {
            return redirect()->route('commissions.index')->with('error', 'Unauthorized action.');
        }

        // Karena 'Link to Existing Service' akan dihapus, $services tidak lagi diperlukan
        // Namun, jika nanti Anda ingin menambahkannya kembali, kode ini tetap berguna.
        $services = collect(); // Default to empty collection
        if ($user && $user->artist) {
            $services = Service::where('artist_id', $user->artist->id)
                                ->where('availability_status', true)
                                ->orderBy('title')
                                ->get();
        }

        return view('commissions.edit', compact('commission', 'services'));
    }

    // Mengupdate commission di database
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $artistId = $user && $user->artist ? $user->artist->id : null;

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            // Hapus 'status' dari validasi karena tidak lagi ada di form
            // 'status' => 'required|string|in:pending,accepted,completed', // Baris ini dihapus
            'total_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Hapus 'service_id' dari validasi karena tidak lagi ada di form
            // 'service_id' => [ ... ], // Blok ini dihapus
        ]);

        $commission = Commission::findOrFail($id);

        // Pastikan hanya user yang memiliki commission yang bisa mengedit
        if ($commission->user_id !== $user->id) {
            return redirect()->route('commissions.index')->with('error', 'Unauthorized action.');
        }

        // Mengelola upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Optionally, delete old image if it exists
            // if ($commission->image) {
            //     Storage::disk('public')->delete($commission->image);
            // }
            $imagePath = $request->file('image')->store('commissions', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Penting: Jika 'status' tidak lagi di-update dari form,
        // Anda mungkin ingin menghapusnya dari $validatedData jika tidak ada di request
        // atau memastikan ia memiliki nilai default jika diperlukan
        // Jika Anda ingin status tetap bisa diubah oleh user tetapi melalui cara lain (misal: tombol action)
        // maka Anda perlu implementasi logika perubahan status di tempat lain.
        // Untuk saat ini, kita biarkan statusnya tidak berubah jika tidak ada di request.
        // Jika Anda ingin selalu mengatur status ke nilai default saat update (jarang terjadi),
        // Anda bisa tambahkan: $validatedData['status'] = 'nilai_default_anda';

        // Update data commission di database
        $commission->update($validatedData);

        return redirect()->route('commissions.show', $commission->id)->with('success', 'Commission berhasil diupdate!');
    }
    
    // Store update lama
    // Menampilkan form untuk mengedit commission
    // public function edit($id)
    // {
    //     $commission = Commission::findOrFail($id);
    //     $user = Auth::user();

    //     // Pastikan hanya user yang memiliki commission yang bisa mengedit
    //     if ($commission->user_id !== $user->id) {
    //         return redirect()->route('commissions.index')->with('error', 'Unauthorized action.');
    //     }

    //     $services = collect(); // Default to empty collection
    //     // Ensure user has an artist profile to offer services to link commissions to
    //     if ($user && $user->artist) {
    //         $services = Service::where('artist_id', $user->artist->id)
    //                             ->where('availability_status', true) // Only available services
    //                             ->orderBy('title')
    //                             ->get();
    //     }

    //     return view('commissions.edit', compact('commission', 'services'));
    // }


    // // Mengupdate commission di database
    // public function update(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $artistId = $user && $user->artist ? $user->artist->id : null;

    //     $validatedData = $request->validate([
    //         'title' => 'required|string|max:255', // Assuming title is also editable
    //         'status' => 'required|string|in:pending,accepted,completed',
    //         'total_price' => 'required|numeric|min:0',
    //         'description' => 'required|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'service_id' => [
    //             'nullable',
    //             'exists:services,id',
    //             function ($attribute, $value, $fail) use ($artistId, $user) {
    //                 if ($value) { // Only validate if service_id is provided
    //                     if (!$user || !$user->artist) {
    //                         $fail('You must have an artist profile to link a service.');
    //                         return;
    //                     }
    //                     $service = Service::find($value);
    //                     if (!$service || $service->artist_id !== $artistId) {
    //                         $fail('The selected service is invalid or does not belong to you.');
    //                     }
    //                 }
    //             },
    //         ],
    //     ]);

    //     $commission = Commission::findOrFail($id);

    //     // Pastikan hanya user yang memiliki commission yang bisa mengedit
    //     if ($commission->user_id !== $user->id) {
    //         return redirect()->route('commissions.index')->with('error', 'Unauthorized action.');
    //     }

    //     // Mengelola upload gambar baru jika ada
    //     if ($request->hasFile('image')) {
    //         // Optionally, delete old image if it exists
    //         // if ($commission->image) {
    //         //     Storage::disk('public')->delete($commission->image);
    //         // }
    //         $imagePath = $request->file('image')->store('commissions', 'public');
    //         $validatedData['image'] = $imagePath;
    //     }

    //     // Update data commission di database
    //     $commission->update($validatedData);

    //     return redirect()->route('commissions.show', $commission->id)->with('success', 'Commission berhasil diupdate!');
    // }

    // Menghapus commission dari database
    public function destroy($id)
    {
        $commission = Commission::findOrFail($id);

        // Pastikan hanya user yang memiliki commission yang bisa menghapus
        if ($commission->user_id !== Auth::id()) {
            return redirect()->route('commissions.index')->with('error', 'Unauthorized action.');
        }

        // Hapus commission
        $commission->delete();

        return redirect()->route('commissions.index')->with('success', 'Commission berhasil dihapus!');
    }

    public function toggleLove($id)
    {
        $commission = Commission::findOrFail($id);
        $user = auth()->user();
    
        // Cek apakah user sudah love commission ini
        if ($commission->loves()->where('user_id', $user->id)->exists()) {
            // Jika sudah love, kurangi loved_count
            $commission->loved_count -= 1;
            $commission->save();
    
            // Hapus love dari user ini di tabel pivot
            $commission->loves()->detach($user->id);
    
            $loved = false;
        } else {
            // Jika belum love, tambahkan loved_count
            $commission->loved_count += 1;
            $commission->save();
    
            // Tambahkan love untuk user ini di tabel pivot
            $commission->loves()->attach($user->id);
    
            $loved = true;
        }
    
        // Return response dalam bentuk JSON
        return response()->json([
            'loved' => $loved,
            'loved_count' => $commission->loved_count
        ]);
    }

    public function addReview(Request $request, $id)
    {
        $request->validate([
            'review' => 'required|string|max:1000', // Increased max length for review
            'rating' => 'required|integer|min:1|max:5',
        ], [
            'review.required' => 'Ulasan tidak boleh kosong.',
            'review.string' => 'Ulasan harus berupa teks.',
            'review.max' => 'Ulasan tidak boleh lebih dari 1000 karakter.',
            'rating.required' => 'Rating tidak boleh kosong.',
            'rating.integer' => 'Rating harus berupa angka.',
            'rating.min' => 'Rating minimal adalah 1.',
            'rating.max' => 'Rating maksimal adalah 5.',
        ]);
    
        // Cari commission berdasarkan ID
        $commission = Commission::findOrFail($id);
    
        // Tambahkan review baru ke tabel reviews
        Review::create([
            'commission_id' => $commission->id,
            'user_id' => auth()->user()->id, // ID user yang memberikan review
            'review' => $request->review, // Isi review
            'rating' => $request->rating, // Simpan rating
        ]);
    
        return back()->with('success', 'Ulasan berhasil ditambahkan!');
    }
    
    
}
