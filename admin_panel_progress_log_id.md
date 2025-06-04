# Catatan Kemajuan Integrasi Panel Admin

Dokumen ini mencatat fitur-fitur utama dan pencapaian sejak integrasi panel admin Next.js ke dalam proyek.

## 1. Inisialisasi dan Konfigurasi Dasar Panel Admin
- **Proyek Next.js:** Membuat direktori `admin-panel` dan menginisialisasi proyek Next.js baru di dalamnya.
- **Styling & UI:** Mengkonfigurasi Tailwind CSS dan mengintegrasikan komponen Shadcn UI untuk antarmuka pengguna.
- **API Client:** Menyiapkan `admin-panel/lib/apiClient.ts` untuk memfasilitasi komunikasi dengan backend Laravel, termasuk penanganan token CSRF.

## 2. Autentikasi Pengguna (Admin)
- **Konteks Autentikasi:** Membuat `admin-panel/context/AuthContext.tsx` untuk mengelola status autentikasi pengguna secara global di panel admin.
- **Halaman Login:** Mengimplementasikan halaman login (`admin-panel/app/login/page.tsx`) dan logika untuk mengirimkan kredensial ke endpoint login Laravel (menggunakan rute web, bukan API).
- **Proses Login/Logout:**
    - Berhasil mengintegrasikan fungsionalitas login yang memvalidasi pengguna admin.
    - Mengimplementasikan fungsionalitas logout.
- **Rute Terproteksi:** Memastikan bahwa rute-rute dalam panel admin hanya dapat diakses setelah pengguna berhasil login.

## 3. Modul Manajemen Pengguna (Awal)
- **API Endpoint Daftar Pengguna (Laravel):**
    - Menambahkan route `GET /api/v1/admin/users` di [`routes/api.php`](routes/api.php:1).
    - Mengimplementasikan metode `index` pada [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1) untuk mengambil daftar pengguna dengan paginasi.
    - Menggunakan [`app/Http/Resources/Admin/UserResource.php`](app/Http/Resources/Admin/UserResource.php:1) untuk memformat data pengguna yang dikirim ke frontend.
- **Halaman Daftar Pengguna (Next.js):**
    - Membuat komponen halaman [`admin-panel/app/admin/users/page.tsx`](admin-panel/app/admin/users/page.tsx:1).
    - Mengimplementasikan pengambilan data pengguna dari API backend.
    - Menampilkan daftar pengguna dalam format tabel, termasuk informasi seperti nama, email, dan status aktif.
    - Menambahkan ikon untuk aksi "Lihat Detail" (mengarahkan ke `/admin/users/[id]`) dan "Edit Pengguna" (mengarahkan ke `/admin/users/[id]/edit`).
- **Status Aktif Pengguna:**
    - Memastikan status aktif pengguna (misalnya, berdasarkan `email_verified_at`) ditampilkan dengan benar di daftar pengguna.

## 4. Modul Manajemen Pengguna: Melihat Detail Pengguna (Selesai)
- **Implementasi Fitur "View User Details":**
    - **Backend (Laravel):**
        - Mengaktifkan route `GET /api/v1/admin/users/{user}` di [`routes/api.php`](routes/api.php:1).
        - Mengaktifkan metode `show(User $user)` di [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1).
    - **Frontend (Next.js):**
        - Membuat komponen halaman [`admin-panel/app/admin/users/[id]/page.tsx`](admin-panel/app/admin/users/[id]/page.tsx:1) untuk mengambil dan menampilkan detail satu pengguna.
        - Memperbarui definisi tipe `User` di [`admin-panel/lib/types.ts`](admin-panel/lib/types.ts:1).
        - Menginstal `lucide-react` untuk ikon.
        - Memperbaiki masalah proxy dengan memperbarui URL API call di frontend untuk menyertakan `/api-proxy/api/v1` agar sesuai dengan routing Laravel.

