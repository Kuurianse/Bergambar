document.addEventListener('DOMContentLoaded', function () {
    // Loop melalui setiap tombol dengan kelas 'love-button'
    document.querySelectorAll('.love-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Mencegah form submit default

            let form = this.closest('form');
            let commissionId = this.dataset.commissionId;

            // Dapatkan elemen-elemen yang akan diupdate
            let iconElement = this.querySelector('i.fa'); // Cari ikon di dalam tombol
            let loveTextSpan = this.querySelector('.love-text'); // Cari span teks di dalam tombol
            let loveCountSpan = this.querySelector('.love-count'); // Cari span hitungan di dalam tombol
            let globalLoveCountSpan = document.getElementById('globalLoveCount-' + commissionId); // Cari span hitungan di luar tombol

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json' // Penting untuk beberapa server, meskipun tidak selalu wajib
                },
                // Body tidak perlu jika Anda tidak mengirim data lain selain token CSRF di header
                // body: JSON.stringify({
                //     _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                // })
            })
            .then(response => {
                if (!response.ok) {
                    // Tangani error HTTP, misal 401 Unauthorized
                    if (response.status === 401) {
                        alert('Anda harus login untuk melakukan aksi ini.');
                        // Redirect ke halaman login jika Anda ingin
                        window.location.href = '/login';
                        return; // Hentikan eksekusi selanjutnya
                    }
                    throw new Error('Network response was not ok, status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                // Update ikon hati
                if (iconElement) {
                    // Pastikan untuk menghapus kelas yang mungkin bertentangan dari Font Awesome 4 atau 6
                    iconElement.classList.remove('fa-heart', 'fa-heart-o', 'fa-solid', 'fa-regular');
                    // Tambahkan kelas yang sesuai (sesuaikan dengan versi Font Awesome yang Anda gunakan)
                    // Jika Font Awesome 4:
                    iconElement.classList.add(data.loved ? 'fa-heart' : 'fa-heart-o');
                    // Jika Font Awesome 6 (contoh, jika ingin hati solid/regular):
                    // iconElement.classList.add(data.loved ? 'fa-solid' : 'fa-regular', 'fa-heart');
                }
                
                // Update teks "Love" / "Loved"
                if (loveTextSpan) {
                    loveTextSpan.textContent = data.loved ? 'Loved' : 'Love';
                }

                // Update jumlah love di dalam tombol
                if (loveCountSpan) {
                    loveCountSpan.textContent = data.loved_count;
                }

                // Update jumlah love di bagian "Loves: X" (detail group)
                if (globalLoveCountSpan) {
                    globalLoveCountSpan.textContent = data.loved_count;
                }
                
                // Ubah gaya tombol (warna background/border)
                // Pastikan kelas 'danger' dan 'outline-danger' didefinisikan di CSS Anda
                if(data.loved) {
                    this.classList.remove('outline-danger');
                    this.classList.add('danger');
                } else {
                    this.classList.remove('danger');
                    this.classList.add('outline-danger');
                }
            })
            .catch(error => {
                console.error('Error toggling love:', error);
                alert('Terjadi kesalahan saat mengubah status love. Silakan coba lagi.');
            });
        });
    });
});