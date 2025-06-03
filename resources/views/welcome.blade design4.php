{{-- Original Path: resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bergambar - Arts for All</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- Jika Anda menggunakan Vite, Anda bisa menggunakan:
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    --}}
    <style>
        body {
            font-family: 'Arial', sans-serif; /* Contoh penggantian font */
        }
        .navbar-brand img {
            max-height: 40px; /* Sesuaikan ukuran logo */
        }
        .hero-section {
            background-color: #f8f9fa; /* Warna latar belakang soft */
            padding: 60px 0;
        }
        .hero-section h1 {
            font-size: 2.8rem;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.2rem;
            color: #6c757d;
        }
        .hero-section img {
            max-height: 400px; /* Batasi tinggi gambar hero */
            object-fit: contain;
        }
        .commission-card img {
            height: 200px; /* Tinggi tetap untuk gambar kartu */
            object-fit: cover; /* Agar gambar mengisi area tanpa distorsi */
        }
        .commission-card .card-title a {
            text-decoration: none;
            color: inherit;
        }
        .commission-card .card-title a:hover {
            color: #0d6efd; /* Bootstrap primary color */
        }
        .love-icon {
            cursor: pointer;
            font-size: 1.2rem;
        }
        .loved {
            color: red; /* Warna untuk ikon yang sudah di-love */
        }
    </style>
</head>
<body>
    <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('assets/Logo.png') }}" alt="Logo Bergambar">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('artists.index') ? 'active' : '' }}" href="{{ route('artists.index') }}">
                            Artists
                        </a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('commissions.index') ? 'active' : '' }}" href="{{ route('commissions.index') }}">
                            Commissions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}" href="{{ route('chat.index') }}">
                            Chat
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUserLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUserLink">
                            <li>
                                <a class="dropdown-item" href="{{ route('users.show', Auth::user()->id) }}">
                                    Edit Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                     @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
                    <h1>Welcome to Bergambar</h1>
                    <p class="lead">Arts for all, from heart to crafts.</p>
                    <a href="{{ route('commissions.index') }}" class="btn btn-primary btn-lg mt-3">Explore Art</a>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('assets/hero.png') }}" alt="Hero Image Bergambar" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h2 class="text-center mb-5">Discover Commissions</h2>
        <div>
            @if($commissions->isEmpty())
                <div class="alert alert-info text-center" role="alert">
                    No commissions are currently available. Check back soon!
                </div>
            @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($commissions as $commission)
                <div class="col">
                    <div class="card h-100 shadow-sm commission-card">
                        @if($commission->image)
                        <img src="{{ asset('storage/' . $commission->image) }}" class="card-img-top" alt="Commission Image for {{ $commission->description }}">
                        @else
                         <img src="https://via.placeholder.com/300x200.png?text=No+Image" class="card-img-top" alt="Placeholder Image">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="{{ route('commissions.order', $commission->id) }}">
                                    {{ Str::limit($commission->description, 50) }} {{-- Batasi panjang deskripsi --}}
                                </a>
                            </h5>
                            <p class="card-text small text-muted">Made by:
                                <a href="{{ route('artists.show', $commission->user->id) }}">{{ $commission->user->name }}</a>
                            </p>
                            <p class="card-text"><span class="badge bg-secondary">{{ $commission->public_status }}</span></p>
                            <p class="card-text fs-5 fw-bold text-primary">Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <a href="{{ route('commissions.order', $commission->id) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                <div class="d-flex align-items-center">
                                    @auth
                                        {{-- Ganti dengan logic AJAX untuk love --}}
                                        @if($commission->loves->contains(auth()->user()))
                                        <span class="love-icon loved me-1" data-commission-id="{{ $commission->id }}">❤️</span> {{-- Bisa pakai ikon font --}}
                                        @else
                                        <span class="love-icon me-1" data-commission-id="{{ $commission->id }}">🤍</span> {{-- Bisa pakai ikon font --}}
                                        @endif
                                    @else
                                        <span class="love-icon me-1">🤍</span> {{-- Ikon untuk guest --}}
                                    @endauth
                                    <span class="text-muted small">{{ $commission->loved_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    </div>

    <footer class="bg-light text-center text-lg-start mt-auto py-4">
        <div class="container">
            <p class="text-center text-muted mb-0">&copy; {{ date('Y') }} Bergambar. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    {{-- Script untuk handle Love (opsional, jika ingin interaktif tanpa refresh) --}}
    <script>
        // Contoh sederhana, idealnya menggunakan AJAX
        document.querySelectorAll('.love-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                const commissionId = this.dataset.commissionId;
                const isLoved = this.classList.contains('loved');
                // Di sini Anda akan mengirim request AJAX ke server untuk update status love
                // console.log(`Commission ID: ${commissionId}, Currently Loved: ${isLoved}`);
                // Untuk demo, kita toggle tampilan saja:
                if (isLoved) {
                    this.classList.remove('loved');
                    this.innerHTML = '🤍'; // Ganti dengan ikon unloved
                    // Kurangi count (jika ada elemen count terpisah)
                } else {
                    this.classList.add('loved');
                    this.innerHTML = '❤️'; // Ganti dengan ikon loved
                    // Tambah count (jika ada elemen count terpisah)
                }
                // PENTING: Ini hanya demo visual, backend logic tetap dibutuhkan
                // Untuk user guest, mungkin tampilkan pesan untuk login
            });
        });
    </script>
</body>
</html>