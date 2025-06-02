# Platform Komisi Karya Seni Bergambar

## Deskripsi Singkat Proyek

Platform Komisi Karya Seni Bergambar adalah aplikasi web yang dibangun menggunakan Laravel 11. Tujuan utama proyek ini adalah untuk menyediakan platform yang menghubungkan seniman dengan pengguna (klien) yang ingin memesan karya seni kustom (komisi). Pengguna dapat menjelajahi karya seniman, memesan komisi, dan berinteraksi dengan seniman, sementara seniman dapat memamerkan portofolio mereka, mengelola layanan yang ditawarkan, dan menangani pesanan komisi.

## Status Proyek Saat Ini

Proyek ini memiliki struktur dasar yang fungsional untuk beberapa fitur inti. Namun, terdapat beberapa bug kritis yang perlu segera ditangani, fitur yang belum sepenuhnya terimplementasi, dan beberapa area yang memerlukan pengembangan lebih lanjut untuk mencapai fungsionalitas penuh dan pengalaman pengguna yang optimal. Rencana pengembangan terstruktur telah diidentifikasi untuk mengatasi isu-isu ini dan menambahkan fitur-fitur baru.

Dokumen analisis dan perencanaan lebih detail dapat ditemukan di:
*   [`project_analysis_and_plan.md`](project_analysis_and_plan.md)
*   [`artist_order_management_plan.md`](artist_order_management_plan.md)
*   [`fix_order_creation_plan.md`](fix_order_creation_plan.md)

## Fitur Utama (Telah Diimplementasikan atau Sebagian Besar Fungsional)

Berikut adalah fitur-fitur utama yang saat ini sudah berjalan atau fungsionalitas backend-nya sebagian besar telah ada:

*   **Autentikasi Pengguna:** Registrasi, login, dan fungsionalitas reset password standar Laravel.
*   **Halaman Sambutan:** Menampilkan daftar komisi yang tersedia untuk umum.
*   **Fitur "Suka" (Love) pada Komisi:** Pengguna dapat menyukai/membatalkan suka pada komisi, dan jumlah suka akan diperbarui.
*   **Ulasan dan Rating Komisi:** Pengguna dapat menambahkan ulasan beserta rating (1-5 bintang) untuk komisi yang telah selesai.
*   **Fungsionalitas Dasar Chat (Backend):**
    *   Melihat daftar percakapan pengguna.
    *   Menampilkan detail percakapan tertentu.
    *   Mengirim pesan (tersimpan di database dan memicu event).
*   **Daftar Seniman (Dasar):** Menampilkan pengguna yang memiliki komisi (dianggap sebagai seniman).
*   **Halaman Detail Seniman (Dasar):** Menampilkan informasi dasar seniman dan komisi yang mereka miliki.
*   **Manajemen Komisi oleh Pengguna:** Pengguna dapat membuat, melihat, mengedit, dan menghapus komisi milik mereka sendiri. Komisi dapat ditautkan ke layanan spesifik yang ditawarkan oleh seniman.
*   **Manajemen Layanan oleh Seniman:** Seniman dapat melakukan operasi CRUD (Create, Read, Update, Delete) pada layanan yang mereka tawarkan. Halaman detail publik untuk setiap layanan juga tersedia dan dapat diakses dari profil artis.
*   **Dasar Manajemen Pesanan oleh Seniman:** Seniman memiliki halaman untuk melihat daftar komisi mereka yang telah dipesan (`/artist/orders`). Mereka dapat melihat detail setiap pesanan dan melakukan aksi dasar untuk mengubah status komisi (misalnya, menerima pesanan, mengirim karya untuk review via link eksternal).

## Isu dan Bug yang Diketahui

Beberapa isu dan bug yang telah teridentifikasi dan memerlukan perhatian:

