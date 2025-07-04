<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
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
        <a href="/" class="logo" style="text-decoration: none; color: inherit; ">Bergambar</a>
        <nav>
            <ul>
                <li><a href="{{ route('artists.index') }}">Artists</a></li>
                @auth
                <li><a href="{{ route('commissions.index') }}">Commissions</a></li>
                <li><a href="{{ route('chat.index') }}">Chat</a></li>
                @endauth
                <li>
                    @auth
                    <div class="btn-container">
                        <a href="{{ route('users.show', Auth::user()->id) }}" class="login-btn">{{ Auth::user()->name }}</a>
                        <a href="{{ route('logout') }}" class="register-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
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
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="upper"></div>
        <div class="bottom">
            <div class="logo">Bergambar</div>
            <p class="copyright">
                &copy; 2025 Bergambar. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="{{ asset('js/love.js') }}"></script>
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
        });
    </script>
</body>
</html>