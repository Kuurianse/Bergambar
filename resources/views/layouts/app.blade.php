<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    
    {{-- HANYA PILIH SATU VERSI FONT AWESOME --}}
    {{-- Pilihan A: Font Awesome 6 (Disarankan jika Anda ingin fitur terbaru) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Pilihan B: Font Awesome 4.7.0 (Jika Anda hanya butuh ikon dasar dan ingin lebih ringan) --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    
    @vite(['resources/js/app.js'])
    
    {{-- css --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    @yield('css_tambahan') {{-- Slot untuk CSS tambahan --}}

    {{-- font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
</head>
<body>
    <header>
        <a href="/" class="logo" style="text-decoration: none; color: inherit;">Bergambar</a>
        <nav>
            <ul>
                <li><a href="{{ route('artists.index') }}">Artists</a></li>
                @auth
                <li><a href="{{ route('commissions.index') }}">Commissions</a></li>
                <li><a href="{{ route('chat.index') }}">Chat</a></li>
                <li> 
                    <a href="#" class="others">
                        Others 
                        <span class="others-arrow">
                            <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.3977 7.66292C7.17802 7.88259 6.82192 7.88259 6.60225 7.66292L0.867387 1.92804C0.64772 1.70837 0.64772 1.35227 0.867387 1.13259L1.13256 0.867393C1.35222 0.647718 1.70838 0.647718 1.92805 0.867393L6.99997 5.93934L12.0719 0.867393C12.2916 0.647718 12.6477 0.647718 12.8674 0.867393L13.1326 1.13259C13.3522 1.35227 13.3522 1.70837 13.1326 1.92804L7.3977 7.66292Z" fill="black"/>
                            </svg>
                        </span>
                    </a>
                    <ul class="others-dropdown">
                        <li><a href="{{ route('users.show', Auth::user()->id) }}">Edit Profile</a></li>
                        <li><a href="{{ route('artist.orders.index') }}">Manage My Orders</a></li>
                        {{-- <li><a href="{{ route('services.index') }}">Manage My Services</a></li> --}}
                        <li><a href="{{ route('orders.index') }}">My Orders (Client)</a></li>
                    </ul>
                </li>
                @endauth
                <li>
                    @auth
                    <div class="btn-container">
                        <a href="{{ route('users.show', Auth::user()->id) }}" class="login-btn">{{ Auth::user()->name }}</a>
                        <a href="{{ route('logout') }}" class="register-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </div>
                    @else
                    <div class="btn-container">
                        <a href="{{ route('login') }}" class="login-btn">Login</a>
                        <a href="{{ route('register') }}" class="register-btn">Register</a>
                    </div>
                    @endauth
                </li>
            </ul>
        </nav>
    </header>

    <main>
        {{-- <div class="container-fluid" style="padding-top: 20px; padding-bottom: 20px;">
            @if (session('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div> --}}
        @yield('content')
    </main>

    <footer>
        <div class="upper"></div>
        <div class="bottom">
            <div class="logo">Bergambar</div>
            <p class="copyright">
                &copy; 2025 Bergambar. All rights reserved.
            </p>
        </div>
    </footer>

    
    {{-- Pindahkan skrip ini ke setelah yield('scripts') jika Anda menggunakan push/stack --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const header = document.querySelector("header");
            const navLinks =
                document.querySelectorAll("header nav ul li a");
            const sections = document.querySelectorAll("section");

            // Sticky Header
            window.addEventListener("scroll", function () {
                if (window.scrollY > 50) {
                    header.classList.add("sticky");
                } else {
                    header.classList.remove("sticky");
                }
            });
            // Anda bisa menambahkan logika JavaScript lainnya di sini.
            // Contoh untuk dropdown 'Others':
            const othersLink = document.querySelector('.others');
            const othersDropdown = document.querySelector('.others-dropdown');
            if (othersLink && othersDropdown) {
                othersLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    othersDropdown.classList.toggle('show'); // Tambahkan kelas 'show' untuk menampilkan dropdown
                });

                // Tutup dropdown jika klik di luar
                document.addEventListener('click', function(e) {
                    if (!othersLink.contains(e.target) && !othersDropdown.contains(e.target)) {
                        othersDropdown.classList.remove('show');
                    }
                });
            }
        });
    </script>
    @stack('scripts') {{-- Pastikan ada @stack('scripts') di layout Anda agar @push('scripts') berfungsi --}}
</body>
</html>