## Rencana Pengembangan: Modul Daftar Layanan (Service Listing - Read-Only)

Dokumen ini menguraikan langkah-langkah untuk mengimplementasikan fungsionalitas daftar layanan (read-only) di panel admin, melanjutkan dari item 5.3 rencana pengembangan admin panel yang lebih besar.

### I. Backend Development (Laravel API)

1.  **Pastikan/Buat `ServiceController`:**
    *   **File:** `app/Http/Controllers/Api/Admin/ServiceController.php`
    *   Jika belum ada, buat controller ini.
    *   **`index(Request $request)`:**
        *   Mengambil daftar layanan (services) dengan paginasi.
        *   Sertakan data relasi yang relevan seperti Artist (pembuat layanan) dan Category. Gunakan eager loading (misalnya, `Service::with(['artist.user', 'category'])->latest()->paginate()`).

2.  **Verifikasi/Perbarui `ServiceResource`:**
    *   **File:** `app/Http/Resources/Admin/ServiceResource.php`
    *   Pastikan resource ini memformat data layanan dengan benar, termasuk informasi dari relasi Artist dan Category.
        *   Contoh fields: `id`, `title`, `description`, `price`, `artist_name`, `category_name`, `created_at`.

3.  **Definisikan Rute API:**
    *   **File:** `routes/api.php`
    *   Tambahkan rute `GET /api/v1/admin/services` yang mengarah ke `ServiceController@index` dalam grup `admin` dan `v1`.

### II. Frontend Development (Next.js - `admin-panel`)

1.  **Perbarui Definisi Tipe (jika perlu):**
    *   **File:** `admin-panel/lib/types.ts`
    *   Verifikasi atau perbarui interface `Service` untuk mencakup semua field yang dikembalikan oleh `ServiceResource` (misalnya, `artist_name`, `category_name`).
    *   Definisikan `PaginatedServicesResponse extends PaginatedResponse<Service>`.

2.  **Perbarui API Client:**
    *   **File:** `admin-panel/lib/apiClient.ts`
    *   Tambahkan fungsi:
        *   `fetchServices(page?: number, limit?: number): Promise<PaginatedServicesResponse>`

3.  **Implementasi Halaman Daftar Layanan:**
    *   **File:** `admin-panel/app/admin/services/page.tsx`
    *   Gunakan komponen client (`'use client'`).
    *   Ambil data layanan menggunakan `fetchServices`.
    *   Tampilkan data dalam `DataTable` Shadcn UI dengan paginasi.
    *   **Kolom yang Disarankan:** ID, Judul Layanan, Artis, Kategori, Harga, Tanggal Dibuat.
    *   (Opsional) Tambahkan filter atau pencarian dasar.

4.  **Perbarui Navigasi Sidebar:**
    *   **File:** `admin-panel/components/admin/admin-sidebar.tsx`
    *   Pastikan link navigasi "Services" sudah ada dan mengarah ke `/admin/services`. Jika belum ada, tambahkan.

### III. Pertimbangan Tambahan

*   **Error Handling:** Implementasikan penanganan error yang baik di frontend saat mengambil data.
*   **Loading State:** Tampilkan indikator loading saat data sedang diambil.