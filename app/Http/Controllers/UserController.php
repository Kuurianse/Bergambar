<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store']);
    }

    public function index()
    {
        if (Auth::user()->role !== 'admin') { // Cek apakah user adalah admin
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        // Menggunakan pagination untuk mengurangi beban query
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
    
        // Pastikan hanya user yang sedang login bisa melihat profilnya sendiri
        if (Auth::id() !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat profil ini');
        }
    
         // Mengambil commissions milik user tersebut
        $commissions = Commission::where('user_id', $user->id)->get();
    
        return view('users.show', compact('user', 'commissions'));  // Menyertakan commissions ke view
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', // Full name
            'username' => 'required|string|max:255|unique:users,username', // Username, unique
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Pastikan hanya user itu sendiri yang bisa mengedit profilnya
        if (Auth::id() !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit profil ini');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi data input
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'bio' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update data user
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->bio = $validatedData['bio'];

        // Jika ada gambar profil yang di-upload
        if ($request->hasFile('profile_picture')) {
            // Hapus gambar lama jika ada
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }

            // Simpan gambar baru
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $imagePath;
        }

        $user->save(); // Simpan perubahan

        return redirect()->route('users.show', $user->id)->with('success', 'Profil berhasil diperbarui');
    }

    public function profile()
    {
        $user = Auth::user(); // Get the authenticated user
        $commissions = Commission::where('user_id', $user->id)->get(); // Get their commissions

        // Assuming 'users.show' can be used for the authenticated user's profile view
        // or a new view 'users.profile' could be created if different layout is needed.
        // For now, let's reuse 'users.show'.
        return view('users.show', compact('user', 'commissions'));
    }
}