## 5. Modul Manajemen Pengguna: Mengedit Detail Pengguna (Selesai)
- **Implementasi Fitur "Edit User Details":**
    - **Backend (Laravel):**
        - Mengaktifkan route `PUT /api/v1/admin/users/{user}` di [`routes/api.php`](routes/api.php:1).
        - Mengimplementasikan metode `update(Request $request, User $user)` di [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1) dengan validasi untuk peran dan status aktif (`is_active`).
    - **Frontend (Next.js):**
        - Membuat komponen halaman [`admin-panel/app/admin/users/[id]/edit/page.tsx`](admin-panel/app/admin/users/[id]/edit/page.tsx:1) dengan formulir untuk mengedit peran dan status aktif pengguna.
        - Mengimplementasikan pengambilan data awal, pengiriman data, penanganan error, dan notifikasi.
- **Perbaikan Navigasi:**
    - Memperbaiki tombol "Back to User List" di halaman detail pengguna ([`admin-panel/app/admin/users/[id]/page.tsx`](admin-panel/app/admin/users/[id]/page.tsx:1)) agar selalu mengarah ke `/admin/users`.

## 6. Modul Manajemen Pengguna: Membuat Pengguna Baru (Selesai)
- **Implementasi Fitur "Create User":**
    - **Backend (Laravel):**
        - Menambahkan route `POST /api/v1/admin/users` di [`routes/api.php`](routes/api.php:1).
        - Mengimplementasikan metode `store(Request $request)` di [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1) untuk validasi dan pembuatan pengguna baru (default aktif).
        - Memastikan `email_verified_at` ada di `$fillable` pada model `User` ([`app/Models/User.php`](app/Models/User.php:1)).
    - **Frontend (Next.js):**
        - Membuat komponen halaman [`admin-panel/app/admin/users/create/page.tsx`](admin-panel/app/admin/users/create/page.tsx:1) dengan formulir untuk membuat pengguna baru.
        - Menambahkan tombol "Create New User" di halaman daftar pengguna ([`admin-panel/app/admin/users/page.tsx`](admin-panel/app/admin/users/page.tsx:1)).

## 7. Modul Manajemen Pengguna: Menghapus Pengguna (Selesai)
- **Implementasi Fitur "Delete User":**
    - **Backend (Laravel):**
        - Menambahkan route `DELETE /api/v1/admin/users/{user}` di [`routes/api.php`](routes/api.php:1).
        - Mengimplementasikan metode `destroy(User $user)` di [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1), termasuk pencegahan penghapusan akun sendiri.
    - **Frontend (Next.js):**
        - Menambahkan tombol "Delete" dengan ikon di komponen daftar pengguna ([`admin-panel/components/admin/users/user-list.tsx`](admin-panel/components/admin/users/user-list.tsx:1)).
        - Tombol "Delete" dinonaktifkan untuk akun admin yang sedang login.
        - Mengimplementasikan `AlertDialog` untuk konfirmasi penghapusan.
        - Memanggil API backend untuk menghapus pengguna, menampilkan notifikasi `toast`, dan memperbarui daftar pengguna secara otomatis.
## 8. Modul Manajemen Artis (Selesai)
- **Implementasi Fitur "Promote User to Artist":**
    - **Backend (Laravel):**
        - Menambahkan metode `promoteToArtist` di [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1) untuk membuat record `Artist` baru terkait `User`.
        - Memperbarui [`app/Http/Resources/Admin/UserResource.php`](app/Http/Resources/Admin/UserResource.php:1) untuk menyertakan `is_artist` dan `artist_id`.
        - Menambahkan route `POST /api/v1/admin/users/{user}/promote-to-artist` di [`routes/api.php`](routes/api.php:1).
        - Memastikan model `User` ([`app/Models/User.php`](app/Models/User.php:1)) memiliki relasi `hasOne` ke `Artist` dan menangani cascade delete.
    - **Frontend (Next.js):**
        - Memperbarui tipe `User` di [`admin-panel/lib/types.ts`](admin-panel/lib/types.ts:1) untuk menyertakan `is_artist` dan `artist_id`.
        - Menambahkan fungsi `promoteUserToArtist` di [`admin-panel/lib/apiClient.ts`](admin-panel/lib/apiClient.ts:1).
        - Memodifikasi halaman detail pengguna ([`admin-panel/app/admin/users/[id]/page.tsx`](admin-panel/app/admin/users/[id]/page.tsx:1)) untuk menampilkan tombol "Promote to Artist" (dengan dialog input portfolio) atau link ke profil artis.
