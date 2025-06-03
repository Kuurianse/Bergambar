@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>Kelola Pesanan Komisi Saya</h2>

    <!-- Alert messages placeholder -->
    <div class="alert alert-success" role="alert">
        Pesan sukses placeholder.
    </div>
    <div class="alert alert-danger" role="alert">
        Pesan error placeholder.
    </div>
    <div class="alert alert-info" role="alert">
        Pesan info placeholder.
    </div>

    <!-- Conditional content: No orders -->
    <div class="no-orders-placeholder">
        <p>Anda belum memiliki pesanan komisi aktif saat ini.</p>
    </div>

    <!-- Conditional content: Orders exist -->
    <div class="orders-list-placeholder">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Judul Komisi</th>
                        <th>Klien</th>
                        <th>Tanggal Pesan</th>
                        <th>Status Komisi</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example Row 1 -->
                    <tr>
                        <td>
                            <a href="#">Judul Komisi Placeholder 1</a>
                        </td>
                        <td>Nama Klien 1</td>
                        <td>01 Jan 2024, 10:00</td>
                        <td>
                            <span class="badge bg-warning text-dark">Ordered Pending Artist Action</span>
                        </td>
                        <td>Rp100.000</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">Kelola Pesanan</a>
                        </td>
                    </tr>
                    <!-- Example Row 2 -->
                    <tr>
                        <td>
                            <a href="#">Judul Komisi Placeholder 2 Panjang Sekali Sampai Harus Dipotong</a>
                        </td>
                        <td>Nama Klien 2</td>
                        <td>02 Feb 2024, 12:30</td>
                        <td>
                            <span class="badge bg-primary">In Progress</span>
                        </td>
                        <td>Rp250.000</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">Kelola Pesanan</a>
                        </td>
                    </tr>
                    <!-- Example Row 3 (Different Status) -->
                    <tr>
                        <td>
                            <a href="#">Komisi Desain Logo Cepat</a>
                        </td>
                        <td>Klien Setia</td>
                        <td>15 Mar 2024, 08:15</td>
                        <td>
                            <span class="badge bg-success">Submitted For Client Review</span>
                        </td>
                        <td>Rp500.000</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">Kelola Pesanan</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination placeholder -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection