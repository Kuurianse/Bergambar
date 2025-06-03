<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="csrf_token_placeholder"> <!-- Placeholder -->

    <title>{{ config('app.name', 'Bergambar') }} - Barebones</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


    <!-- Scripts (Vite or direct links for barebones) -->
    <!-- For barebones, we might simulate or use CDN if Vite setup is complex to replicate without running it -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    <!-- <script src="/js/app.js" defer></script> --> <!-- Placeholder for app.js -->


    <!-- Custom Styles Placeholder -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa; /* Light gray background for the body */
            padding-top: 70px; /* Adjust if navbar is fixed-top */
        }
        .navbar {
            /* background-color: #ffffff; */ /* Default white, can be overridden */
            /* box-shadow: 0 2px 4px rgba(0,0,0,.04); */
        }
        .navbar-brand img {
            max-height: 40px; /* Consistent logo size */
        }
        .nav-link, .navbar-nav .nav-item .btn-muted {
            color: #495057; /* Standard text color */
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .nav-link:hover, .navbar-nav .nav-item .btn-muted:hover {
            color: #007bff; /* Primary color on hover */
            font-weight: 500;
        }
        .nav-link.fw-bold, .navbar-nav .nav-item .btn-muted.fw-bold {
            color: #0056b3; /* Darker primary for active/bold items */
            font-weight: bold;
        }
        .dropdown-item:active {
            background-color: #e9ecef; /* Light grey for active dropdown item */
        }
        .card-header {
            font-weight: 500;
        }
        /* Add more global styles or component-specific styles as needed for the redesign */
        .btn-primary { /* Example primary button style */
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        /* Placeholder for hero text styling if used globally */
        .hero-text-placeholder {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 5px;
            color: white;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#"> <!-- Href to # -->
                    <img src="https://via.placeholder.com/150x50/007BFF/FFFFFF?Text=Bergambar" alt="Logo Placeholder" style="max-height: 40px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('/') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-home me-1"></i>Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('artists*') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-palette me-1"></i>Artists</a>
                        </li>
                        <!-- Authenticated: Commissions Link -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('commissions*') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-file-signature me-1"></i>Commissions</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links Placeholder -->
                        <!-- Guest View -->
                        <li class="nav-item" style="display: none;"> <!-- Toggle display based on auth status -->
                            <a class="nav-link {{ Request::is('login') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                        </li>
                        <li class="nav-item" style="display: none;"> <!-- Toggle display based on auth status -->
                            <a class="nav-link {{ Request::is('register') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-user-plus me-1"></i>Register</a>
                        </li>

                        <!-- Authenticated User View -->
                        <li class="nav-item"> <!-- Toggle display based on auth status -->
                            <a class="nav-link {{ Request::is('chat*') ? 'fw-bold' : '' }}" href="#">
                                <i class="fas fa-comments me-1"></i>Chat 
                                <span class="badge bg-danger rounded-pill" style="display: inline-block;">3</span> <!-- Example unread count -->
                            </a>
                        </li>
                        <li class="nav-item dropdown"> <!-- Toggle display based on auth status -->
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img src="https://via.placeholder.com/30/007BFF/FFFFFF?Text=U" class="rounded-circle me-1" alt="User Avatar" style="width:30px; height:30px;">
                                User Name Placeholder
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item {{ Request::is('profile*') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-user-circle me-2"></i>My Profile</a>
                                <a class="dropdown-item {{ Request::is('dashboard*') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                                <hr class="dropdown-divider">
                                <!-- Artist Specific Links (show if user is artist) -->
                                <a class="dropdown-item {{ Request::is('artist/orders*') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-box-open me-2"></i>My Artist Orders</a>
                                <a class="dropdown-item {{ Request::is('artist/services*') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-concierge-bell me-2"></i>My Services</a>
                                <a class="dropdown-item {{ Request::is('artist/commissions*') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-palette me-2"></i>My Commissions (as Artist)</a>
                                <hr class="dropdown-divider">
                                <!-- Client Specific Links -->
                                <a class="dropdown-item {{ Request::is('client/orders*') ? 'fw-bold' : '' }}" href="#"><i class="fas fa-shopping-bag me-2"></i>My Orders (as Client)</a>
                                <hr class="dropdown-divider">
                                <a class="dropdown-item text-danger" href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form-placeholder').submit();">
                                   <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                </a>
                                <form id="logout-form-placeholder" action="#" method="POST" class="d-none"> <!-- Action to # -->
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <!-- Footer Placeholder -->
        <footer class="py-4 mt-auto bg-light text-center text-muted border-top">
            <div class="container">
                <p class="mb-1">&copy; {{ date('Y') }} Bergambar - Barebones Version. All Rights Reserved.</p>
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                    <li class="list-inline-item"><a href="#">Terms of Service</a></li>
                    <li class="list-inline-item"><a href="#">Contact Us</a></li>
                </ul>
            </div>
        </footer>
    </div>
    @stack('scripts') <!-- For page-specific scripts -->
</body>
</html>