# Rencana Detail Implementasi Fitur "Edit User Details"

Dokumen ini merinci langkah-langkah untuk mengimplementasikan fitur "Edit User Details" (fokus pada pengeditan peran pengguna) di panel admin.

## A. Backend Laravel

1.  **Modifikasi [`routes/api.php`](routes/api.php:1):**
    *   **Aksi:** Hapus komentar pada baris yang mendefinisikan rute `PUT` untuk pembaruan pengguna. Ini akan mengaktifkan endpoint `PUT /api/v1/admin/users/{user}`.
    *   **Perubahan Spesifik (baris 29):**
        ```php
        // Dari:
        // Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        // Menjadi:
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        ```

2.  **Modifikasi [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1):**
    *   **Aksi:** Tambahkan `use Illuminate\Validation\Rule;` di bagian atas file. Hapus komentar pada metode `update(Request $request, User $user)` dan implementasikan logika validasi serta pembaruan.
    *   **Implementasi Metode `update`:**
        ```php
        // ... (use statements)
        use Illuminate\Validation\Rule; // Tambahkan ini

        class UserController extends Controller
        {
            // ... (metode index dan show)

            /**
             * Update the specified resource in storage.
             *
             * @param  \Illuminate\Http\Request  $request
             * @param  \App\Models\User  $user
             * @return \App\Http\Resources\Admin\UserResource
             */
            public function update(Request $request, User $user)
            {
                $validatedData = $request->validate([
                    'role' => [
                        'required',
                        'string',
                        Rule::in(['admin', 'user', 'artist']), // Sesuaikan jika daftar peran berbeda
                    ],
                    // Tambahkan validasi untuk field lain jika akan diedit di masa depan
                    // 'name' => 'sometimes|string|max:255',
                    // 'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
                    // 'bio' => 'nullable|string',
                ]);

                $user->role = $validatedData['role'];
                // Jika field lain diizinkan untuk diedit:
                // if ($request->has('name') && isset($validatedData['name'])) {
                //     $user->name = $validatedData['name'];
                // }
                // // (dan seterusnya untuk field lain)

                $user->save();

                return new UserResource($user->fresh());
            }

            // ... (metode destroy jika ada)
        }
        ```

## B. Frontend Next.js (`admin-panel`)

1.  **Buat File Halaman Edit:**
    *   Buat file baru: [`admin-panel/app/admin/users/[id]/edit/page.tsx`](admin-panel/app/admin/users/[id]/edit/page.tsx:1).

2.  **Implementasi Komponen Halaman Edit (`admin-panel/app/admin/users/[id]/edit/page.tsx`):**
    *   Gunakan `'use client';` di awal file.
    *   **Impor Utama:** `useState`, `useEffect`, `useParams`, `useRouter` (dari `next/navigation`), `apiClient`, tipe `User` (dari `lib/types`), komponen UI dari `@/components/ui` (seperti `Button`, `Card`, `Select`, `Label`, `Input`, `Skeleton`), dan ikon dari `lucide-react`.
    *   **State Management:**
        *   `user: User | null` (untuk data pengguna awal).
        *   `formData: { role: string }` (atau lebih jika field lain ditambahkan).
        *   `loadingInitial: boolean` (untuk pengambilan data awal).
        *   `submitting: boolean` (untuk proses update).
        *   `error: string | null` (untuk pesan error).
        *   `availableRoles: string[]` (misalnya, `['admin', 'user', 'artist']`).
    *   **`useEffect` untuk Pengambilan Data Awal:**
        *   Ambil `userId` dari `params`.
        *   Jika `userId` ada, panggil `apiClient.get(\`/api-proxy/api/v1/admin/users/\${userId}\`)`.
        *   Pada sukses: `setUser(response.data.data)` dan `setFormData({ role: response.data.data.role })`.
        *   Tangani error dan status loading.
    *   **Fungsi `handleChange` (untuk `<select>` role):**
        *   `setFormData({ ...formData, role: event.target.value })`.
    *   **Fungsi `handleSubmit`:**
        *   Set `submitting` ke `true`.
        *   Panggil `apiClient.put(\`/api-proxy/api/v1/admin/users/\${userId}\`, { role: formData.role })`.
        *   Pada sukses: tampilkan notifikasi (misalnya, menggunakan `toast` dari `use-toast`), perbarui state `user` jika perlu, dan alihkan pengguna ke halaman detail (`/admin/users/[id]`).
        *   Pada error: set pesan `error`, tampilkan notifikasi error.
        *   Set `submitting` ke `false` di `finally`.
    *   **Struktur JSX:**
        *   Tombol "Kembali ke Detail Pengguna" (mengarahkan ke `/admin/users/[id]`).
        *   Card dengan judul (misalnya, "Edit User: {user?.name}").
        *   Formulir (`<form onSubmit={handleSubmit}>`):
            *   Bagian untuk "Role": `Label` dan komponen `Select` dari Shadcn UI.
                *   `<SelectTrigger>` dan `<SelectContent>`.
                *   Loop melalui `availableRoles` untuk membuat `<SelectItem>`.
                *   Nilai `Select` dikontrol oleh `formData.role`, dan `onValueChange` memanggil `handleChange`.
            *   Tombol "Simpan Perubahan" (tipe `submit`, dengan status `disabled` saat `submitting` atau `loadingInitial`).
        *   Tampilkan pesan `error` jika ada.
        *   Tampilkan komponen `Skeleton` saat `loadingInitial` untuk mengisi form.

## Diagram Alur Data (Mermaid) untuk Proses Update

```mermaid
sequenceDiagram
    participant Client (Next.js Page: admin-panel/app/admin/users/[id]/edit/page.tsx)
    participant LaravelAPI (Laravel Backend: /api/v1/admin/users/{user})
    participant Database

    Client->>Client: Pengguna mengisi form dan submit
    Client->>LaravelAPI: PUT /api-proxy/api/v1/admin/users/{id} (dengan data form: { role: "new_role" })
    LaravelAPI->>LaravelAPI: Validasi data request (role harus valid)
    alt Data Valid
        LaravelAPI->>Database: Update User record (id = {id}) set role = "new_role"
        Database-->>LaravelAPI: Konfirmasi update
        LaravelAPI->>LaravelAPI: Transformasi User record dengan UserResource
        LaravelAPI-->>Client: JSON response (User details yang sudah diupdate)
        Client->>Client: Tampilkan notifikasi sukses
        Client->>Client: Redirect ke halaman detail/daftar pengguna
    else Data Tidak Valid
        LaravelAPI-->>Client: JSON response (Error validasi, misal: 422 Unprocessable Entity)
        Client->>Client: Tampilkan pesan error validasi pada form
    end
```

Rencana ini berfokus pada pengeditan peran (role) pengguna. Penambahan field lain dapat mengikuti pola yang sama.