- **Implementasi Fitur Daftar & Edit Artis:**
    - **Backend (Laravel):**
        - Membuat [`app/Http/Controllers/Api/Admin/ArtistController.php`](app/Http/Controllers/Api/Admin/ArtistController.php:1) dengan metode `index`, `show`, `update`, dan `toggleVerification`.
        - Membuat [`app/Http/Resources/Admin/ArtistResource.php`](app/Http/Resources/Admin/ArtistResource.php:1) untuk memformat data artis.
        - Menambahkan rute API terkait di [`routes/api.php`](routes/api.php:1) untuk manajemen artis.
    - **Frontend (Next.js):**
        - Mendefinisikan tipe `Artist` dan `PaginatedArtistsResponse` di [`admin-panel/lib/types.ts`](admin-panel/lib/types.ts:1).
        - Menambahkan fungsi `fetchArtists`, `fetchArtist`, `updateArtist`, `toggleArtistVerification` di [`admin-panel/lib/apiClient.ts`](admin-panel/lib/apiClient.ts:1).
        - Membuat halaman daftar artis ([`admin-panel/app/admin/artists/page.tsx`](admin-panel/app/admin/artists/page.tsx:1)) dengan tabel, paginasi, link edit, dan switch toggle verifikasi.
        - Membuat halaman edit artis ([`admin-panel/app/admin/artists/[id]/edit/page.tsx`](admin-panel/app/admin/artists/[id]/edit/page.tsx:1)) dengan form untuk mengedit portfolio, rating, dan status verifikasi.
        - Memastikan link navigasi "Artist Management" ada di sidebar ([`admin-panel/components/admin/admin-sidebar.tsx`](admin-panel/components/admin/admin-sidebar.tsx:1)).
- **Perbaikan Bug:**
    - Memperbaiki runtime error `artist.rating.toFixed is not a function` di [`admin-panel/app/admin/artists/page.tsx`](admin-panel/app/admin/artists/page.tsx:1) dengan memastikan konversi tipe data yang benar.

## 9. Modul Manajemen Komisi (Fase 1 - Read-Only Selesai)
- **Implementasi Fitur Dasar Manajemen Komisi:**
    - **Backend (Laravel):**
        - Membuat [`app/Http/Controllers/Api/Admin/CommissionController.php`](app/Http/Controllers/Api/Admin/CommissionController.php:1) dengan metode `index` dan `show`.
        - Membuat [`app/Http/Resources/Admin/CommissionResource.php`](app/Http/Resources/Admin/CommissionResource.php:1) dan resource minimal [`app/Http/Resources/Admin/ServiceResource.php`](app/Http/Resources/Admin/ServiceResource.php:1).
        - Menambahkan rute API terkait di [`routes/api.php`](routes/api.php:1) untuk komisi.
    - **Frontend (Next.js):**
        - Memperbarui definisi tipe di [`admin-panel/lib/types.ts`](admin-panel/lib/types.ts:1) untuk `Commission`, `PaginatedCommissionsResponse`, dan `Service`.
        - Menambahkan fungsi `fetchCommissions` dan `fetchCommission` di [`admin-panel/lib/apiClient.ts`](admin-panel/lib/apiClient.ts:1).
        - Mengimplementasikan halaman daftar komisi ([`admin-panel/app/admin/commissions/page.tsx`](admin-panel/app/admin/commissions/page.tsx:1)) dengan pengambilan data, tabel, dan paginasi.
        - Membuat dan mengimplementasikan halaman detail komisi ([`admin-panel/app/admin/commissions/[id]/page.tsx`](admin-panel/app/admin/commissions/[id]/page.tsx:1)).
        - Memverifikasi bahwa link navigasi "Commission Management" sudah ada di sidebar ([`admin-panel/components/admin/admin-sidebar.tsx`](admin-panel/components/admin/admin-sidebar.tsx:1)).
