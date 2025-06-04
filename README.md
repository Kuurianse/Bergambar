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

*   **Autentikasi Pengguna (Laravel & Admin Panel):**
    *   Laravel: Registrasi, login, dan fungsionalitas reset password standar.
    *   Admin Panel (Next.js): Login khusus admin, pengelolaan sesi via AuthContext, rute terproteksi.
*   **Halaman Sambutan (Laravel):** Menampilkan daftar komisi yang tersedia untuk umum.
*   **Fitur "Suka" (Love) pada Komisi (Laravel):** Pengguna dapat menyukai/membatalkan suka pada komisi.
*   **Ulasan dan Rating Komisi (Laravel):** Pengguna dapat menambahkan ulasan beserta rating.
*   **Chat Real-time (Laravel & Blade):**
    *   Backend siap dengan event broadcasting ([`app/Events/MessageSent.php`](app/Events/MessageSent.php:1)).
    *   Frontend ([`resources/views/chat/chat.blade.php`](resources/views/chat/chat.blade.php:1)) menggunakan Laravel Echo dan Pusher untuk pesan real-time.
*   **Manajemen Komisi oleh Pengguna (Laravel):** CRUD untuk komisi milik pengguna.
*   **Manajemen Layanan oleh Seniman (Laravel):** CRUD penuh untuk layanan yang ditawarkan seniman.
*   **Dasar Manajemen Pesanan oleh Seniman (Laravel):** Seniman dapat melihat daftar pesanan dan mengubah status komisi.
*   **Panel Admin Komprehensif (Next.js - `admin-panel/`):**
    *   **Dashboard:** Menampilkan statistik (total pengguna, artis, komisi, pesanan) dan aktivitas terbaru (pengguna & komisi terbaru) via API ([`admin-panel/app/admin/dashboard/page.tsx`](admin-panel/app/admin/dashboard/page.tsx:1)).
    *   **Manajemen Pengguna:** Fungsionalitas CRUD penuh (daftar, lihat detail, buat, edit, hapus pengguna) ([`admin-panel/app/admin/users/`](admin-panel/app/admin/users/)).
    *   **Manajemen Artis:**
        *   Promosikan pengguna menjadi artis.
        *   Daftar artis dengan paginasi dan detail ([`admin-panel/app/admin/artists/page.tsx`](admin-panel/app/admin/artists/page.tsx:1)).
        *   Edit detail artis (portfolio, rating, status verifikasi) ([`admin-panel/app/admin/artists/[id]/edit/page.tsx`](admin-panel/app/admin/artists/[id]/edit/page.tsx:1)).
        *   Toggle status verifikasi artis.
    *   **Manajemen Kategori:** Fungsionalitas CRUD penuh (daftar, buat, edit, hapus kategori) ([`admin-panel/app/admin/categories/`](admin-panel/app/admin/categories/)).
    *   **Manajemen Komisi (Read-Only):** Daftar komisi dengan paginasi dan lihat detail komisi ([`admin-panel/app/admin/commissions/`](admin-panel/app/admin/commissions/)).
    *   **Manajemen Layanan (Read-Only):** Daftar layanan dengan paginasi, sorting, dan detail ([`admin-panel/app/admin/services/page.tsx`](admin-panel/app/admin/services/page.tsx:1)).
    *   **Struktur & Navigasi:** Sidebar ([`admin-panel/components/admin/admin-sidebar.tsx`](admin-panel/components/admin/admin-sidebar.tsx:1)), header ([`admin-panel/components/admin/admin-header.tsx`](admin-panel/components/admin/admin-header.tsx:1)) dengan informasi pengguna, tombol logout fungsional, dan placeholder untuk pencarian, notifikasi, profil, dan pengaturan.

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

*   **Profil Seniman Tingkat Lanjut:**
    *   Model `Artist` ([`app/Models/Artist.php`](app/Models/Artist.php:1)) dan tabel `artists` ada. `ArtistController` (Laravel) telah di-refactor.
    *   View `artists.index` dan `artists.show` (Laravel) telah diperbarui.
    *   Status verifikasi (`is_verified`) kini dapat dikelola melalui Panel Admin (Next.js) ([`admin-panel/app/admin/artists/page.tsx`](admin-panel/app/admin/artists/page.tsx:1)).
    *   *(Status Fitur Rating: Implementasi input rating (1-5 bintang) pada form review, validasi, dan penyimpanan rating di controller, serta penampilan rating pada daftar ulasan telah SELESAI. Pesan UI terkait juga sudah dalam Bahasa Indonesia)*.
    *   *(Pengembangan Lanjutan: Kalkulasi `rating` otomatis untuk seniman, integrasi lebih dalam antara profil Laravel dan data di Panel Admin).*
