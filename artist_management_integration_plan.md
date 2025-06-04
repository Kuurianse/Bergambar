## Rencana Detail Integrasi (Diperbarui): Artist Management & Penyesuaian User Management

Modul ini akan memungkinkan admin untuk:
1.  Mempromosikan `User` menjadi `Artist` melalui halaman detail pengguna.
2.  Melihat daftar semua `Artist`.
3.  Mengedit detail `Artist` (termasuk `portfolio_link`, `rating`, dan `is_verified`).
4.  Mengelola status verifikasi `Artist`.

```mermaid
graph TD
    subgraph User Management Module (Penyesuaian)
        direction LR
        UM1[Admin di Halaman User Detail] --> UM2{User Belum Jadi Artis?};
        UM2 -- Ya --> UM3[Tampilkan Tombol "Promote to Artist"];
        UM2 -- Tidak --> UM4[Tampilkan Info Artis / Link ke Edit Artis];
        UM3 --> UM5[Admin Klik "Promote to Artist"];
        UM5 --> UM6[Modal/Form Input Portfolio Link];
        UM6 --> UM7{Next.js Panggil API Laravel: UserController@promoteToArtist};
        UM7 --> UM8[Laravel: Buat record Artist baru];
        UM8 --> UM9[Next.js: Tampilkan Notifikasi Sukses & Update UI];
    end

    subgraph Artist Management Module (Fitur Baru)
        direction TB
        AM_A[Admin Buka Halaman Daftar Artis di Next.js] --> AM_B{Next.js Panggil API Laravel: ArtistController@index};
        AM_B --> AM_C[Laravel API: Ambil data Artis + User terkait (Paginated)];
        AM_C --> AM_D[Format data menggunakan ArtistResource];
        AM_D --> AM_E{Next.js Terima Data Artis};
        AM_E --> AM_F[Tampilkan Daftar Artis di Tabel dengan Link Edit & Tombol Toggle Verifikasi];

        AM_F -- Klik "Edit" --> AM_G[Admin di Halaman Edit Artis: admin/artists/[id]/edit];
        AM_G --> AM_H{Next.js Panggil API Laravel: ArtistController@show untuk data awal};
        AM_H --> AM_I[Form Edit Artis: Portfolio, Rating, Verifikasi];
        AM_I -- Submit --> AM_J{Next.js Panggil API Laravel: ArtistController@update};
        AM_J --> AM_K[Laravel: Update record Artist];
        AM_K --> AM_L[Next.js: Tampilkan Notifikasi Sukses & Redirect/Update UI];

        AM_F -- Klik "Toggle Verifikasi" --> AM_M{Next.js Panggil API Laravel: ArtistController@toggleVerification};
        AM_M --> AM_N[Laravel: Update status 'is_verified' Artis];
        AM_N --> AM_O[Next.js: Tampilkan Notifikasi Sukses & Update UI Tabel];
    end
```

### Bagian Backend (Laravel API)

#### 1. Penyesuaian pada `UserController` ([`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1))

*   **Method `promoteToArtist(Request $request, User $user)`:**
    *   Validasi input (misalnya, `portfolio_link` bisa opsional atau wajib).
    *   Cek apakah user sudah menjadi artis, jika sudah, kembalikan error atau pesan yang sesuai.
    *   Buat record baru di tabel `artists` yang berelasi dengan `$user`.
    *   Isi `portfolio_link` jika ada. `is_verified` default ke `false`, `rating` default ke `null` atau nilai awal.
    *   Kembalikan respon sukses dengan data artis yang baru dibuat (mungkin menggunakan `ArtistResource`).