- **Perbaikan Lanjutan:**
    - Mengubah simbol mata uang pada harga total menjadi Rupiah (Rp) di halaman daftar dan detail komisi.
    - Memperbaiki error hidrasi HTML (`<div>` di dalam `<p>`) pada halaman detail komisi dengan menyesuaikan komponen `DetailItem`.

## 10. Modul Manajemen Kategori (CRUD - Selesai)
- **Implementasi Fitur CRUD Kategori:**
    - **Backend (Laravel):**
        - Memperbarui model `Category` ([`app/Models/Category.php`](app/Models/Category.php:1)) dengan menambahkan `slug` ke `$fillable`.
        - Membuat `CategoryController` ([`app/Http/Controllers/Api/Admin/CategoryController.php`](app/Http/Controllers/Api/Admin/CategoryController.php:1)) dengan metode `index`, `store`, `show`, `update`, `destroy`, termasuk validasi dan auto-generasi slug.
        - Membuat `CategoryResource` ([`app/Http/Resources/Admin/CategoryResource.php`](app/Http/Resources/Admin/CategoryResource.php:1)) untuk format respons API.
        - Menambahkan rute API `Route::apiResource('categories', ...)` di [`routes/api.php`](routes/api.php:1).
        - Menambahkan kolom `slug` ke tabel `categories` melalui migrasi database baru dan menjalankan `php artisan migrate`.
    - **Frontend (Next.js - `admin-panel`):**
        - Menambahkan definisi tipe `Category` dan `PaginatedCategoriesResponse` di [`admin-panel/lib/types.ts`](admin-panel/lib/types.ts:1).
        - Menambahkan fungsi API CRUD (`fetchCategories`, `fetchCategory`, `createCategory`, `updateCategory`, `deleteCategory`) di [`admin-panel/lib/apiClient.ts`](admin-panel/lib/apiClient.ts:1).
        - Mengimplementasikan halaman daftar kategori ([`admin-panel/app/admin/categories/page.tsx`](admin-panel/app/admin/categories/page.tsx:1)) dengan `DataTable` (termasuk instalasi `@tanstack/react-table` dan pembuatan komponen `data-table.tsx`), paginasi, tombol tambah, dan aksi edit/hapus.
        - Mengimplementasikan halaman tambah kategori baru ([`admin-panel/app/admin/categories/create/page.tsx`](admin-panel/app/admin/categories/create/page.tsx:1)).
        - Mengimplementasikan halaman edit kategori ([`admin-panel/app/admin/categories/[id]/edit/page.tsx`](admin-panel/app/admin/categories/[id]/edit/page.tsx:1)).
        - Memverifikasi link "Categories" di sidebar ([`admin-panel/components/admin/admin-sidebar.tsx`](admin-panel/components/admin/admin-sidebar.tsx:1)).
    - **Penyelesaian Masalah Build & Runtime:**
        - Mengatasi masalah dependensi `peerDependencies` dengan menyesuaikan versi React ke `^18.2.0` dan `date-fns` ke `~3.6.0`.
        - Menginstal `axios` yang hilang.
        - Memastikan `pnpm` digunakan secara konsisten untuk manajemen paket di `admin-panel`.

