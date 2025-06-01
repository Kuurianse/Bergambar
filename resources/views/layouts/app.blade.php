<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Vite Script untuk Menggabungkan semua yang diperlukan -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/bootstrap.js'])
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
    <div id="app">
    <!-- Navbar -->
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

    @yield('content')
        
    </div>
</body>
</html>