*   **Pelacakan Pembayaran Detail:** Model `Payment` ([`app/Models/Payment.php`](app/Models/Payment.php:1)) dan tabel `payments` ada. Alur pemesanan saat ini menyederhanakan status order menjadi 'paid'. Pembuatan record `Payment` disimulasikan saat konfirmasi pembayaran ([`app/Http/Controllers/OrderController.php`](app/Http/Controllers/OrderController.php:1)), namun integrasi gateway pembayaran penuh dan penggunaan detail model `Payment` belum ada.
*   **Panel Admin (Next.js) - Fitur Lanjutan:**
    *   **Manajemen Pesanan Admin:** Halaman untuk admin mengelola semua pesanan ([`admin-panel/app/admin/orders/page.tsx`](admin-panel/app/admin/orders/page.tsx:1)) masih tertunda implementasinya (sesuai log #14).
    *   **Fungsionalitas Penuh Navbar:** Fitur Pencarian dan Notifikasi di navbar admin ([`admin-panel/components/admin/admin-header.tsx`](admin-panel/components/admin/admin-header.tsx:1)) saat ini adalah placeholder visual. Halaman Profil dan Pengaturan Admin juga belum dibuat dan linknya bersifat placeholder.
*   **Persistensi Detail Pesanan:** ~~`OrderController@confirmPayment` belum menyimpan `total_price` ke dalam tabel `orders`.~~ *(Status: TERATASI. Metode `confirmPayment` di [`app/Http/Controllers/OrderController.php`](app/Http/Controllers/OrderController.php:1) sudah menyimpan `total_price` saat membuat order baru).*
*   **Relasi Model yang Hilang:** ~~Beberapa relasi penting (seperti `orders()`, `reviews()`, `messages()`) belum didefinisikan di model `User`.~~ *(Status: TERATASI. Relasi-relasi yang diperlukan seperti `orders()`, `reviews()`, `messagesSent()`, `messagesReceived()`, dan `lovedCommissions()` sudah ada di model [`app/Models/User.php`](app/Models/User.php:1))*.
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
*   **Implementasi Penuh Chat Real-Time:** *(Status: Frontend SELESAI, Backend sudah ada)*
    *   Konfigurasi Laravel Broadcasting (Pusher) sudah diasumsikan berjalan.
    *   Implementasi Laravel Echo di sisi klien ([`resources/views/chat/chat.blade.php`](resources/views/chat/chat.blade.php:1)) telah selesai, memungkinkan pesan tampil secara real-time.
    *   *(Pengembangan Lanjutan: Notifikasi unread messages, status online/offline, UI yang lebih kaya).*
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
        text delivery_link "nullable"
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

## Prerequisites

Before you begin, ensure you have the following installed on your system:
*   PHP (version compatible with Laravel 11, e.g., PHP >= 8.2)
*   Composer
*   Node.js (LTS version recommended)
*   pnpm (for the Next.js admin panel, as per project logs)
*   A database server (e.g., MySQL, MariaDB)
*   Git

## Setup and Running the Project

Follow these steps to get the project up and running on your local machine.

### 1. Clone the Repository

```bash
git clone <URL_REPOSITORY_ANDA>
cd <NAMA_DIREKTORI_PROYEK>
```

### 2. Backend Setup (Laravel)

These steps are performed in the root directory of the project.

**a. Install PHP Dependencies:**
```bash
composer install
```

**b. Configure Environment:**
   *   The project includes example environment files for different setups:
        *   [`./.env.laragon.example`](./.env.laragon.example:1): For Laragon users.
        *   [`./.env.xampp.example`](./.env.xampp.example:1): For XAMPP users (using `php artisan serve`).
   *   Copy the example file relevant to your setup to `.env`:
        *   For Laragon: `cp .env.laragon.example .env`
        *   For XAMPP: `cp .env.xampp.example .env`
   *   Open the `.env` file and configure it:
        *   **Database:** Set `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` according to your local database configuration. Ensure the database exists.
        *   **APP_URL:**
            *   For Laragon, it's likely pre-configured (e.g., `http://bergambar.test/`).
            *   For XAMPP with `php artisan serve`, it should be `http://localhost:8000`.
        *   Review other settings like `MAIL_HOST` if you plan to test email functionalities.

**c. Generate Application Key:**
```bash
php artisan key:generate
```

**d. Run Database Migrations and Seeders:**
   This will create the necessary tables and populate them with initial data.
```bash
php artisan migrate --seed
```
   *(Ensure relevant seeders like `UserSeeder`, `ArtistSeeder`, etc., are correctly configured in `DatabaseSeeder.php` if you need specific initial data.)*

**e. Compile Frontend Assets (Vite):**
   The main Laravel application uses Vite for frontend asset bundling.
   ```bash
   npm install
   # or if you prefer pnpm and have it installed globally:
   # pnpm install
   
   # Run the Vite development server (for hot module replacement during development)
   npm run dev
   # or for pnpm:
   # pnpm dev
   ```
   *(This command should typically be run in a separate terminal window from `php artisan serve`.)*

**f. Start the Laravel Development Server:**
   *   **For Laragon:**
        *   Ensure Laragon is running and your project is correctly configured as a virtual host (e.g., pointing to `http://bergambar.test/`). You typically start services via the Laragon application ("Start All"). The Vite dev server (from step e) should also be running.
   *   **For XAMPP (or manual `php artisan serve`):**
        ```bash
        php artisan serve
        ```
        The Laravel application will usually be available at `http://localhost:8000`. The Vite dev server (from step e) should also be running.

### 3. Frontend Setup (Next.js Admin Panel)

The admin panel is a Next.js application located in the `admin-panel/` directory.

**a. Navigate to the Admin Panel Directory:**
```bash
cd admin-panel
```

**b. Install JavaScript Dependencies:**
   The project uses `pnpm` for the admin panel.
```bash
pnpm install
```

**c. Configure Admin Panel Environment:**
   *   An example environment file is provided at [`admin-panel/.env.local.example`](admin-panel/.env.local.example:1).
   *   Copy this file to `admin-panel/.env.local`:
        ```bash
        cp .env.local.example .env.local
        ```
   *   Open `admin-panel/.env.local` and uncomment/configure the variables based on your backend setup:
        *   **If your Laravel backend is running via Laragon (e.g., at `http://bergambar.test/`):**
            ```env
            NEXT_PUBLIC_APP_URL=http://localhost:3000
            NEXT_PUBLIC_LARAVEL_PROXY_DESTINATION=http://bergambar.test/
            NEXT_PUBLIC_LARAVEL_ROOT_VIA_PROXY=/api-proxy
            ```
        *   **If your Laravel backend is running via XAMPP with `php artisan serve` (e.g., at `http://localhost:8000`):**
            ```env
            NEXT_PUBLIC_APP_URL=http://localhost:3001 # Or your preferred port for Next.js
            NEXT_PUBLIC_LARAVEL_PROXY_DESTINATION=http://localhost:8000
            NEXT_PUBLIC_LARAVEL_ROOT_VIA_PROXY=/api-proxy
            ```
        *   **Note:** `NEXT_PUBLIC_APP_URL` is the URL where the Next.js admin panel itself will run. Ensure the port doesn't conflict with other services.

**d. Start the Next.js Development Server:**
```bash
pnpm dev
```
   The Next.js admin panel will typically be available at the URL specified in `NEXT_PUBLIC_APP_URL` (e.g., `http://localhost:3000` or `http://localhost:3001`).

### 4. Accessing the Application

*   **Main Laravel Application:** Access it via the URL configured in your Laravel `.env` (`APP_URL`) and your web server setup (e.g., `http://bergambar.test/` for Laragon, or `http://localhost:8000` for `php artisan serve`).
*   **Next.js Admin Panel:** Access it via the URL configured in `admin-panel/.env.local` (`NEXT_PUBLIC_APP_URL`), typically `http://localhost:3000` or `http://localhost:3001`.

**Additional Notes:**
*   **Real-time Chat (Pusher):** For full real-time chat functionality, you'll need to:
    *   Configure Pusher (or an alternative like Ably/Soketi) credentials in your Laravel `.env` file (`PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_CLUSTER`).
    *   Set `BROADCAST_DRIVER=pusher` in Laravel's `.env`.
    *   Ensure `App\Providers\BroadcastServiceProvider::class` is uncommented in `config/app.php`.
    *   Define broadcast authentication routes in `routes/channels.php`.
    *   Run `php artisan queue:work` if you're queueing broadcasts.
*   Ensure your local development environment (PHP, Composer, Node.js, Database Server) meets the requirements of Laravel 11 and the Next.js version used.