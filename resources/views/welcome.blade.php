<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bergambar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}" />
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
</head>
<body>
    <section class="hero">
        <!-- Navigation Bar -->
        <header>
            <div class="logo">Bergambar</div>
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
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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

        <!-- Hero Section -->
        <div class="hero-container">
            <div class="hero-content">
                <h1>Commission Amazing Artwork</h1>
                <p>
                    Connect with talented artists and bring your creative visions to
                    life. Browse portfolios, commision custom artwork, and support the
                    creative community.
                </p>
                <div class="btn-container">
                    <a href="{{ route('artists.index') }}" class="btn browse-btn"> Browse Artist </a>
                    <a href="{{ route('commissions.index') }}" class="btn view-btn"> View Commissions </a>
                </div>
            </div>

            <div class="hero-image">
                <img src="{{ asset('assets/imageNew.jpg') }}" alt="Hero Image" />
            </div>
        </div>
    </section>

    <!-- Featured Commissions -->
    <section class="featured">
        <!-- featured-container -->
        <div class="featured-container">
            <div class="featured-header">
                <h2>Featured Commissions</h2>
                <p>Discover amazing artwork from our talented community</p>
            </div>

            <div class="cards-grid-container">
                @if($commissions->isEmpty())
                    <p>No commissions are currently available.</p>
                @else
                    @foreach($commissions as $commission)
                    <div class="card">
                        <div class="card-image-placeholder">
                            @if($commission->image)
                                <img src="{{ asset($commission->image) }}" alt="Commission Image" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <div class="tag-completed">{{ $commission->public_status }}</div>
                        <div class="card-text">
                            <h3 class="card-title">
                                <a href="{{ route('commissions.order', $commission->id) }}" style="text-decoration: none; color: inherit;">
                                    {{ $commission->description }}
                                </a>
                            </h3>
                            <p class="card-author">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.00001 2.66671C6.52725 2.66671 5.33334 3.86061 5.33334 5.33337C5.33334 6.80613 6.52725 8.00004 8.00001 8.00004C9.47277 8.00004 10.6667 6.80613 10.6667 5.33337C10.6667 3.86061 9.47276 2.66671 8.00001 2.66671ZM10.4273 8.51296C11.3833 7.78203 12 6.62972 12 5.33337C12 3.12423 10.2091 1.33337 8.00001 1.33337C5.79087 1.33337 4.00001 3.12423 4.00001 5.33337C4.00001 6.62972 4.61668 7.78203 5.57268 8.51296C4.90236 8.80948 4.28567 9.22909 3.75737 9.7574C3.34941 10.1654 3.00624 10.6261 2.73452 11.1234C2.23311 12.0413 2.43759 12.9772 3.00227 13.639C3.54587 14.276 4.41669 14.6667 5.33334 14.6667H10.6667C11.5833 14.6667 12.4541 14.276 12.9977 13.639C13.5624 12.9772 13.7669 12.0413 13.2655 11.1234C12.9938 10.6261 12.6506 10.1654 12.2426 9.7574C11.7143 9.22909 11.0976 8.80948 10.4273 8.51296ZM8.00001 9.33337C6.76233 9.33337 5.57534 9.82504 4.70017 10.7002C4.38284 11.0175 4.11596 11.3758 3.90464 11.7627C3.7 12.1373 3.76602 12.4799 4.01654 12.7735C4.28814 13.0918 4.77723 13.3334 5.33334 13.3334H10.6667C11.2228 13.3334 11.7119 13.0918 11.9835 12.7735C12.234 12.4799 12.3 12.1373 12.0954 11.7627C11.884 11.3758 11.6172 11.0175 11.2998 10.7002C10.4247 9.82504 9.23768 9.33337 8.00001 9.33337Z" fill="#57606E" />
                                </svg>
                                {{ $commission->user->name }}
                            </p>
                            
                            <span class="card-like">
                                @auth
                                    @if($commission->loves->contains(auth()->user()))
                                        <i class="fa-solid fa-heart love-icon" style="color: #ff0000; cursor: pointer;" data-commission-id="{{ $commission->id }}"></i>
                                    @else
                                        <i class="fa-regular fa-heart love-icon" style="color: #ff3300; cursor: pointer;" data-commission-id="{{ $commission->id }}"></i>
                                    @endif
                                @else
                                    <i class="fa-regular fa-heart" style="color: #cccccc;"></i>
                                @endauth
                                <span class="love-count">{{ $commission->loved_count }}</span>
                            </span>

                            <div class="priceBtn-absolute">
                                <span class="card-price">Rp{{ number_format($commission->total_price, 0, ',', '.') }}</span>
                                <a href="{{ route('commissions.order', $commission->id) }}" class="card-details-btn">View Details</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="upper"></div>
        <div class="bottom">
            <div class="logo">Bergambar</div>
            <p class="copyright">&copy; 2025 Bergambar. All rights reserved.</p>
        </div>
    </footer>
    <script src="{{ asset('js/love.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const header = document.querySelector("header");
            const navLinks = document.querySelectorAll("header nav ul li a");
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
