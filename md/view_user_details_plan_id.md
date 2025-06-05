# Rencana Detail Implementasi Fitur "View User Details"

Dokumen ini merinci langkah-langkah untuk mengimplementasikan fitur "View User Details" di panel admin.

## Part A: Laravel Backend API Endpoint

1.  **Aktifkan Rute API di [`routes/api.php`](routes/api.php:1):**
    *   **Aksi:** Hapus komentar pada definisi rute yang sudah ada untuk mengambil detail satu pengguna. Ini akan mengaktifkan endpoint `GET /api/v1/admin/users/{user}`.
    *   **File:** [`routes/api.php`](routes/api.php:1)
    *   **Perubahan Spesifik (baris 28):**
        ```php
        // Dari:
        // Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        // Menjadi:
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        ```
        Dan sesuaikan komentar pada baris 27:
        ```php
        // Dari:
        // Add other admin user routes here later (e.g., show, update, store, destroy)
        // Menjadi:
        // Add other admin user routes here later (e.g., update, store, destroy)
        ```

2.  **Aktifkan Metode Controller di [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1):**
    *   **Aksi:** Hapus komentar pada metode `show(User $user)` yang sudah ada. Metode ini menggunakan *route model binding* untuk mengambil pengguna dan mengembalikannya dalam format `UserResource`.
    *   **File:** [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1)
    *   **Perubahan Spesifik (baris 42-45):**
        ```php
        // Dari:
        // public function show(User $user)
        // {
        //     return new UserResource($user);
        // }
        // Menjadi:
        public function show(User $user)
        {
            return new UserResource($user);
        }
        ```

3.  **Resource Data Pengguna ([`app/Http/Resources/Admin/UserResource.php`](app/Http/Resources/Admin/UserResource.php:1)):**
    *   **Status:** Terverifikasi. Resource yang ada sudah sesuai dan menyediakan detail pengguna yang diperlukan. Tidak ada perubahan yang diperlukan untuk file ini terkait fitur ini.

## Part B: Halaman Frontend Next.js (`admin-panel`)

1.  **Implementasi Komponen Halaman [`admin-panel/app/admin/users/[id]/page.tsx`](admin-panel/app/admin/users/[id]/page.tsx:1):**
    *   **Tujuan:** Menampilkan detail satu pengguna yang diambil dari API Laravel.
    *   **Tipe:** Ini akan menjadi *Client Component* (tambahkan `'use client';` di bagian atas).
    *   **Fungsionalitas Inti:**
        *   Ekstrak `id` pengguna dari parameter URL (misalnya, menggunakan `useParams` dari `next/navigation`).
        *   Gunakan *hook* `useEffect` untuk mengambil data pengguna saat komponen dimuat atau jika `id` berubah.
        *   Manfaatkan `apiClient` (dari [`admin-panel/lib/apiClient.ts`](admin-panel/lib/apiClient.ts:1)) untuk membuat permintaan `GET` ke endpoint `/api/v1/admin/users/{id}`.
        *   Implementasikan manajemen state untuk status *loading* (misalnya, menampilkan *spinner*) dan penanganan error (misalnya, menampilkan pesan error jika pengguna tidak ditemukan atau terjadi error API).
        *   Tampilkan detail pengguna yang diambil. Kolom yang ditampilkan harus selaras dengan yang disediakan oleh `UserResource` (misalnya, Nama, Email, Username, Role, Bio, Status Aktif, Tanggal Bergabung, Terakhir Diperbarui).
        *   Susun informasi dengan jelas. Pertimbangkan penggunaan komponen seperti Card, Definition List, atau Badge dari pustaka UI Anda (misalnya, Shadcn UI) untuk tampilan yang lebih baik.
        *   Sertakan tautan "Kembali ke Daftar Pengguna" (misalnya, ke `/admin/users`).
        *   Opsional, tampilkan foto profil pengguna jika tersedia.

## Diagram Alur Data (Mermaid)

```mermaid
sequenceDiagram
    participant Client (Next.js Page: admin-panel/app/admin/users/[id]/page.tsx)
    participant NextServer (Next.js Routing/SSR)
    participant LaravelAPI (Laravel Backend: /api/v1/admin/users/{user})
    participant Database

    Client->>NextServer: Navigasi ke /admin/users/{id}
    NextServer-->>Client: Menyajikan shell komponen halaman
    Client (useEffect)->>LaravelAPI: GET /api/v1/admin/users/{id} (via apiClient)
    LaravelAPI->>LaravelAPI: Route model binding untuk {user}
    LaravelAPI->>Database: Ambil User dengan id = {id}
    Database-->>LaravelAPI: Record pengguna
    LaravelAPI->>LaravelAPI: Transformasi record User dengan UserResource
    LaravelAPI-->>Client: Respons JSON (detail Pengguna)
    Client->>Client: Atur state dengan data pengguna yang diambil
    Client->>Client: Render detail pengguna di halaman