*   **Kritis: Kegagalan Pembuatan Pesanan (Order Creation Failure):** ~~Disebabkan oleh properti `$fillable` yang hilang di model `Order` dan kolom `commission_id` yang hilang di tabel `orders`. Rencana perbaikan detail terdapat di [`fix_order_creation_plan.md`](fix_order_creation_plan.md).~~ *(Status: Diasumsikan TERATASI berdasarkan analisis kode terbaru yang menunjukkan komponen yang diperlukan sudah ada).*
*   **Rute Profil Pengguna Belum Terimplementasi:** ~~Rute `/profile` belum memiliki metode `profile()` di `UserController`.~~ *(Status: TERATASI. Metode `profile()` sudah ada di `UserController`)*.
*   **Kesalahan Daftar Komisi di Halaman Indeks:** ~~Halaman indeks komisi (`/commissions`) saat ini menampilkan komisi milik pengguna yang login saja, bukan semua komisi publik. Selain itu, ada kesalahan routing dimana `GET /commissions` mengarah ke `create` bukan `index`.~~ *(Status: TERATASI. Logika `CommissionController@index` dan rute di `routes/web.php` sudah benar).*
*   **Logika Keliru pada `OrderController@show`:** ~~Metode ini keliru mengambil `Commission` berdasarkan ID, bukan `Order`.~~ *(Status: TERATASI. Metode `OrderController@show()` sudah benar mengambil `Order`)*.
*   **Ketidaksesuaian Skema Tabel `artists`:** ~~Kolom `portfolio_link`, `is_verified`, `rating` didefinisikan di model `Artist` tetapi tidak ada di migrasi tabel `artists`.~~ *(Status: TERATASI. Kolom-kolom ini telah ditambahkan oleh migrasi `2025_05_28_051000_add_details_to_artists_table.php`)*.
*   **Ketidaksesuaian Skema Tabel `services`:** ~~Kolom `category_id` untuk relasi ke kategori hilang dari tabel `services`.~~ *(Status: TERATASI. Kolom `category_id` telah ditambahkan oleh migrasi `2025_05_28_052555_add_category_id_to_services_table.php`)*.
*   **Typo Minor pada Migrasi `commission_loves`:** ~~Perlu dikoreksi pada metode `down()`.~~ *(Status: TERATASI. Metode `down()` di migrasi `2024_10_22_080834_create_commission_loves_table.php` sudah benar).*
*   **Komentar yang Salah Tempat pada Migrasi `users`**: ~~Komentar `// Menambahkan kolom 'username'` salah tempat.~~ *(Status: TERATASI. Komentar sudah berada di baris yang benar pada migrasi `2014_10_12_000000_create_users_table.php`)*.
*   **Visibilitas Status Komisi Internal:** Saat ini, status alur kerja internal komisi (seperti `ordered_pending_artist_action`) kemungkinan terlihat oleh publik secara luas. *(Status: Langkah awal PENANGANAN telah dilakukan dengan menambahkan accessor `public_status` di model `Commission` dan memperbarui view publik. Penyesuaian lebih lanjut mungkin diperlukan untuk view admin/seniman dan logika bisnis yang lebih kompleks).*

## Fitur yang Belum Selesai atau Sebagian Diimplementasikan

Fitur-fitur berikut telah dimulai namun belum sepenuhnya fungsional atau terintegrasi:

*   **Profil Seniman Tingkat Lanjut:** Model `Artist` dan tabel `artists` ada. `ArtistController` telah di-refactor untuk menggunakan model `Artist`. View `artists.index` dan `artists.show` telah diperbarui untuk menampilkan detail lebih kaya (`is_verified`, `rating` jika ada) dan alur pembuatan/pengelolaan profil artis dari halaman profil pengguna telah ditambahkan. *(Status Fitur Rating: Implementasi input rating (1-5 bintang) pada form review, validasi, dan penyimpanan rating di controller, serta penampilan rating pada daftar ulasan telah SELESAI. Pesan UI terkait juga sudah dalam Bahasa Indonesia)*. Aspek pengelolaan `is_verified` dan kalkulasi `rating` otomatis untuk seniman masih memerlukan pengembangan lebih lanjut (kemungkinan via panel admin atau sistem).
*   **Manajemen Layanan oleh Seniman:** *(Status: SELESAI)* Fungsionalitas CRUD penuh untuk layanan oleh seniman telah diimplementasikan. Seniman dapat membuat, melihat, mengedit, dan menghapus layanan mereka. Halaman detail publik untuk layanan juga tersedia dan tertaut dari profil artis. Komisi dapat ditautkan ke layanan saat pembuatan/pengeditan.
*   **Pelacakan Pembayaran Detail:** Model `Payment` dan tabel `payments` ada, namun alur pemesanan saat ini hanya menyederhanakan status order menjadi 'paid' tanpa integrasi gateway pembayaran atau penggunaan model `Payment` secara detail.
*   **Chat Real-time (Frontend):** Backend telah siap dengan event broadcasting (`MessageSent`), namun implementasi sisi klien (Laravel Echo) untuk pengalaman chat real-time belum ada.
*   **Panel Admin:** Fungsionalitas admin yang komprehensif untuk mengelola pengguna, seniman, komisi, dll., belum dikembangkan secara menyeluruh.
*   **Persistensi Detail Pesanan:** ~~`OrderController@confirmPayment` belum menyimpan `total_price` ke dalam tabel `orders`.~~ *(Status: TERATASI. Metode `confirmPayment` di `OrderController` sudah menyimpan `total_price` saat membuat order baru).*
*   **Relasi Model yang Hilang:** ~~Beberapa relasi penting (seperti `orders()`, `reviews()`, `messages()`) belum didefinisikan di model `User`.~~ *(Status: TERATASI. Relasi-relasi yang diperlukan seperti `orders()`, `reviews()`, `messagesSent()`, `messagesReceived()`, dan `lovedCommissions()` sudah ada di model `User`)*.
*   **Inkonsistensi Penggunaan `name` vs. `username`:** ~~Pada tabel `users`, perlu ada standarisasi penggunaan antara `name` dan `username`.~~ *(Status: TERATASI. `UserController@update` telah dimodifikasi untuk memungkinkan pembaruan field `name` selain `username`, menyelaraskannya dengan form registrasi dan edit yang sudah ada. Kedua field kini dapat dikelola).*

## Fitur yang Direncanakan & Pengembangan Selanjutnya

Berikut adalah garis besar fitur yang direncanakan untuk pengembangan di masa mendatang:

*   **Sistem Profil Seniman Penuh:**
    *   Memungkinkan pengguna mendaftar/ditetapkan sebagai seniman.
    *   Manajemen profil seniman yang diperluas (portofolio, bio, dll.).
    *   Proses verifikasi seniman.
*   **Manajemen Layanan Penuh oleh Seniman:**
    *   Seniman dapat mendefinisikan layanan spesifik yang ditawarkan.
    *   Pengkategorian layanan.
    *   Integrasi layanan ke dalam alur pemesanan komisi.
*   **Integrasi Gateway Pembayaran dan Pelacakan Pembayaran Detail:**
    *   Implementasi `PaymentController`.
    *   Integrasi dengan gateway pembayaran (misalnya, Midtrans, Stripe).
    *   Pembuatan record `Payment` setelah transaksi berhasil.
*   **Implementasi Penuh Chat Real-Time:**
    *   Konfigurasi Laravel Broadcasting (Pusher, Ably, atau self-hosted).
    *   Implementasi Laravel Echo di sisi klien.
*   **Pengembangan Panel Admin Komprehensif:**
    *   Area khusus untuk admin mengelola berbagai aspek platform.