## 11. Modul Daftar Layanan (Service Listing - Read-Only Selesai)
- **Implementasi Fitur Daftar Layanan (Read-Only):**
    - **Backend (Laravel API):**
        - Membuat `ServiceController` ([`app/Http/Controllers/Api/Admin/ServiceController.php`](app/Http/Controllers/Api/Admin/ServiceController.php:1)) dengan metode `index` untuk mengambil daftar layanan dengan paginasi dan relasi (artist, category).
        - Memperbarui `ServiceResource` ([`app/Http/Resources/Admin/ServiceResource.php`](app/Http/Resources/Admin/ServiceResource.php:1)) untuk menyertakan field yang relevan (`id`, `title`, `description`, `price`, `artist_name`, `category_name`, `created_at`).
        - Menambahkan rute API `GET /api/v1/admin/services` di [`routes/api.php`](routes/api.php:1).
    - **Frontend (Next.js - `admin-panel`):**
        - Memperbarui definisi tipe `Service` dan menambahkan `PaginatedServicesResponse` di [`admin-panel/lib/types.ts`](admin-panel/lib/types.ts:1).
        - Menambahkan fungsi `fetchServices` di [`admin-panel/lib/apiClient.ts`](admin-panel/lib/apiClient.ts:1).
        - Mengimplementasikan halaman daftar layanan ([`admin-panel/app/admin/services/page.tsx`](admin-panel/app/admin/services/page.tsx:1)) dengan `DataTable`, paginasi, dan penanganan loading/error.
        - Membuat komponen `PaginationControls` ([`admin-panel/components/admin/pagination-controls.tsx`](admin-panel/components/admin/pagination-controls.tsx:1)) untuk digunakan di halaman daftar.
        - Memverifikasi link "Services" di sidebar ([`admin-panel/components/admin/admin-sidebar.tsx`](admin-panel/components/admin/admin-sidebar.tsx:1)).
    - **Perbaikan Lanjutan & Penyelesaian Masalah:**
        - Menyesuaikan tampilan kolom (alignment, header bahasa Inggris) di halaman daftar layanan.
        - Mengimplementasikan fungsionalitas sorting client-side pada tabel layanan.
        - Memperbaiki error "Rules of Hooks" dengan memindahkan `useReactTable` ke top-level component dan memoizing props.
        - Memperbaiki error "table is undefined" di halaman Categories dengan mengupdate cara penggunaan `DataTable` agar konsisten dengan perubahan terbaru (menggunakan `useReactTable` di page component).
        - Memastikan `PaginationControls` diimpor dengan benar di halaman Categories.

## 12. Perbaikan Backend (Fase 1 - Berdasarkan `project_analysis_and_plan.md`)
- **Perbaikan Pembuatan Pesanan (Order Creation):**
    - Verifikasi: `$fillable` pada model [`app/Models/Order.php`](app/Models/Order.php:1) sudah mencakup field yang diperlukan (`user_id`, `commission_id`, `status`, `total_price`). (Sudah Sesuai)
    - Verifikasi: Metode `confirmPayment` di [`app/Http/Controllers/OrderController.php`](app/Http/Controllers/OrderController.php:1) sudah benar dalam mengambil `commission_id` dan `total_price` dari komisi terkait. (Sudah Sesuai)
- **Perbaikan Rute Profil Pengguna:**
    - Verifikasi: Metode `profile()` di [`app/Http/Controllers/UserController.php`](app/Http/Controllers/UserController.php:1) sudah ada dan berfungsi untuk menampilkan profil pengguna yang terautentikasi. (Sudah Sesuai)
- **Koreksi Skema Tabel `artists`:**
    - Verifikasi: Kolom `portfolio_link`, `is_verified`, dan `rating` sudah ada di tabel `artists` melalui migrasi [`database/migrations/2025_05_28_051000_add_details_to_artists_table.php`](database/migrations/2025_05_28_051000_add_details_to_artists_table.php:1). (Sudah Sesuai)
- **Koreksi Skema Tabel `services`:**
    - Verifikasi: Kolom `category_id` sudah ada di tabel `services` dengan foreign key constraint melalui migrasi [`database/migrations/2025_05_28_052555_add_category_id_to_services_table.php`](database/migrations/2025_05_28_052555_add_category_id_to_services_table.php:1). (Sudah Sesuai)
- **Perbaikan Daftar Komisi (`CommissionController@index`):**
    - Verifikasi: Metode `index` di [`app/Http/Controllers/CommissionController.php`](app/Http/Controllers/CommissionController.php:1) sudah menampilkan semua komisi dengan paginasi, bukan hanya milik pengguna yang login. (Sudah Sesuai)
- **Perbaikan Rute Komisi:**
    - Verifikasi: Rute `GET /commissions` di [`routes/web.php`](routes/web.php:1) sudah benar mengarah ke `CommissionController@index` dan rute `GET /commissions/create` sudah benar mengarah ke `CommissionController@create`. (Sudah Sesuai)
