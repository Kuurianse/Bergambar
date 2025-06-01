@extends('layouts.app')
<div class="container mt-5">
        <h2 class="text-black">Edit Profil</h2>

        <!-- Form Edit Profil -->
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT') <!-- Menggunakan method PUT untuk update -->

    <!-- Username -->
    <div class="mb-3">
        <label for="username" class="text-black p-2">Username</label>
        <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $user->username) }}" required>
    </div>

    <!-- Name -->
    <div class="mb-3">
        <label for="name" class="text-black p-2">Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="text-black p-2">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>

    <!-- Password (Opsional) -->
    <div class="mb-3" >
        <label for="password" class="text-black p-2">Password (Biarkan kosong jika tidak ingin mengubah)</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <!-- Bio (Optional) -->
    <div class="mb-3">
        <label for="bio" class="text-black p-2">Bio</label>
        <textarea name="bio" id="bio" class="form-control">{{ old('bio', $user->bio) }}</textarea>
    </div>

    <!-- Profile Picture -->
    <div class="mb-3">
        <label for="profile_picture" class="text-black p-2">Profile Picture</label>
        <input type="file" name="profile_picture" id="profile_picture" class="form-control">

        @if($user->profile_picture)
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" style="max-width: 100px; margin-top: 10px;">
        @endif
    </div>

    <!-- Submit -->
    <button type="submit" class="btn edit text-black p-2 fs-6 float-end">Update</button>
</form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
@endsection