*   **Alur Kerja Manajemen Pesanan untuk Seniman & Klien:** *(Status: Implementasi Dasar SELESAI untuk alur seniman; Implementasi Dasar SELESAI untuk alur review klien)*
    *   **Sisi Seniman:**
        *   Antarmuka untuk melihat daftar pesanan masuk ([`resources/views/artists/orders/index.blade.php`](resources/views/artists/orders/index.blade.php:1)) dan detail pesanan ([`resources/views/artists/orders/show.blade.php`](resources/views/artists/orders/show.blade.php:1)).
        *   Seniman dapat memperbarui status komisi (misalnya, `ordered_pending_artist_action` -> `artist_accepted` -> `in_progress` -> `submitted_for_client_review`).
        *   Pengiriman hasil karya via link eksternal (disimpan di `orders.delivery_link`) saat status `submitted_for_client_review`.
        *   Dapat melihat catatan revisi dari klien jika status `needs_revision`.
        *   Navigasi "Kelola Pesanan Saya" tersedia.
    *   **Sisi Klien:**
        *   Pada halaman detail pesanan ([`resources/views/orders/show.blade.php`](resources/views/orders/show.blade.php:1)), klien dapat melihat link hasil karya yang dikirim seniman.
        *   Klien dapat "Menyetujui Hasil Karya" (mengubah status komisi & order menjadi `completed`).
        *   Klien dapat "Minta Revisi" dengan menyertakan catatan (mengubah status komisi menjadi `needs_revision` dan menyimpan catatan di tabel `order_revisions`).
        *   Histori permintaan revisi ditampilkan kepada klien.
    *   Rencana detail untuk fitur ini terdapat di [`artist_order_management_plan.md`](artist_order_management_plan.md:1).
    *   Pembedaan status publik dan internal via accessor `public_status` di model `Commission`.
    *   *(Pengembangan Lanjutan: Notifikasi email/platform, penanganan revisi yang lebih interaktif, pembatalan pesanan, upload file langsung).*
*   **Perbaikan Bug dan Isu yang Ada:** Menyelesaikan semua bug yang teridentifikasi.
*   **Penyempurnaan UI/UX:** Meningkatkan pengalaman pengguna secara keseluruhan.

## Gambaran Model Data (ERD)

```mermaid
erDiagram
    USERS {
        int id PK
        string name
        string email UK
        string username UK
        text bio
        string profile_picture
        timestamp email_verified_at
        string password
        string remember_token
        timestamp created_at
        timestamp updated_at
        string role
    }

    ARTISTS {
        int id PK
        int user_id FK
        string portfolio_link
        boolean is_verified
        decimal rating
        timestamp created_at
        timestamp updated_at
    }

    CATEGORIES {
        int id PK
        string name
        text description
        timestamp created_at
        timestamp updated_at
    }

    SERVICES {
        int id PK
        int artist_id FK
        int category_id FK
        string title
        text description
        decimal price
        string service_type
        boolean availability_status
        timestamp created_at
        timestamp updated_at
    }

    COMMISSIONS {
        int id PK
        int user_id FK
        int service_id FK
        string status
        decimal total_price
        text description
        string image_path
        int loved_count
        timestamp created_at
        timestamp updated_at
    }

    ORDERS {
        int id PK
        int user_id FK
        int commission_id FK
        string status
        decimal total_price
        text delivery_link nullable
        timestamp created_at
        timestamp updated_at
    }

    PAYMENTS {
        int id PK
        int commission_id FK
        int order_id FK
        string payment_method
        decimal amount
        string payment_status
        timestamp payment_date
        timestamp created_at
        timestamp updated_at
    }

    REVIEWS {
        int id PK
        int commission_id FK
        int user_id FK
        text review
        tinyint rating "nullable, 1-5"
        timestamp created_at
        timestamp updated_at
    }

    MESSAGES {
        int id PK
        int sender_id FK
        int receiver_id FK
        text message
        timestamp created_at
        timestamp updated_at
    }

    COMMISSION_LOVES {
        int id PK
        int commission_id FK
        int user_id FK
        timestamp created_at
        timestamp updated_at
    }

    USERS ||--o{ ARTISTS : "has"
    USERS ||--o{ COMMISSIONS : "creates"
    USERS ||--o{ ORDERS : "places"
    USERS ||--o{ REVIEWS : "writes"
    USERS ||--o{ MESSAGES : "sends"
    USERS ||--o{ MESSAGES : "receives"
    USERS }o--o{ COMMISSION_LOVES : "loves"

    ARTISTS ||--o{ SERVICES : "offers"
    CATEGORIES ||--o{ SERVICES : "categorizes"
    SERVICES ||--o{ COMMISSIONS : "leads_to"
    COMMISSIONS ||--o{ ORDERS : "ordered_via"
    COMMISSIONS ||--o{ PAYMENTS : "has_payment"
    COMMISSIONS ||--o{ REVIEWS : "has_review"
    COMMISSIONS }o--o{ COMMISSION_LOVES : "loved_by"
    ORDERS ||--o{ PAYMENTS : "paid_via"
    ORDERS ||--o{ ORDER_REVISIONS : "has_revisions"
    USERS ||--o{ ORDER_REVISIONS : "requests_revision"

    ORDER_REVISIONS {
        int id PK
        int order_id FK
        int user_id FK
        text notes
        timestamp requested_at
        timestamp created_at
        timestamp updated_at
    }
```

