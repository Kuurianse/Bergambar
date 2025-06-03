@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <h2>Kelola Pesanan: Judul Komisi Placeholder Panjang</h2>
            <hr>

            <!-- Alert messages placeholder -->
            <div class="alert alert-success" role="alert">
                Pesan sukses placeholder.
            </div>
            <div class="alert alert-danger" role="alert">
                Pesan error placeholder.
            </div>

            <h4>Detail Komisi</h4>
            <p><strong>Judul/Deskripsi:</strong> Deskripsi lengkap komisi placeholder yang mungkin cukup panjang.</p>
            <p><strong>Status Saat Ini:</strong> 
                <span class="badge bg-primary">In Progress</span> <!-- Contoh status -->
            </p>
            <!-- Placeholder untuk gambar komisi -->
            <div class="commission-image-placeholder my-3">
                <img src="https://via.placeholder.com/200x150.png?text=Gambar+Komisi" alt="Gambar Komisi Placeholder" style="max-width: 200px; height: auto;">
            </div>

            <h4 class="mt-4">Detail Pesanan</h4>
            <!-- Conditional: Order details exist -->
            <div class="order-details-placeholder">
                <p><strong>Dipesan oleh:</strong> Nama Klien Placeholder</p>
                <p><strong>Tanggal Pesan:</strong> 01 Februari 2024, 12:30</p>
                <p><strong>Total Harga:</strong> Rp250.000</p>
                <p><strong>Status Pembayaran:</strong> <span class="badge bg-success">Paid</span></p>
                
                <!-- Placeholder: Link Hasil Karya (jika status 'submitted_for_client_review') -->
                <div class="alert alert-info delivery-link-placeholder" style="display: none;"> <!-- Tampilkan jika relevan -->
                    <p><strong>Link Hasil Karya yang Telah Dikirim:</strong></p>
                    <p><a href="https://contoh.com/hasilkarya_placeholder" target="_blank" rel="noopener noreferrer">https://contoh.com/hasilkarya_placeholder</a></p>
                </div>

                <!-- Placeholder: Catatan Revisi dari Klien (jika status 'needs_revision') -->
                <div class="alert alert-warning mt-3 revision-notes-placeholder" style="display: none;"> <!-- Tampilkan jika relevan -->
                    <h5><i class="fas fa-exclamation-triangle"></i> Permintaan Revisi dari Klien</h5>
                    <div class="mb-2 p-2 border-bottom">
                        <p class="mb-1"><strong>Diminta pada:</strong> 05 Februari 2024, 10:00</p>
                        <p class="mb-0"><strong>Catatan:</strong> Tolong revisi bagian warna rambut menjadi lebih gelap.</p>
                    </div>
                    <div class="mb-2 p-2 border-bottom">
                        <p class="mb-1"><strong>Diminta pada:</strong> 03 Februari 2024, 15:00</p>
                        <p class="mb-0"><strong>Catatan:</strong> Latar belakangnya kurang detail, mohon ditambahkan elemen alam.</p>
                    </div>
                </div>
            </div>
            <!-- Conditional: No order details -->
            <div class="no-order-details-placeholder" style="display: none;"> <!-- Tampilkan jika tidak ada info order -->
                <p class="text-danger">Informasi pesanan tidak ditemukan.</p>
            </div>

            <h4 class="mt-4">Aksi Seniman</h4>
            <!-- Conditional: Actions available -->
            <div class="artist-actions-placeholder">
                <p>Pilih aksi untuk memperbarui status komisi ini:</p>
                <!-- Contoh Aksi 1: Terima Pesanan -->
                <form action="#" method="POST" class="mb-2">
                    <input type="hidden" name="new_status" value="artist_accepted">
                    <button type="submit" class="btn btn-success">Terima Pesanan</button>
                </form>
                <!-- Contoh Aksi 2: Mulai Pengerjaan (jika sudah diterima) -->
                <form action="#" method="POST" class="mb-2" style="display: none;"> <!-- Tampilkan jika relevan -->
                    <input type="hidden" name="new_status" value="in_progress">
                    <button type="submit" class="btn btn-info">Mulai Pengerjaan</button>
                </form>
                <!-- Contoh Aksi 3: Kirim Hasil Karya -->
                <form action="#" method="POST" class="mb-2">
                    <input type="hidden" name="new_status" value="submitted_for_client_review">
                    <div class="mb-3">
                        <label for="delivery_link_placeholder" class="form-label">Link Hasil Karya (Wajib diisi)</label>
                        <input type="url" class="form-control" 
                               id="delivery_link_placeholder" name="delivery_link" 
                               value="" 
                               placeholder="https://contoh.com/hasilkarya" required>
                        <!-- Placeholder untuk error -->
                        <div class="invalid-feedback" style="display: none;">
                            Link hasil karya wajib diisi dan harus berupa URL yang valid.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Hasil Karya untuk Review</button>
                </form>
                 <!-- Contoh Aksi 4: Tandai Selesai Setelah Revisi (jika ada revisi sebelumnya) -->
                <form action="#" method="POST" class="mb-2" style="display: none;"> <!-- Tampilkan jika relevan -->
                    <input type="hidden" name="new_status" value="submitted_for_client_review">
                     <div class="mb-3">
                        <label for="delivery_link_revised_placeholder" class="form-label">Link Hasil Karya Revisi (Wajib diisi)</label>
                        <input type="url" class="form-control" 
                               id="delivery_link_revised_placeholder" name="delivery_link" 
                               value="" 
                               placeholder="https://contoh.com/hasilkarya_revisi" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Kirim Hasil Revisi</button>
                </form>
            </div>
            <!-- Conditional: No actions available -->
            <div class="no-actions-placeholder" style="display: none;"> <!-- Tampilkan jika tidak ada aksi -->
                <p>Tidak ada aksi yang tersedia untuk status komisi saat ini dari sisi Anda.</p>
                <!-- Contoh pesan tambahan -->
                <p class="status-info-placeholder">Menunggu review dari klien.</p> 
            </div>
            <hr>
            <a href="#" class="btn btn-secondary mt-3">Kembali ke Daftar Pesanan</a>
        </div>
        <div class="col-md-4">
            <h5>Catatan:</h5>
            <p><small>Pastikan untuk selalu berkomunikasi dengan klien Anda melalui fitur chat jika ada pertanyaan atau klarifikasi yang diperlukan.</small></p>
            <!-- Bisa ditambahkan info lain di sidebar jika perlu -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Ringkasan Klien</h6>
                    <p class="card-text"><strong>Nama:</strong> Nama Klien Placeholder</p>
                    <p class="card-text"><strong>Bergabung:</strong> 01 Jan 2023</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Profil Klien</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection