# Rencana Struktur untuk README.md Proyek Komisi Karya Seni

Dokumen ini menguraikan rencana struktur dan konten yang digunakan untuk membuat file `README.md` utama proyek.

## 1. Judul Proyek
    *   Contoh: "Platform Komisi Karya Seni Bergambar"

## 2. Deskripsi Singkat Proyek
    *   Tujuan utama proyek.
    *   Teknologi utama (Laravel 11).
    *   *Sumber: [`project_analysis_and_plan.md`](project_analysis_and_plan.md)*

## 3. Status Proyek Saat Ini
    *   Ringkasan tingkat tinggi (berfungsi, rusak, dalam pengembangan).
    *   *Sumber: Sintesis dari [`project_analysis_and_plan.md`](project_analysis_and_plan.md)*

## 4. Fitur Utama (Implemented or Mostly Functional Features)
    *   Daftar fitur yang sudah berjalan atau backend-nya fungsional.
    *   Contoh: Autentikasi, Halaman Sambutan, Fitur "Suka", Ulasan, Chat Dasar, Daftar & Detail Seniman Dasar, Manajemen Komisi oleh Pengguna.
    *   *Sumber: [`project_analysis_and_plan.md`](project_analysis_and_plan.md) (Bagian 3.1)*

## 5. Isu dan Bug yang Diketahui (Known Issues & Bugs)
    *   Daftar bug kritis dan utama.
    *   Contoh: Kegagalan Pembuatan Pesanan (referensi ke [`fix_order_creation_plan.md`](fix_order_creation_plan.md)), Rute Profil Belum Ada, Kesalahan Daftar Komisi, Ketidaksesuaian Skema Tabel.
    *   *Sumber: [`project_analysis_and_plan.md`](project_analysis_and_plan.md) (Bagian 3.2)*

## 6. Fitur yang Belum Selesai atau Sebagian Diimplementasikan
    *   Daftar fitur yang sudah dimulai namun belum sepenuhnya fungsional.
    *   Contoh: Profil Seniman Lanjut, Manajemen Layanan, Pelacakan Pembayaran Detail, Chat Real-time Frontend, Panel Admin.
    *   *Sumber: [`project_analysis_and_plan.md`](project_analysis_and_plan.md) (Bagian 3.3)*

## 7. Fitur yang Direncanakan & Pengembangan Selanjutnya
    *   Garis besar fitur untuk pengembangan di masa depan.
    *   Contoh: Sistem Profil Seniman Penuh, Manajemen Layanan Penuh, Integrasi Gateway Pembayaran, Chat Real-Time Penuh, Panel Admin, Alur Kerja Manajemen Pesanan Seniman (referensi ke [`artist_order_management_plan.md`](artist_order_management_plan.md)).
    *   *Sumber: [`project_analysis_and_plan.md`](project_analysis_and_plan.md) (Bagian 3.3, 4.3), [`artist_order_management_plan.md`](artist_order_management_plan.md)*

## 8. Gambaran Model Data (Data Model Overview)
    *   Sertakan diagram ERD Mermaid yang ada.
    *   *Sumber: [`project_analysis_and_plan.md`](project_analysis_and_plan.md) (Bagian 3.5)*
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
    ```

## 9. Sorotan Rencana Pengembangan (Opsional)
    *   Sebutkan pendekatan bertahap dari [`project_analysis_and_plan.md`](project_analysis_and_plan.md) (Fase 1: Perbaikan, Fase 2: Fungsionalitas Inti, dst.).
    *   *Sumber: [`project_analysis_and_plan.md`](project_analysis_and_plan.md) (Bagian 4)*

## 10. Instalasi dan Pengaturan
    *   Instruksi standar pengaturan Laravel:
        *   `git clone`
        *   `composer install`
        *   `.env` setup
        *   `php artisan key:generate`
        *   `php artisan migrate --seed` (sebutkan seeder penting)
    *   Dependensi lain (Node.js, server broadcast).

## 11. Cara Berkontribusi (Opsional)
    *   Panduan kontribusi standar Laravel atau placeholder.