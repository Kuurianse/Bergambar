## Rencana Pengembangan: Modul Manajemen Pesanan (Admin Panel)

Dokumen ini menguraikan langkah-langkah untuk mengimplementasikan fungsionalitas manajemen pesanan (orders) di panel admin Next.js, menggantikan data mock dengan data dari API backend Laravel.

### I. Backend Development (Laravel API) - Jika Diperlukan

1.  **Pastikan/Buat `OrderController` untuk Admin:**
    *   **File:** `app/Http/Controllers/Api/Admin/OrderController.php`
    *   Jika belum ada, buat controller ini.
    *   **`index(Request $request)`:**
        *   Mengambil daftar semua pesanan (`Order`) dengan paginasi.
        *   Sertakan data relasi yang relevan:
            *   `user` (pemesan/customer)
            *   `commission` (komisi yang dipesan)
            *   `commission.user` (artis dari komisi tersebut)
            *   `payments` (detail pembayaran terkait pesanan) - *Opsional untuk daftar awal, bisa dimuat di detail.*
        *   Gunakan eager loading (misalnya, `Order::with(['user', 'commission.user', 'payments'])->latest()->paginate()`).
    *   **`show(Order $order)`:**
        *   Mengambil detail satu pesanan.
        *   Sertakan relasi yang sama seperti di `index`.
    *   **(Opsional) `updateStatus(Request $request, Order $order)`:**
        *   Memperbarui status pesanan (misalnya, dari 'pending' ke 'processing', 'shipped', 'cancelled').
        *   Validasi status yang diizinkan.

2.  **Buat/Verifikasi `OrderResource` untuk Admin:**
    *   **File:** `app/Http/Resources/Admin/OrderResource.php`
    *   Pastikan resource ini memformat data pesanan dengan benar, termasuk informasi dari relasi yang dibutuhkan (User, Commission, Artist, Payment status/method).
    *   Contoh fields: `id`, `order_date`, `status`, `total_price`, `customer_name`, `customer_email`, `commission_title`, `artist_name`, `payment_status`, `payment_method`.

3.  **Definisikan Rute API Admin untuk Orders:**
    *   **File:** `routes/api.php`
    *   Tambahkan rute dalam grup `admin` dan `v1`:
        *   `GET /api/v1/admin/orders` -> `OrderController@index`
        *   `GET /api/v1/admin/orders/{order}` -> `OrderController@show`
        *   `(Opsional) PUT /api/v1/admin/orders/{order}/status` -> `OrderController@updateStatus`

### II. Frontend Development (Next.js - `admin-panel`)

1.  **Perbarui/Buat Definisi Tipe:**
    *   **File:** `admin-panel/lib/types.ts`
    *   Verifikasi atau buat interface `Order` untuk mencakup semua field yang dikembalikan oleh `OrderResource`. Pertimbangkan untuk membuat sub-interface untuk `PaymentSummary` jika diperlukan.
    *   Definisikan `PaginatedOrdersResponse extends PaginatedResponse<Order>`.

2.  **Perbarui API Client:**
    *   **File:** `admin-panel/lib/apiClient.ts`
    *   Tambahkan fungsi:
        *   `fetchAdminOrders(page?: number, limit?: number, filters?: any): Promise<PaginatedOrdersResponse>`
        *   `fetchAdminOrder(orderId: number): Promise<Order>`
        *   `(Opsional) updateAdminOrderStatus(orderId: number, status: string): Promise<Order>`

3.  **Implementasi Halaman Daftar Pesanan Admin:**
    *   **File:** `admin-panel/app/admin/orders/page.tsx`
    *   Gunakan komponen client (`'use client'`).
    *   Ganti data mock dengan pengambilan data menggunakan `fetchAdminOrders`.
    *   Gunakan `DataTable` Shadcn UI dengan paginasi.
    *   **Kolom yang Disarankan (sesuai mock data awal, disesuaikan dengan `OrderResource`):**
        *   Order ID
        *   Customer (Nama Pengguna atau Email)
        *   Commission Title
        *   Artist (Nama Pengguna atau Email Artis)
        *   Status Pesanan (misalnya, 'pending', 'paid', 'processing', 'completed', 'cancelled')
        *   Status Pembayaran (misalnya, 'pending', 'paid', 'failed')
        *   Total Harga
        *   Tanggal Pesan
        *   Aksi (misalnya, tombol "Lihat Detail" mengarah ke `/admin/orders/[id]`)
    *   Implementasikan loading state dan error handling.
    *   (Opsional) Tambahkan filter berdasarkan status pesanan atau status pembayaran.

4.  **Implementasi Halaman Detail Pesanan Admin (Baru):**
    *   **File:** `admin-panel/app/admin/orders/[id]/page.tsx`
    *   Gunakan komponen client (`'use client'`).
    *   Ambil data detail pesanan menggunakan `fetchAdminOrder(orderId)`.
    *   Tampilkan semua detail relevan dari pesanan, termasuk informasi customer, detail komisi, detail artis, dan detail pembayaran (jika ada).
    *   (Opsional) Jika ada fungsionalitas update status, sediakan UI untuk itu.
    *   Sediakan tombol "Kembali ke Daftar Pesanan".

5.  **Perbarui Navigasi Sidebar:**
    *   **File:** `admin-panel/components/admin/admin-sidebar.tsx`
    *   Pastikan link navigasi "Orders" sudah ada dan mengarah ke `/admin/orders`. Jika belum ada, tambahkan.

### III. Pertimbangan Tambahan

*   **Status Pesanan vs. Status Pembayaran:** Jelaskan perbedaan dan bagaimana keduanya ditampilkan. Status pesanan bisa lebih beragam (pending, processing, shipped, delivered, cancelled) dibandingkan status pembayaran (pending, paid, failed, refunded).
*   **Aksi Admin:** Tentukan aksi apa saja yang bisa dilakukan admin pada pesanan (misalnya, lihat detail, update status, batalkan pesanan, proses refund - beberapa mungkin memerlukan endpoint API tambahan).
*   **Error Handling & Notifikasi:** Implementasikan penanganan error yang baik dan notifikasi toast untuk aksi.