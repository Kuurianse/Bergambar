## Rencana Pengembangan: Modul Manajemen Kategori (CRUD)

Dokumen ini menguraikan langkah-langkah untuk mengimplementasikan fungsionalitas Create, Read, Update, dan Delete (CRUD) untuk Kategori di panel admin, sesuai dengan item 5.2 dari rencana pengembangan admin panel yang lebih besar.

```mermaid
graph TD
    subgraph Backend (Laravel API)
        direction LR
        A1[Buat app/Http/Controllers/Api/Admin/CategoryController.php] --> A2{Implementasikan metode CRUD};
        A2 --> A3[index()];
        A2 --> A4[store(Request $request)];
        A2 --> A5[show(Category $category)];
        A2 --> A6[update(Request $request, Category $category)];
        A2 --> A7[destroy(Category $category)];
        A1 --> A8[Gunakan CategoryResource untuk respons];

        B1[Buat app/Http/Resources/Admin/CategoryResource.php] --> B2{Definisikan struktur};
        B2 --> B3[Sertakan field dari model Category: id, name, description, slug, created_at, updated_at];
        B2 --> B4[Hitung jumlah service terkait (opsional, bisa ditambahkan nanti)];

        C1[Perbarui routes/api.php] --> C2{Definisikan rute resource untuk kategori};
        C2 --> C3[Route::apiResource('categories', CategoryController::class)];

        D1[Pastikan Model app/Models/Category.php ada] --> D2{Verifikasi fillable fields};
        D2 --> D3[name, description, slug];
    end

    subgraph Frontend (Next.js - admin-panel)
        direction LR
        E1[Perbarui admin-panel/lib/types.ts] --> E2{Definisikan tipe Category};
        E2 --> E3[Interface Category: id, name, description?, slug?, created_at?, updated_at?];
        E2 --> E4[Interface PaginatedCategoriesResponse];

        F1[Perbarui admin-panel/lib/apiClient.ts] --> F2{Tambahkan fungsi API CRUD Kategori};
        F1 --> F3[fetchCategories(page, limit)];
        F1 --> F4[fetchCategory(categoryId)];
        F1 --> F5[createCategory(data)];
        F1 --> F6[updateCategory(categoryId, data)];
        F1 --> F7[deleteCategory(categoryId)];

        G1[Buat Halaman Daftar Kategori] --> G2{Lokasi: admin-panel/app/admin/categories/page.tsx};
        G2 --> G3[Ambil & tampilkan kategori dengan paginasi];
        G2 --> G4[Gunakan Shadcn UI DataTable];
        G2 --> G5[Kolom: ID, Nama, Slug, (Deskripsi singkat), Jumlah Servis (opsional), Tgl Dibuat];
        G2 --> G6[Tombol "Tambah Kategori Baru"];
        G2 --> G7[Aksi per baris: Edit, Hapus];

        H1[Buat Halaman Tambah Kategori Baru] --> H2{Lokasi: admin-panel/app/admin/categories/create/page.tsx};
        H2 --> H3[Formulir untuk input nama, deskripsi, slug (opsional, bisa digenerate)];
        H2 --> H4[Handle submit & panggil API createCategory];

        I1[Buat Halaman Edit Kategori] --> I2{Lokasi: admin-panel/app/admin/categories/[id]/edit/page.tsx};
        I2 --> I3[Formulir diisi dengan data kategori yang ada];
        I2 --> I4[Handle submit & panggil API updateCategory];

        J1[Perbarui admin-panel/components/admin/admin-sidebar.tsx] --> J2[Pastikan link "Categories" ada & aktif];
    end

    Backend --> Frontend;
```

### I. Backend Development (Laravel API)

1.  **Pastikan Model `Category` Ada dan Terkonfigurasi**
    *   **File:** `app/Models/Category.php`
    *   Verifikasi bahwa model ini ada. Jika tidak, buat modelnya.
    *   Pastikan properti `$fillable` menyertakan `name`, `description`, dan `slug`.
    *   Pertimbangkan untuk menambahkan mutator untuk membuat `slug` secara otomatis jika tidak disediakan.
    *   Relasi: `hasMany` ke `Service` (jika ingin menampilkan jumlah service).

