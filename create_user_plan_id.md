# Rencana Detail Implementasi Fitur "Create User"

Dokumen ini merinci langkah-langkah untuk mengimplementasikan fitur "Create User" di panel admin.

## A. Backend Laravel

1.  **Definisi Rute API Baru:**
    *   Tambahkan rute `POST` ke [`routes/api.php`](routes/api.php:1) untuk menangani pembuatan pengguna baru:
        *   `POST /users` (akan menjadi `/api/v1/admin/users` setelah prefix grup).
        *   Rute ini akan diarahkan ke metode `store` di `AdminUserController`.

2.  **Implementasi Metode Controller (`store`):**
    *   Tambahkan metode `store(Request $request)` pada controller [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1).
    *   **Validasi Input:**
        ```php
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed', // Membutuhkan 'password_confirmation'
            'role' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::in(['admin', 'user', 'artist']),
            ],
            'bio' => 'nullable|string|max:1000', // Sesuaikan max length jika perlu
        ]);
        ```
    *   **Logika Pembuatan Pengguna:**
        ```php
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'username' => $validatedData['username'],
            'password' => \Illuminate\Support\Facades\Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'bio' => $validatedData['bio'] ?? null,
            'email_verified_at' => now(), // Pengguna baru langsung aktif/terverifikasi
        ]);
        ```
    *   **Respons Sukses:**
        ```php
        return new \App\Http\Resources\Admin\UserResource($user); 
        // Defaultnya akan mengembalikan status 201 jika menggunakan create() pada resource controller, 
        // atau bisa secara eksplisit: return (new UserResource($user))->response()->setStatusCode(201);
        ```

## B. Frontend Next.js (`admin-panel`)

1.  **Pembuatan Komponen Halaman Create (`admin-panel/app/admin/users/create/page.tsx`):**
    *   Ini akan menjadi *Client Component* (`'use client';`).
    *   **State Management:**
        *   `formData` untuk semua field: `name`, `email`, `username`, `password`, `password_confirmation`, `role`, `bio`.
        *   `submitting: boolean`.
        *   `errors: object | null` (untuk menyimpan error validasi per field dari backend).
        *   `availableRoles: string[]` (misalnya, `['admin', 'user', 'artist']`).
    *   **Formulir Pembuatan Pengguna:**
        *   Gunakan komponen `Input` untuk `name`, `email`, `username`, `password`, `password_confirmation`.
        *   Gunakan komponen `Textarea` untuk `bio`.
        *   Gunakan komponen `Select` untuk `role`.
        *   Tampilkan pesan error validasi di bawah masing-masing field jika ada.
    *   **Fungsi `handleChange`:** Untuk memperbarui state `formData`.
    *   **Fungsi `handleSubmit`:**
        *   Set `submitting` ke `true`.
        *   Kirim permintaan `POST` ke `/api-proxy/api/v1/admin/users` dengan `formData`.
        *   **Penanganan Sukses:** Tampilkan notifikasi `toast` sukses, reset form, dan alihkan ke halaman daftar pengguna (`/admin/users`) atau detail pengguna yang baru dibuat.
        *   **Penanganan Error Validasi (422):** Ambil `err.response.data.errors` dan set ke state `errors` untuk ditampilkan di form. Tampilkan `toast` error umum.
        *   **Penanganan Error Lain:** Tampilkan `toast` error umum.
        *   Set `submitting` ke `false` di `finally`.
    *   **Navigasi:**
        *   Tombol "Kembali ke Daftar Pengguna" yang mengarah ke `/admin/users`.
        *   Tombol "Simpan Pengguna" (tipe `submit`, dengan status `disabled` saat `submitting`).

2.  **Tambahkan Tombol "Create User" di Halaman Daftar Pengguna:**
    *   Modifikasi [`admin-panel/app/admin/users/page.tsx`](admin-panel/app/admin/users/page.tsx:1) untuk menambahkan tombol (misalnya, di atas tabel pengguna) yang mengarah ke `/admin/users/create`.

## Diagram Alur Data (Mermaid) untuk Proses Create User

```mermaid
sequenceDiagram
    participant Client (Next.js Page: admin-panel/app/admin/users/create/page.tsx)
    participant LaravelAPI (Laravel Backend: POST /api/v1/admin/users)
    participant Database

    Client->>Client: Admin mengisi form (name, email, username, password, role, bio) dan submit
    Client->>LaravelAPI: POST /api-proxy/api/v1/admin/users (dengan data form)
    LaravelAPI->>LaravelAPI: Validasi data request
    alt Data Valid
        LaravelAPI->>Database: Create new User record (password di-hash, email_verified_at = now())
        Database-->>LaravelAPI: User record baru (dengan ID)
        LaravelAPI->>LaravelAPI: Transformasi User record dengan UserResource
        LaravelAPI-->>Client: JSON response (User details baru, status 201 Created)
        Client->>Client: Tampilkan notifikasi sukses
        Client->>Client: Reset form
        Client->>Client: Redirect ke halaman daftar pengguna atau detail pengguna baru
    else Data Tidak Valid (misal: email sudah ada, password tidak cocok)
        LaravelAPI-->>Client: JSON response (Error validasi, status 422 Unprocessable Entity, dengan detail error per field)
        Client->>Client: Tampilkan pesan error validasi pada masing-masing field di form
        Client->>Client: Tampilkan notifikasi error umum
    end