<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bergambar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
    .navbar-nav .nav-item a {
        color: #464646; 
    }

    .navbar-nav .nav-item a:hover {
       font-weight: bold;
    }

    .img-contain {
        padding: 0;
        margin: 0;
    }

    /* Tambahkan warna latar belakang hitam pada teks hero agar lebih terlihat */
    .hero-text {
        background-color: rgba(0, 0, 0, 0.5); /* Warna latar belakang semi-transparan */
        padding: 20px;
        border-radius: 5px;
    }
    </style>
</head>
<body style="background-color: #ffffff;">
    <!-- Navbar -->
    <nav class="w-100 top-0 navbar navbar-expand-lg navbar-light shadow-sm" style="background-color: #ffffff;">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('assets/Logo.png') }}" class="img-fluid rounded" alt="Logo" style="width: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-1">
                        <a href="{{ route('artists.index') }}" class="btn btn-muted {{ Request::routeIs('artists.index') ? 'fw-bold' : '' }}">
                         <i class="fas fa-palette me-2"></i>Artists
                        </a>
                    </li>
                    <!-- Tampilkan menu commissions hanya jika user login -->
                    @auth
                    <li class="nav-item mx-1">
                        <a href="{{ route('commissions.index') }}" class="btn btn-muted{{ Request::routeIs('commissions.index') ? 'fw-bold' : '' }}">
                        <i class="fa-solid fa-file-signature me-2"></i>Commission
                        </a>
                    </li>
                    @endauth

                    @auth 
                        <li class="nav-item mx-1 dropdown">
                            <a href="#" class="btn btn-muted {{ Request::is('users/*') ? 'fw-bold' : '' }} nav-link dropdown-toggle text-black fs-6" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fa-regular fa-id-card me-2"></i>{{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a href="{{ route('users.show', Auth::user()->id) }}" class="dropdown-item {{ Request::is('users/*') ? 'fw-bold' : '' }}">
                                    Edit Profile
                                </a>                                                
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @else
                        <li class="nav-item mx-1">
                            <a href="{{ route('login') }}" class="fs-6 btn btn-muted {{ Request::routeIs('login') ? 'fw-bold' : '' }}">
                                Login
                            </a>
                        </li>
                    @endauth

                    @auth
                    <li class="nav-item mx-1">
                        <a href="{{ route('chat.index') }}" class="btn btn-muted {{ Request::routeIs('chat.index') ? 'fw-bold' : '' }}">
                            <i class="fa-brands fa-rocketchat me-2"></i>Chat
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container-fluid img-contain position-relative">
        <div class="w-100 text-center text-white position-absolute top-50 start-50 translate-middle ">
            <h1 class="display-6 fw-bold">Welcome to Bergambar</h1>
            <p class="lead">Arts for all, from heart to crafts</p>
            <!-- <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg mt-3">Explore Our Services</a> -->
        </div>
        <img src="{{ asset('assets/hero.png') }}" class="img-fluid w-100" alt="...">
    </div>

    <!-- Commission Section -->
    <div class="container my-5">
        <h2 class="text-black fw-semibold">Discover Commissions</h2>
        <div class="row">
            @if($commissions->isEmpty())
                <p class="text-black fw-semibold">No commissions available at the moment.</p>
            @else
                @foreach($commissions as $commission)
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title">
                                <a href="{{ route('commissions.order', $commission->id) }}" class="text-decoration-none text-dark">
                                    {{ $commission->description }} 
                                </a>
                            </h5>
                             <!-- Cek apakah user sudah love commission ini -->
                             @if($commission->loves->contains(auth()->user()))
                                    <!-- Tampilkan ikon hati penuh jika user sudah love -->
                                    <i class="fa-solid fa-heart mt-1 ms-auto me-0 love-icon" 
                                    style="color: #ff0000; cursor: pointer;" 
                                    data-commission-id="{{ $commission->id }}"></i>
                                @else
                                    <!-- Tampilkan ikon hati kosong jika user belum love -->
                                    <i class="fa-regular fa-heart mt-1 ms-auto me-0 love-icon" 
                                    style="color: #ff3300; cursor: pointer;" 
                                    data-commission-id="{{ $commission->id }}"></i>
                                @endif
                            <span class="love-count ms-2">{{ $commission->loved_count }}</span>
                        </div>
                        <p class="card-text fw-medium">Made by: {{ $commission->user->name }}</p>
                        <p class="card-text">Status: {{ $commission->status }}</p>
                        <p class="card-text">Price:  Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>
                        @if($commission->image)
                            <img src="{{ asset('storage/' . $commission->image) }}" alt="Commission Image" class="img-fluid">
                        @endif
                    </div>
                </div>
            </div>
                @endforeach
            @endif
        </div>
    </div>

    <script src="{{ asset('js/love.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