2.  **Buat `CategoryController`**
    *   **File:** `app/Http/Controllers/Api/Admin/CategoryController.php`
    *   **`index(Request $request)`:** Mengambil daftar kategori dengan paginasi.
    *   **`store(Request $request)`:** Memvalidasi input dan membuat kategori baru.
        *   Validasi: `name` (required, unique), `description` (nullable), `slug` (nullable, unique, auto-generate jika kosong).
    *   **`show(Category $category)`:** Mengambil satu kategori.
    *   **`update(Request $request, Category $category)`:** Memvalidasi input dan memperbarui kategori.
    *   **`destroy(Category $category)`:** Menghapus kategori. Pertimbangkan apa yang terjadi pada service terkait (misalnya, cegah penghapusan jika ada service terkait, atau set category_id service menjadi null).

3.  **Buat `CategoryResource`**
    *   **File:** `app/Http/Resources/Admin/CategoryResource.php`
    *   **Struktur:**
        *   `id`, `name`, `description`, `slug`.
        *   `created_at`, `updated_at`.
        *   (Opsional) `services_count` jika di-load dengan `withCount('services')`.

4.  **Definisikan Rute API**
    *   **File:** `routes/api.php`
    *   Tambahkan rute resource untuk kategori dalam grup `admin` dan `v1`:
        *   `Route::apiResource('categories', App\Http\Controllers\Api\Admin\CategoryController::class);`

### II. Frontend Development (Next.js - `admin-panel`)

1.  **Perbarui Definisi Tipe**
    *   **File:** `admin-panel/lib/types.ts`
    *   Definisikan interface `Category`: `id`, `name`, `description?`, `slug?`, `created_at?`, `updated_at?`, `services_count?`.
    *   Definisikan `PaginatedCategoriesResponse extends PaginatedResponse<Category>`.

2.  **Perbarui API Client**
    *   **File:** `admin-panel/lib/apiClient.ts`
    *   Tambahkan fungsi:
        *   `fetchCategories(page?: number, limit?: number): Promise<PaginatedCategoriesResponse>`
        *   `fetchCategory(categoryId: number): Promise<Category>`
        *   `createCategory(data: { name: string; description?: string; slug?: string }): Promise<Category>`
        *   `updateCategory(categoryId: number, data: { name?: string; description?: string; slug?: string }): Promise<Category>`
        *   `deleteCategory(categoryId: number): Promise<void>` (atau respons sukses)

3.  **Implementasi Halaman Daftar Kategori**
    *   **File:** `admin-panel/app/admin/categories/page.tsx`
    *   Gunakan komponen client (`'use client'`).
    *   Ambil data kategori menggunakan `fetchCategories`.
    *   Tampilkan data dalam `DataTable` Shadcn UI dengan paginasi.
    *   **Kolom:** ID, Nama, Slug, Deskripsi (singkat/opsional), Jumlah Servis (jika ada), Tanggal Dibuat.
    *   Tombol "Tambah Kategori Baru" yang mengarah ke halaman create.
    *   Aksi per baris: Tombol "Edit" (mengarahkan ke `/admin/categories/[id]/edit`) dan "Hapus" (dengan dialog konfirmasi).

4.  **Implementasi Halaman Tambah Kategori Baru**
    *   **File:** `admin-panel/app/admin/categories/create/page.tsx`
    *   Formulir untuk input: `name` (required), `description` (opsional), `slug` (opsional, jika kosong bisa di-generate backend atau frontend sebelum submit).
    *   Logika untuk submit form, memanggil `createCategory`, menangani sukses (misalnya, redirect ke daftar kategori dengan pesan sukses) dan error.

5.  **Implementasi Halaman Edit Kategori**
    *   **File:** `admin-panel/app/admin/categories/[id]/edit/page.tsx`
    *   Ambil data kategori yang akan diedit menggunakan `fetchCategory`.
    *   Isi formulir dengan data yang ada.
    *   Logika untuk submit form, memanggil `updateCategory`, menangani sukses dan error.

6.  **Perbarui Navigasi Sidebar**
    *   **File:** `admin-panel/components/admin/admin-sidebar.tsx`
    *   Pastikan link navigasi "Categories" sudah ada dan mengarah ke `/admin/categories`. Jika belum ada, tambahkan.

### III. Pertimbangan Tambahan

*   **Slug Generation:** Tentukan apakah slug akan di-generate di frontend (misalnya, saat pengguna mengetik nama) atau di backend jika tidak disediakan.
*   **Error Handling & Notifikasi:** Implementasikan penanganan error yang baik dan notifikasi (misalnya, menggunakan `toast`) untuk operasi CRUD.
*   **Konfirmasi Hapus:** Selalu gunakan dialog konfirmasi sebelum menghapus kategori.
*   **Validasi Frontend:** Tambahkan validasi dasar di sisi frontend untuk pengalaman pengguna yang lebih baik, selain validasi backend.