## Sorotan Rencana Pengembangan

Pengembangan proyek ini akan mengikuti pendekatan bertahap seperti yang diuraikan dalam [`project_analysis_and_plan.md`](project_analysis_and_plan.md):
*   **Fase 1:** Perbaikan Segera (Bug Kritis & Isu Mendesak)
*   **Fase 2:** Penyelesaian Fungsionalitas Inti & Penanganan Isu Desain
*   **Fase 3:** Pengembangan Fitur yang Belum Selesai/Direncanakan
*   **Fase 4:** Penyempurnaan dan Pengujian

## Instalasi dan Pengaturan

1.  **Clone repository:**
    ```bash
    git clone <URL_REPOSITORY_ANDA>
    cd <NAMA_DIREKTORI_PROYEK>
    ```
2.  **Install dependensi Composer:**
    ```bash
    composer install
    ```
3.  **Salin file environment:**
    ```bash
    cp .env.example .env
    ```
4.  **Konfigurasi file `.env`:**
    *   Atur `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, dll., sesuai dengan pengaturan database lokal Anda.
    *   Atur `APP_KEY` (atau generate di langkah berikutnya).
    *   Konfigurasi pengaturan lain yang relevan seperti mail, queue, dll.
5.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```
6.  **Jalankan migrasi database dan seeder (jika ada):**
    ```bash
    php artisan migrate
    ```
    Jika Anda ingin mengisi data awal, jalankan seeder (pastikan seeder relevan telah dibuat dan didaftarkan di `DatabaseSeeder.php`):
    ```bash
    php artisan db:seed
    ```
    Seeder yang mungkin relevan berdasarkan struktur proyek: `UserSeeder`, `ArtistSeeder`, `CategorySeeder`, `ServiceSeeder`, `CommissionSeeder`.
7.  **Install dependensi NPM dan compile aset (jika menggunakan Vite/Mix):**
    ```bash
    npm install
    npm run dev
    ```
8.  **Jalankan development server:**
    ```bash
    php artisan serve
    ```
    Aplikasi akan tersedia di `http://localhost:8000` (atau port lain jika ditentukan).

**Catatan Tambahan:**
*   Untuk fungsionalitas chat real-time, Anda perlu mengkonfigurasi driver broadcasting (misalnya Pusher, Ably, atau Redis dengan Socket.IO) di file `.env` dan menjalankan queue worker jika diperlukan.
*   Pastikan environment lokal Anda (PHP, Composer, Node.js, Database Server) memenuhi persyaratan Laravel 11.

## Cara Berkontribusi

Terima kasih atas minat Anda untuk berkontribusi pada proyek ini! Saat ini, panduan kontribusi formal belum ditetapkan. Namun, Anda dapat memulai dengan:
1.  Melakukan fork pada repository.
2.  Membuat branch baru untuk fitur atau perbaikan bug Anda.
3.  Melakukan commit pada perubahan Anda.
4.  Membuat Pull Request ke branch utama.

Pastikan untuk mengikuti standar coding yang ada dan memberikan deskripsi yang jelas mengenai perubahan yang Anda ajukan.

---
*Dokumen ini dihasilkan berdasarkan analisis dari file-file perencanaan proyek.*
