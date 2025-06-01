**Gambaran Umum Proyek:**
Proyek ini adalah platform berbasis Laravel yang bertujuan untuk menghubungkan seniman dengan pengguna untuk pemesanan karya seni (komisi). Pengguna dapat menjelajahi komisi, berinteraksi dengan seniman, dan melakukan pemesanan. Seniman dapat memamerkan karya mereka dan mengelola layanan yang ditawarkan.

**Fitur yang Sudah Diimplementasikan (Backend sebagian besar fungsional):**
*   Autentikasi Pengguna (registrasi, login, reset kata sandi)
*   Halaman selamat datang yang menampilkan komisi
*   Fitur "Suka/Favorit" pada komisi
*   Memberikan ulasan pada komisi
*   Fungsionalitas dasar backend untuk obrolan (daftar percakapan, mengirim/menerima pesan melalui database dan event)
*   Daftar dasar seniman (pengguna yang memiliki komisi)
*   Halaman detail dasar seniman yang menampilkan komisi mereka
*   Operasi CRUD bagi pengguna untuk mengelola komisi mereka sendiri

**Fitur yang Belum Diimplementasikan, Rusak, atau Tidak Lengkap:**
*   **Kritis:** Pembuatan pesanan rusak karena properti `$fillable` yang hilang di model `Order` dan `commission_id` yang hilang di tabel `orders`.
*   Rute halaman profil pengguna tidak diimplementasikan.
*   Daftar komisi hanya menampilkan komisi pengguna yang sedang login, bukan semua komisi.
*   Routing yang salah untuk pembuatan komisi.
*   Logika untuk menampilkan detail pesanan individu keliru.
*   Ketidakcocokan skema database untuk tabel `artists` (kolom `portfolio_link`, `is_verified`, `rating` hilang) dan `services` (kolom `category_id` hilang).
*   Profil seniman tingkat lanjut (menggunakan model dan tabel `Artist` khusus untuk portofolio, verifikasi, dll.) belum dikembangkan.
*   Manajemen layanan oleh seniman (mendefinisikan layanan spesifik yang dapat dikategorikan) tidak terintegrasi.
*   Pelacakan pembayaran terperinci dan integrasi gateway pembayaran aktual tidak ada.
*   Frontend obrolan real-time (pengaturan Echo sisi klien) tidak diimplementasikan.
*   Panel admin yang komprehensif sebagian besar tidak ada.
*   Detail pesanan seperti `total_price` tidak disimpan.
*   Beberapa relasi Eloquent penting hilang di model `User`.
*   Penggunaan kolom `name` vs. `username` yang tidak konsisten untuk pengguna.

**Fitur yang Direncanakan untuk Implementasi di Masa Depan:**
*   Sistem profil seniman yang berfungsi penuh dengan manajemen portofolio dan verifikasi.
*   Sistem manajemen layanan lengkap untuk seniman, yang memungkinkan pengguna memesan layanan tertentu.
*   Sistem pembayaran yang kuat dengan integrasi gateway pembayaran dan pelacakan terperinci.
*   Fungsionalitas obrolan real-time penuh (sisi klien).
*   Panel admin yang komprehensif untuk manajemen situs.