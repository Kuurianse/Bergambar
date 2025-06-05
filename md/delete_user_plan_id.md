# Rencana Detail Implementasi Fitur "Delete User"

Dokumen ini merinci langkah-langkah untuk mengimplementasikan fitur "Delete User" di panel admin, termasuk dialog konfirmasi dan pencegahan penghapusan akun sendiri.

## A. Backend Laravel

1.  **Definisi Rute API Baru:**
    *   Tambahkan rute `DELETE` ke [`routes/api.php`](routes/api.php:1):
        *   `DELETE /users/{user}` (akan menjadi `/api/v1/admin/users/{user}` setelah prefix grup).
        *   Rute ini akan menggunakan *route model binding* dan diarahkan ke metode `destroy` di `AdminUserController`.

2.  **Implementasi Metode Controller (`destroy`):**
    *   Tambahkan metode `destroy(User $user)` pada controller [`app/Http/Controllers/Api/Admin/UserController.php`](app/Http/Controllers/Api/Admin/UserController.php:1).
    *   **Pencegahan Penghapusan Diri Sendiri:**
        ```php
        if (auth()->id() === $user->id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 403); // Forbidden
        }
        ```
    *   **Logika Penghapusan:**
        ```php
        $user->delete(); 
        // Asumsi hard delete. Pertimbangkan implikasi pada data terkait.
        ```
    *   **Respons Sukses:**
        ```php
        return response()->noContent(); // HTTP 204 No Content
        ```

## B. Frontend Next.js (`admin-panel`)

1.  **Penambahan Tombol "Delete":**
    *   **Di Daftar Pengguna ([`admin-panel/components/admin/users/user-list.tsx`](admin-panel/components/admin/users/user-list.tsx:1)):** Tambahkan tombol/ikon "Delete" di setiap baris pengguna. Tombol ini harus dinonaktifkan atau disembunyikan jika `user.id` sama dengan ID admin yang sedang login.
    *   **Opsional (Di Halaman Detail/Edit):** Pertimbangkan tombol "Delete" di [`admin-panel/app/admin/users/[id]/page.tsx`](admin-panel/app/admin/users/[id]/page.tsx:1) atau [`admin-panel/app/admin/users/[id]/edit/page.tsx`](admin-panel/app/admin/users/[id]/edit/page.tsx:1), juga dengan logika nonaktif/sembunyi jika itu akun sendiri.

2.  **Dialog Konfirmasi Penghapusan (`AlertDialog`):**
    *   Gunakan komponen `AlertDialog` dari Shadcn UI (`@/components/ui/alert-dialog`).
    *   **State Management:** Perlu state untuk mengontrol visibilitas dialog dan menyimpan ID pengguna yang akan dihapus.
    *   **Trigger:** Saat tombol "Delete" (yang aktif) diklik, set state untuk menampilkan dialog dan simpan ID pengguna target.
    *   **Konten Dialog:**
        *   `AlertDialogTitle`: "Konfirmasi Penghapusan" / "Are you sure?"
        *   `AlertDialogDescription`: "Tindakan ini tidak dapat diurungkan. Pengguna '[Nama Pengguna]' akan dihapus secara permanen."
        *   `AlertDialogCancel`: Tombol "Batal".
        *   `AlertDialogAction` (dengan varian `destructive`): Tombol "Hapus".

3.  **Logika Penghapusan di Frontend:**
    *   Saat tombol "Hapus" di `AlertDialogAction` diklik:
        *   Set status `submitting` atau `deleting` ke `true`.
        *   Panggil `apiClient.delete(\`/api-proxy/api/v1/admin/users/\${userIdToDelete}\`)`.
        *   **Penanganan Sukses:**
            *   Tampilkan notifikasi `toast` sukses.
            *   Perbarui daftar pengguna:
                *   Jika di halaman daftar: Ambil ulang daftar pengguna atau filter pengguna yang dihapus dari state lokal.
                *   Jika di halaman detail/edit pengguna yang dihapus: Alihkan ke `/admin/users`.
        *   **Penanganan Error:** Tampilkan notifikasi `toast` error.
        *   Set status `submitting` atau `deleting` ke `false` di `finally`.
        *   Tutup `AlertDialog`.

## Diagram Alur Data (Mermaid) untuk Proses Delete User

```mermaid
sequenceDiagram
    participant Client (Next.js: User List/Detail/Edit Page)
    participant AlertDialog (Shadcn UI)
    participant LaravelAPI (Laravel Backend: DELETE /api/v1/admin/users/{user})
    participant Database

    Client->>Client: Admin klik tombol "Delete" untuk User X (tombol aktif karena User X != admin login)
    Client->>AlertDialog: Tampilkan dialog konfirmasi "Hapus User X?"
    
    alt Admin Konfirmasi Hapus
        AlertDialog-->>Client: Admin klik "Confirm Delete"
        Client->>LaravelAPI: DELETE /api-proxy/api/v1/admin/users/{id_user_x}
        LaravelAPI->>LaravelAPI: Cek auth()->id() !== id_user_x (double check)
        LaravelAPI->>Database: Delete User record where id = {id_user_x}
        Database-->>LaravelAPI: Konfirmasi delete
        LaravelAPI-->>Client: HTTP 204 No Content (Sukses)
        Client->>Client: Tampilkan notifikasi sukses "User X berhasil dihapus"
        Client->>Client: Update UI (refresh list atau redirect)
        Client->>Client: Tutup AlertDialog
    else Admin Batal
        AlertDialog-->>Client: Admin klik "Cancel"
        Client->>Client: Tutup AlertDialog, tidak ada aksi
    end
```

Rencana ini mencakup langkah-langkah utama. Detail implementasi komponen UI dan state management akan dikerjakan saat implementasi kode.