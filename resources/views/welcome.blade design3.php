<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bergambar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Navbar Styles */
        nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        nav > div {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav img {
            height: 40px;
            width: auto;
        }

        nav button {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        nav a:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            transform: translateY(-2px);
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
            min-width: 180px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #333;
            border-radius: 0;
        }

        .dropdown-menu a:hover {
            background: #f8f9ff;
            color: #667eea;
        }

        /* Hero Section */
        .hero {
            padding: 6rem 2rem;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero-content {
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #fff, #e0e7ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 300;
            text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .hero img {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: perspective(1000px) rotateX(5deg);
            transition: transform 0.3s ease;
        }

        .hero img:hover {
            transform: perspective(1000px) rotateX(0deg) scale(1.02);
        }

        /* Commission Section */
        .commission-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            margin: 2rem;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .commission-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .commissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .commission-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .commission-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
        }

        .commission-card-body {
            padding: 1.5rem;
        }

        .commission-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .commission-title {
            flex: 1;
            margin-right: 1rem;
        }

        .commission-title h5 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .commission-title a {
            text-decoration: none;
            color: #333;
            transition: color 0.3s ease;
        }

        .commission-title a:hover {
            color: #667eea;
        }

        .love-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }

        .love-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .love-icon:hover {
            transform: scale(1.2);
        }

        .love-icon.loved {
            color: #e74c3c;
        }

        .love-icon.not-loved {
            color: #bdc3c7;
        }

        .love-count {
            font-size: 0.875rem;
            color: #666;
            font-weight: 500;
        }

        .commission-details {
            space-y: 0.5rem;
        }

        .commission-details p {
            margin-bottom: 0.5rem;
            color: #666;
        }

        .commission-details p:first-child {
            font-weight: 600;
            color: #333;
        }

        .price {
            font-size: 1.125rem;
            font-weight: 700;
            color: #27ae60;
            background: rgba(39, 174, 96, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
            margin: 0.5rem 0;
        }

        .status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status.available {
            background: rgba(39, 174, 96, 0.1);
            color: #27ae60;
        }

        .status.in-progress {
            background: rgba(241, 196, 15, 0.1);
            color: #f1c40f;
        }

        .status.completed {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .commission-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-top: 1rem;
            transition: transform 0.3s ease;
        }

        .commission-image:hover {
            transform: scale(1.02);
        }

        .no-commissions {
            text-align: center;
            padding: 3rem;
            color: #666;
            font-size: 1.125rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav button {
                display: block;
            }

            nav ul {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 1rem;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }

            nav ul.show {
                display: flex;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .commission-section {
                margin: 1rem;
                padding: 2rem 1rem;
            }

            .commissions-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .hero {
                padding: 3rem 1rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .commission-section h2 {
                font-size: 2rem;
            }
        }

        /* Animation for love button */
        @keyframes heartbeat {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .love-icon.animate {
            animation: heartbeat 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div>
            <a href="/">
                <img src="{{ asset('assets/Logo.png') }}" alt="Bergambar Logo">
            </a>
            <button type="button" onclick="toggleNav()">
                <span>☰</span>
            </button>
            <div id="navbarNav">
                <ul>
                    <li>
                        <a href="{{ route('artists.index') }}">Artists</a>
                    </li>
                    @auth
                    <li>
                        <a href="{{ route('commissions.index') }}">Commissions</a>
                    </li>
                    @endauth

                    @auth
                        <li class="dropdown">
                            <a href="#" role="button">{{ Auth::user()->name }}</a>
                            <div class="dropdown-menu">
                                <a href="{{ route('users.show', Auth::user()->id) }}">Edit Profile</a>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth

                    @auth
                    <li>
                        <a href="{{ route('chat.index') }}">Chat</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h1>Welcome to Bergambar</h1>
            <p>Arts for all, from heart to crafts</p>
        </div>
        <img src="{{ asset('assets/hero.png') }}" alt="Bergambar Hero Image">
    </div>

    <!-- Commission Section -->
    <div class="commission-section">
        <h2>Discover Commissions</h2>
        <div class="commissions-grid">
            @if($commissions->isEmpty())
                <div class="no-commissions">
                    <p>No commissions are currently available.</p>
                </div>
            @else
                @foreach($commissions as $commission)
                <div class="commission-card">
                    <div class="commission-card-body">
                        <div class="commission-header">
                            <div class="commission-title">
                                <h5>
                                    <a href="{{ route('commissions.order', $commission->id) }}">
                                        {{ $commission->description }}
                                    </a>
                                </h5>
                            </div>
                            <div class="love-section">
                                @auth
                                    @if($commission->loves->contains(auth()->user()))
                                        <span class="love-icon loved">❤️</span>
                                    @else
                                        <span class="love-icon not-loved">🤍</span>
                                    @endif
                                @else
                                    <span class="love-icon not-loved">🤍</span>
                                @endauth
                                <span class="love-count">{{ $commission->loved_count }}</span>
                            </div>
                        </div>
                        <div class="commission-details">
                            <p><strong>Made by:</strong> {{ $commission->user->name }}</p>
                            <p><strong>Status:</strong> 
                                <span class="status {{ strtolower(str_replace(' ', '-', $commission->public_status)) }}">
                                    {{ $commission->public_status }}
                                </span>
                            </p>
                            <div class="price">Rp{{ number_format($commission->total_price, 0, ',', '.') }}</div>
                            @if($commission->image)
                                <img src="{{ asset('storage/' . $commission->image) }}" alt="Commission Image" class="commission-image">
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        function toggleNav() {
            const nav = document.getElementById('navbarNav').querySelector('ul');
            nav.classList.toggle('show');
        }

        // Add click animations to love buttons
        document.querySelectorAll('.love-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                this.classList.add('animate');
                setTimeout(() => {
                    this.classList.remove('animate');
                }, 300);
            });
        });

        // Close mobile nav when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.querySelector('nav');
            const navToggle = document.querySelector('nav button');
            const navMenu = document.getElementById('navbarNav').querySelector('ul');
            
            if (!nav.contains(event.target) && navMenu.classList.contains('show')) {
                navMenu.classList.remove('show');
            }
        });
    </script>
</body>
</html>