*   **Update `UserResource` ([`app/Http/Resources/Admin/UserResource.php`](app/Http/Resources/Admin/UserResource.php:1)):**
    *   Sertakan informasi apakah user tersebut adalah seorang artis dan ID artisnya jika ada. Ini berguna untuk UI di halaman detail user.
    ```php
    // Contoh dalam UserResource
    'is_artist' => $this->whenLoaded('artist', fn() => true, false), // atau $this->artist()->exists()
    'artist_id' => $this->whenLoaded('artist', fn() => $this->artist->id, null),
    'artist_details' => $this->whenLoaded('artist', fn() => new ArtistResource($this->artist), null), // Opsional, jika ingin detail artis langsung
    ```
    *   Pastikan relasi `artist` di-load di `UserController@show` jika `UserResource` akan menyertakannya.

#### 2. `ArtistController` ([`app/Http/Controllers/Api/Admin/ArtistController.php`](app/Http/Controllers/Api/Admin/ArtistController.php) - BARU)

*   **Method `index()`:**
    *   Mengambil daftar `Artist` dengan *eager loading* relasi `user`.
    *   Mengimplementasikan paginasi.
    *   Mengembalikan data yang diformat melalui `ArtistResource`.
*   **Method `show(Artist $artist)`:**
    *   Mengambil satu `Artist` dengan relasi `user`.
    *   Mengembalikan data melalui `ArtistResource`. (Untuk halaman edit)
*   **Method `update(Request $request, Artist $artist)`:**
    *   Validasi input untuk `portfolio_link`, `rating`, `is_verified`.
    *   Update field-field tersebut pada instance `$artist`.
    *   Menyimpan perubahan.
    *   Mengembalikan data artis yang sudah diupdate melalui `ArtistResource`.
*   **Method `toggleVerification(Artist $artist)`:**
    *   Mengubah nilai boolean field `is_verified`.
    *   Menyimpan perubahan.
    *   Mengembalikan data artis yang sudah diupdate melalui `ArtistResource`.

#### 3. `ArtistResource` ([`app/Http/Resources/Admin/ArtistResource.php`](app/Http/Resources/Admin/ArtistResource.php) - BARU)

*   Struktur:
    ```php
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'portfolio_link' => $this->portfolio_link,
            'is_verified' => (bool) $this->is_verified,
            'rating' => $this->rating,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'user' => new UserResource($this->whenLoaded('user')), // Atau UserResource sederhana hanya dengan nama & email
        ];
    }
    ```

#### 4. Rute API ([`routes/api.php`](routes/api.php))

*   Tambahkan rute berikut dalam grup `admin` dan `v1`:
    *   `POST /users/{user}/promote-to-artist` -> `[UserController::class, 'promoteToArtist']`
    *   `GET /artists` -> `[ArtistController::class, 'index']`
    *   `GET /artists/{artist}` -> `[ArtistController::class, 'show']`
    *   `PUT /artists/{artist}` -> `[ArtistController::class, 'update']`
    *   `PUT /artists/{artist}/toggle-verification` -> `[ArtistController::class, 'toggleVerification']`

#### 5. Model `User` ([`app/Models/User.php`](app/Models/User.php:1))

*   Pastikan relasi `hasOne` atau `hasMany` (jika seorang user bisa punya multiple artist profile, tapi sepertinya `hasOne` lebih cocok) ke `Artist` sudah didefinisikan.
    ```php
    public function artist()
    {
        return $this->hasOne(Artist::class);
    }
    ```
*   **Penghapusan Cascade:** Untuk memastikan record `Artist` terhapus saat `User` dihapus:
    *   **Opsi 1 (Database Level):** Pastikan foreign key `user_id` di tabel `artists` memiliki `ON DELETE CASCADE`.
    *   **Opsi 2 (Model Event):**
        ```php
        protected static function boot()
        {
            parent::boot();

            static::deleting(function ($user) {
                if ($user->artist) {
                    $user->artist->delete();
                }
            });
        }
        ```
        Pilih salah satu metode. Database level biasanya lebih disarankan untuk integritas data.

### Bagian Frontend (Next.js - `admin-panel`)

#### 1. Penyesuaian pada Modul User Management

