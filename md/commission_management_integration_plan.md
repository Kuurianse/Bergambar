## Rencana Detail Integrasi: Commission Management Module

Modul ini akan memungkinkan admin untuk melihat daftar semua komisi dan detailnya. Kemampuan untuk mengelola (misalnya, menyembunyikan/menghapus) komisi bersifat opsional untuk saat ini.

```mermaid
graph TD
    A[Admin Buka Halaman Daftar Komisi di Next.js] --> B{Next.js Panggil API Laravel};
    B --> C[Laravel API: CommissionController@index];
    C --> D[Ambil data Komisi + User (Artis) + Service terkait];
    D --> E[Format data menggunakan CommissionResource];
    E --> F{Next.js Terima Data Komisi};
    F --> G[Tampilkan Daftar Komisi di Tabel dengan Link Detail];

    H[Admin Klik Link Detail Komisi] --> I[Admin di Halaman Detail Komisi: admin/commissions/[id]];
    I --> J{Next.js Panggil API Laravel: CommissionController@show};
    J --> K[Laravel API: Ambil data Komisi spesifik + relasi terkait];
    K --> L[Format data menggunakan CommissionResource];
    L --> M{Next.js Terima Data Detail Komisi};
    M --> N[Tampilkan Detail Komisi];
```

### Bagian Backend (Laravel API)

1.  **Buat `CommissionController`:**
    *   File: `app/Http/Controllers/Api/Admin/CommissionController.php`
    *   Method `index(Request $request)`:
        *   Mengambil daftar `Commission` dengan *eager loading* relasi `user` (untuk info artis), `service` (untuk info layanan terkait).
        *   Mengimplementasikan paginasi.
        *   Mempertimbangkan filter berdasarkan status atau artis jika diperlukan di masa mendatang.
        *   Mengembalikan data yang diformat melalui `CommissionResource`.
    *   Method `show(Commission $commission)`:
        *   Mengambil satu `Commission` dengan relasi `user`, `service`, `orders`, `reviews`, `payments`.
        *   Mengembalikan data melalui `CommissionResource`.
    *   *(Opsional)* Method `destroy(Commission $commission)`:
        *   Menghapus komisi (soft delete jika diimplementasikan pada model, atau hard delete).
        *   Mengembalikan respon sukses.

2.  **Buat `CommissionResource`:**
    *   File: `app/Http/Resources/Admin/CommissionResource.php`
    *   Struktur: Akan mencakup detail dari model `Commission` (`id`, `title`, `status`, `public_status`, `total_price`, `description`, `image`) dan detail dari relasi yang di-load:
        *   `user` (menggunakan `UserResource` atau sub-setnya untuk info artis: `id`, `name`, `email`).
        *   `service` (menggunakan `ServiceResource` atau sub-setnya: `id`, `name`, `category_name`).
        *   (Untuk halaman detail) `orders`, `reviews`, `payments` (menggunakan resource masing-masing jika sudah ada atau akan dibuat).

3.  **Definisikan Rute API:**
    *   File: `routes/api.php`
    *   Tambahkan rute berikut dalam grup `admin` dan `v1`:
        *   `GET /commissions` -> `[CommissionController::class, 'index']`
        *   `GET /commissions/{commission}` -> `[CommissionController::class, 'show']`
        *   *(Opsional)* `DELETE /commissions/{commission}` -> `[CommissionController::class, 'destroy']`

### Bagian Frontend (Next.js - `admin-panel`)

1.  **Buat Halaman Daftar Komisi:**
    *   Direktori: `admin-panel/app/admin/commissions/`
    *   File: `page.tsx`
    *   Fungsionalitas:
        *   Mengambil data komisi dari endpoint API Laravel (`/api-proxy/api/v1/admin/commissions`).
        *   Menampilkan data dalam tabel (Shadcn UI `DataTable`) dengan paginasi.
        *   Kolom tabel: ID, Judul, Artis (Nama User), Layanan (Nama Servis), Status (Internal & Publik), Harga, Tanggal Dibuat.
        *   Aksi: Link/Tombol "View Details" yang mengarah ke `/admin/commissions/[id]`.
        *   *(Opsional)* Tombol "Delete" jika fungsionalitasnya diimplementasikan.

2.  **Buat Halaman Detail Komisi:**
    *   Direktori: `admin-panel/app/admin/commissions/[id]/`
    *   File: `page.tsx`
    *   Fungsionalitas:
        *   Mengambil data komisi spesifik menggunakan `commissionId` dari URL.
        *   Menampilkan semua detail komisi, termasuk:
            *   Informasi dasar komisi.
            *   Detail Artis (User).
            *   Detail Layanan (Service).
            *   *(Opsional di tahap awal)* Daftar Order terkait.
            *   *(Opsional di tahap awal)* Daftar Review terkait.
            *   *(Opsional di tahap awal)* Daftar Payment terkait.
        *   Tombol "Back to Commission List".

3.  **Perbarui API Client:**
    *   File: `admin-panel/lib/apiClient.ts`
    *   Tambahkan fungsi untuk:
        *   `fetchCommissions(page, limit, filters?)`
        *   `fetchCommission(commissionId)`
        *   *(Opsional)* `deleteCommission(commissionId)`

4.  **Perbarui Definisi Tipe:**
    *   File: `admin-panel/lib/types.ts`
    *   Definisikan tipe `Commission`, `Service` (jika belum ada), `Order` (jika belum ada), `Review` (jika belum ada), `Payment` (jika belum ada).
    *   Definisikan `PaginatedCommissionsResponse`.
    ```typescript
    // Contoh di admin-panel/lib/types.ts
    export interface Commission {
      id: number;
      user_id: number;
      title: string;
      status: string; // Internal status
      public_status: string; // Accessor from Laravel
      total_price: number | string; // string from DB, number after parse
      description: string | null;
      image: string | null;
      service_id: number | null;
      created_at: string;
      updated_at: string;
      user?: User; // Artist info
      service?: Service; // Service info
      // Optional relations for detail page
      orders?: Order[];
      reviews?: Review[];
      payments?: Payment[];
    }

    export interface PaginatedCommissionsResponse extends PaginatedResponse<Commission> {}

    // Minimal Service type for now, expand as needed
    export interface Service {
        id: number;
        name: string;
        // category_name?: string; // If fetched via join or accessor
        // Add other relevant service fields
    }
    ```

5.  **Tambahkan Navigasi:**
    *   Perbarui sidebar di layout admin ([`admin-panel/components/admin/admin-sidebar.tsx`](admin-panel/components/admin/admin-sidebar.tsx:1)) untuk menyertakan link ke halaman "Commissions".

### Pertimbangan Tambahan:

*   **Status Komisi:** Model `Commission` memiliki accessor `public_status`. Pastikan ini digunakan dengan benar di frontend untuk tampilan yang sesuai bagi admin, atau tampilkan kedua status (internal dan publik) jika itu lebih informatif.
*   **Relasi:** Eager load relasi yang benar di controller Laravel untuk menghindari N+1 query. Untuk halaman daftar, mungkin hanya `user` dan `service`. Untuk halaman detail, bisa lebih banyak.
*   **Filtering & Sorting:** Untuk halaman daftar komisi, pertimbangkan untuk menambahkan kemampuan filter (berdasarkan status, artis) dan sorting di masa mendatang. Ini bisa ditambahkan sebagai peningkatan setelah fungsionalitas dasar ada.
*   **Aksi Opsional:** Keputusan untuk mengimplementasikan penghapusan atau penyembunyian komisi oleh admin bisa dibuat nanti. Fokus awal adalah pada tampilan data.