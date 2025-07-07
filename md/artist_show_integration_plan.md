# Rencana Integrasi Tampilan Baru Halaman Artis

Berikut adalah rencana langkah-demi-langkah untuk mengintegrasikan tampilan baru untuk `resources/views/artists/show.blade.php`.

## Langkah 1: Analisis & Pengumpulan Data

Menganalisis file-file berikut untuk memetakan semua data dinamis dan logika kondisional:
- `resources/views/artists/show.blade.php` (template baru)
- `resources/views/artists/showss.blade.php` (logika lama)
- `resources/views/commissions/index.blade.php` (referensi untuk daftar komisi)

## Langkah 2: Implementasi pada `show.blade.php`

Memodifikasi `resources/views/artists/show.blade.php` dengan perubahan berikut:

### 1. Bagian Profil Artis
- **Gambar Profil:** Mengganti gambar profil statis dengan logika kondisional.
  - Jika `$artist->user->profile_picture` ada, gunakan gambar tersebut.
  - Jika tidak, gunakan gambar placeholder dari Unsplash yang ada di template sebagai fallback.
- **Data Teks:** Mengganti `username`, `name`, `rating`, dan `bio` dengan data dinamis dari variabel `$artist`.
- **Lencana "Verified":** Menampilkan lencana hanya jika `$artist->is_verified` bernilai `true`.

### 2. Bagian Tombol Aksi
- Menerapkan logika `@auth` dan `@if` untuk menampilkan tombol yang sesuai:
  - **"Edit Artist Profile"**: Tampil jika `Auth::id() == $artist->user_id`.
  - **"Contact Artist"**: Tampil jika `Auth::id() != $artist->user_id`.
  - **"Portfolio"**: Tampil jika `$artist->portfolio_link` ada.

### 3. Bagian "My Commissions"
- Menghapus semua kartu komisi statis yang ada.
- Menambahkan perulangan `@foreach($artist->user->commissions as $commission)` untuk menampilkan komisi secara dinamis.
- **Gambar Komisi:** Di dalam setiap kartu, jika `$commission->image` ada, tampilkan gambar tersebut. Jika tidak, gunakan gambar placeholder Unsplash sebagai fallback.
- **Data Komisi:** Menampilkan judul, status, nama artis, jumlah "love", dan harga secara dinamis.
- **Tombol Aksi Komisi:** Menambahkan logika `@auth` untuk menampilkan tombol "Edit", "Delete", dan "View" hanya untuk pemilik komisi.
- Menambahkan pesan "This artist has not posted any commissions yet." jika tidak ada komisi yang ditemukan.

## Diagram Alur Data

```mermaid
graph TD
    subgraph Controller
        A[Artist Data: $artist]
    end

    subgraph "resources/views/artists/show.blade.php"
        B[Profile Section]
        C[Action Buttons Section]
        D[My Commissions Section]
    end

    subgraph "Data Logic"
        B1["@if ($artist->user->profile_picture) ... @else [Unsplash Image]"]
        B2[Username, Name, Bio, Rating]
        C1["@auth & @if logic for buttons"]
        D1["@foreach ($artist->user->commissions as $commission)"]
        D2["@if ($commission->image) ... @else [Unsplash Image]"]
        D3[Commission Data: Title, Price, etc.]
        D4["@auth & @if logic for commission buttons"]
    end

    A -- artist data --> B
    A -- user auth logic --> C
    A -- artist commissions --> D

    B -- populates --> B1 & B2
    C -- populates --> C1
    D -- populates --> D1
    D1 -- creates cards with --> D2 & D3 & D4