- **Perbaikan `OrderController@show`:**
    - Verifikasi: Metode `show(\$id)` di [`app/Http/Controllers/OrderController.php`](app/Http/Controllers/OrderController.php:1) sudah benar mengambil data `Order` berdasarkan ID Order. (Sudah Sesuai)
- **Perbaikan Tipografi/Komentar Migrasi:**
    - Verifikasi: `Schema::dropIfExists` di migrasi `commission_loves` sudah menggunakan nama tabel yang benar (`commission_loves`). (Sudah Sesuai)
    - Verifikasi: Komentar untuk kolom `username` di migrasi `users` sudah berada di baris yang benar. (Sudah Sesuai)

**(Semua item di Fase 1: Perbaikan Segera dari `project_analysis_and_plan.md` telah diverifikasi selesai).**

## 13. Penyelesaian Fungsionalitas Inti & Perbaikan Desain (Fase 2 - Berdasarkan `project_analysis_and_plan.md`)
- **Standarisasi `name` vs. `username`:**
    - Verifikasi: `UserController@store` dan `UserController@update` sudah menangani `name` dan `username`. (Sudah Sesuai)
    - Verifikasi: Formulir registrasi ([`resources/views/auth/register.blade.php`](resources/views/auth/register.blade.php:1)) dan edit profil ([`resources/views/users/edit.blade.php`](resources/views/users/edit.blade.php:1)) sudah konsisten. (Sudah Sesuai)
    - Update: Tampilan profil pengguna ([`resources/views/users/show.blade.php`](resources/views/users/show.blade.php:1)) diperbarui untuk menampilkan `name` selain `username`. (Selesai)
- **Penambahan Relasi Model yang Hilang:**
    - Verifikasi: Relasi `user()` sudah ada di model [`app/Models/Order.php`](app/Models/Order.php:1). (Sudah Sesuai)
    - Verifikasi: Relasi `orders()`, `reviews()`, `messagesSent()`, `messagesReceived()`, dan `lovedCommissions()` sudah ada di model [`app/Models/User.php`](app/Models/User.php:1). (Sudah Sesuai)
- **Penyempurnaan Proses Pesanan:**
    - Verifikasi: `OrderController@confirmPayment` sudah benar dalam mengisi `total_price` di tabel `orders`. (Sudah Sesuai - diverifikasi di Fase 1)
    - Verifikasi: View `resources/views/orders/show.blade.php` sudah menampilkan detail pesanan dari model `Order` dengan baik. (Sudah Sesuai)

**(Semua item di Fase 2: Penyelesaian Fungsionalitas Inti & Perbaikan Desain dari `project_analysis_and_plan.md` telah diverifikasi selesai).**

## 14. Pengembangan Fitur Belum Selesai/Terencana (Fase 3 - Berdasarkan `project_analysis_and_plan.md`)
- **Sistem Profil Artis Penuh:**
    - Verifikasi: Model `Artist` ([`app/Models/Artist.php`](app/Models/Artist.php:1)) dan skema tabel `artists` sudah benar (diverifikasi di Fase 1). (Sudah Sesuai)
    - Verifikasi: Metode `create`, `store`, `edit`, `update` di [`app/Http/Controllers/ArtistController.php`](app/Http/Controllers/ArtistController.php:1) sudah ada untuk mengelola profil artis (portfolio). (Sudah Sesuai)
    - Verifikasi: Metode `index` dan `show` di [`app/Http/Controllers/ArtistController.php`](app/Http/Controllers/ArtistController.php:1) sudah menggunakan model `Artist` dan menampilkan profil yang lebih kaya. (Sudah Sesuai)
    - Verifikasi: Views `resources/views/artists/create.blade.php`, `resources/views/artists/edit.blade.php`, `resources/views/artists/index.blade.php`, dan `resources/views/artists/show.blade.php` sudah ada dan fungsional untuk lingkup saat ini. (Sudah Sesuai)
    - Catatan: Fitur "Upgrade to artist" ditangani oleh alur `artists.create`. (Sesuai)