*   **Halaman Detail Pengguna ([`admin-panel/app/admin/users/[id]/page.tsx`](admin-panel/app/admin/users/[id]/page.tsx:1)):**
    *   Ambil data user termasuk informasi `is_artist` dan `artist_id`.
    *   Jika user belum menjadi artis, tampilkan tombol "Promote to Artist".
        *   Tombol ini akan membuka modal/dialog (Shadcn UI `Dialog`) dengan form untuk memasukkan `portfolio_link` (opsional atau wajib sesuai backend).
        *   Submit form akan memanggil API `promoteToArtist`.
    *   Jika user sudah menjadi artis, tampilkan status artis dan mungkin link ke halaman edit artis (`/admin/artists/[artist_id]/edit`).
*   **API Client ([`admin-panel/lib/apiClient.ts`](admin-panel/lib/apiClient.ts)):**
    *   Tambahkan fungsi `promoteUserToArtist(userId, data)`.
*   **Tipe ([`admin-panel/lib/types.ts`](admin-panel/lib/types.ts)):**
    *   Update tipe `User` untuk menyertakan `is_artist` (boolean) dan `artist_id` (number, opsional).

#### 2. Halaman Daftar Artis (BARU)

*   Direktori: [`admin-panel/app/admin/artists/`](admin-panel/app/admin/artists/)
*   File: [`page.tsx`](admin-panel/app/admin/artists/page.tsx)
*   Fungsionalitas:
    *   Mengambil data artis dari endpoint API Laravel (`/api-proxy/api/v1/admin/artists`).
    *   Menampilkan data dalam tabel (Shadcn UI `DataTable`) dengan paginasi.
    *   Kolom: Nama User, Email User, Link Portofolio, Rating, Status Verifikasi (misalnya, `Switch` atau `Badge`), Aksi.
    *   Aksi:
        *   Tombol/Link "Edit" yang mengarah ke `/admin/artists/[id]/edit`.
        *   Tombol/Switch "Toggle Verifikasi" yang memanggil API `toggleArtistVerification`.

#### 3. Halaman Edit Artis (BARU)

*   Direktori: [`admin-panel/app/admin/artists/[id]/edit/`](admin-panel/app/admin/artists/[id]/edit/)
*   File: [`page.tsx`](admin-panel/app/admin/artists/[id]/edit/page.tsx)
*   Fungsionalitas:
    *   Mengambil data artis spesifik menggunakan `artistId` dari URL.
    *   Menampilkan form (Shadcn UI `Form` dengan `react-hook-form` dan `zod`) untuk mengedit:
        *   `portfolio_link` (input teks)
        *   `rating` (input angka, mungkin slider atau number input)
        *   `is_verified` (checkbox atau switch)
    *   Submit form akan memanggil API `updateArtist`.
    *   Tombol "Back to Artist List".

#### 4. Komponen Artist (Opsional, jika diperlukan)

*   [`admin-panel/components/admin/artists/artist-list.tsx`](admin-panel/components/admin/artists/artist-list.tsx)
*   [`admin-panel/components/admin/artists/artist-form.tsx`](admin-panel/components/admin/artists/artist-form.tsx)

#### 5. API Client ([`admin-panel/lib/apiClient.ts`](admin-panel/lib/apiClient.ts))

*   Tambahkan fungsi:
    *   `fetchArtists(page, limit)`
    *   `fetchArtist(artistId)`
    *   `updateArtist(artistId, data)`
    *   `toggleArtistVerification(artistId)`

#### 6. Tipe ([`admin-panel/lib/types.ts`](admin-panel/lib/types.ts))

*   Definisikan tipe `Artist` dan `PaginatedArtistsResponse` seperti yang telah diuraikan di rencana sebelumnya.

#### 7. Navigasi Sidebar

*   Tambahkan link "Artists" di sidebar admin ([`admin-panel/components/layout/sidebar.tsx`](admin-panel/components/layout/sidebar.tsx) atau file serupa) yang mengarah ke `/admin/artists`.