- **Manajemen Layanan oleh Seniman:**
    - Verifikasi: Tabel `services` sudah memiliki `category_id`. (Sudah Sesuai - diverifikasi di Fase 1)
    - Verifikasi: [`app/Http/Controllers/ServiceController.php`](app/Http/Controllers/ServiceController.php:1) sudah mengimplementasikan CRUD untuk seniman mengelola layanan mereka. (Sudah Sesuai)
    - Verifikasi: Manajemen Kategori dilakukan via Admin Panel (Selesai di item #10 log ini). (Sudah Sesuai)
    - Verifikasi: Relasi `service()` di [`app/Models/Commission.php`](app/Models/Commission.php:1) dan kolom `service_id` di tabel `commissions` sudah ada. (Sudah Sesuai)
    - Verifikasi: Proses pembuatan komisi di `CommissionController@create/store` sudah mendukung pemilihan layanan secara opsional. (Sudah Sesuai)
    - Update: View `resources/views/artists/show.blade.php` sudah menampilkan layanan yang ditawarkan artis. (Sudah Sesuai)
    - Update: View `resources/views/services/show.blade.php` diperbarui untuk menyertakan tombol "Pesan Layanan Ini" yang mengarahkan ke pembuatan komisi. (Selesai)
- **Pelacakan Pembayaran Detail & Integrasi Gateway (Dasar):**
    - Verifikasi: Relasi `payments()` sudah ada di model [`app/Models/Commission.php`](app/Models/Commission.php:1). (Sudah Sesuai)
    - Verifikasi: Metode `confirmPayment` di [`app/Http/Controllers/OrderController.php`](app/Http/Controllers/OrderController.php:1) sudah membuat record `Payment` secara simulasi. (Sudah Sesuai)
    - Catatan: [`app/Http/Controllers/PaymentController.php`](app/Http/Controllers/PaymentController.php:1) saat ini berisi CRUD dasar untuk admin, bukan untuk alur pembayaran pengguna atau integrasi gateway. Integrasi gateway pembayaran penuh adalah langkah berikutnya yang signifikan dan memerlukan pemilihan gateway spesifik.
- **Implementasi Obrolan Real-Time Penuh:**
    - Verifikasi: Event [`app/Events/MessageSent.php`](app/Events/MessageSent.php:1) sudah mengimplementasikan `ShouldBroadcast` dan dikonfigurasi untuk channel private. (Selesai)
    - Verifikasi: [`package.json`](package.json:1) sudah menyertakan `laravel-echo` dan `pusher-js`. (Sudah Sesuai)
    - Verifikasi: [`resources/js/bootstrap.js`](resources/js/bootstrap.js:1) sudah mengkonfigurasi Laravel Echo untuk Pusher. (Sudah Sesuai)
    - Verifikasi: View [`resources/views/chat/chat.blade.php`](resources/views/chat/chat.blade.php:1) sudah memiliki JavaScript untuk mengirim pesan via Axios dan mendengarkan broadcast message. (Sudah Sesuai)
    - **Langkah Selanjutnya oleh Pengguna:**
        - Konfigurasi driver broadcast di `.env` (misalnya, `BROADCAST_DRIVER=pusher`).
        - Isi kredensial Pusher (atau driver lain) di `.env`.
        - Pastikan `App\Providers\BroadcastServiceProvider::class` tidak dikomentari di `config/app.php`.
        - Definisikan rute autentikasi broadcast di `routes/channels.php` (misalnya, untuk `chat.{userId}`).
        - Jalankan `php artisan queue:work` jika menggunakan antrian untuk broadcast.
- **Pengembangan Panel Admin (Lanjutan):**
    - Catatan: Sebagian besar fungsionalitas panel admin Next.js telah dikembangkan (item #1-11). Namun, beberapa halaman inti masih memerlukan implementasi atau integrasi penuh.
    - **Halaman Dashboard Admin ([`admin-panel/app/admin/dashboard/page.tsx`](admin-panel/app/admin/dashboard/page.tsx:1)):** (Tertunda - Menunggu spesifikasi/implementasi)
    - **Halaman Manajemen Pesanan Admin ([`admin-panel/app/admin/orders/page.tsx`](admin-panel/app/admin/orders/page.tsx:1)):** (Tertunda - Menunggu spesifikasi/implementasi)