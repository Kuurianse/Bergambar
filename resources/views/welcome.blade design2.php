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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Navbar Styles */
        nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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
            font-size: 1.5rem;
            cursor: pointer;
            color: #333;
        }

        #navbarNav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        #navbarNav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        #navbarNav a:hover {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        /* Dropdown styles */
        .dropdown {
            position: relative;
        }

        .dropdown > div {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            padding: 0.5rem 0;
            display: none;
            z-index: 1001;
        }

        .dropdown:hover > div {
            display: block;
        }

        .dropdown > div a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #333;
            border-radius: 0;
        }

        .dropdown > div a:hover {
            background: #f8f9ff;
            transform: none;
            box-shadow: none;
        }

        /* Hero Section */
        .hero {
            padding: 4rem 2rem;
            text-align: center;
            color: white;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
            min-height: 500px;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .hero-content p {
            font-size: 1.3rem;
            font-weight: 300;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .hero img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .hero img:hover {
            transform: scale(1.05) rotate(2deg);
        }

        /* Commission Section */
        .commission-section {
            background: white;
            margin: 2rem;
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .commission-section h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 3rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .commissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .commission-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
            overflow: hidden;
        }

        .commission-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .commission-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(102, 126, 234, 0.2);
        }

        .commission-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .commission-header h5 {
            flex: 1;
            margin-right: 1rem;
        }

        .commission-header h5 a {
            text-decoration: none;
            color: #333;
            font-size: 1.3rem;
            font-weight: 600;
            line-height: 1.4;
            transition: color 0.3s ease;
        }

        .commission-header h5 a:hover {
            color: #667eea;
        }

        .love-section {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(102, 126, 234, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #667eea;
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
            font-size: 1.2rem;
            font-weight: 700;
            color: #667eea;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .commission-image {
            margin-top: 1.5rem;
        }

        .commission-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .commission-image img:hover {
            transform: scale(1.02);
        }

        .no-commissions {
            text-align: center;
            padding: 3rem;
            color: #666;
            font-size: 1.1rem;
        }

        /* Status badges */
        .status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status.available {
            background: #d4edda;
            color: #155724;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.completed {
            background: #cce5ff;
            color: #004085;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            nav button {
                display: block;
            }

            #navbarNav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
                border-radius: 0 0 15px 15px;
                padding: 1rem;
            }

            #navbarNav.show {
                display: block;
            }

            #navbarNav ul {
                flex-direction: column;
                gap: 0.5rem;
            }

            .hero {
                grid-template-columns: 1fr;
                text-align: center;
                padding: 2rem 1rem;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

            .commission-section {
                margin: 1rem;
                padding: 2rem 1rem;
            }

            .commissions-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .commission-card {
            animation: fadeIn 0.6s ease forwards;
        }

        .commission-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .commission-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .commission-card:nth-child(4) {
            animation-delay: 0.3s;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div>
            <a href="/">
                <img src="{{ asset('assets/Logo.png') }}" alt="Logo">
            </a>
            <button type="button" onclick="toggleNav()" aria-label="Toggle navigation">
                <span>☰</span>
            </button>
            <div id="navbarNav">
                <ul>
                    <li>
                        <a href="{{ route('artists.index') }}">
                         Artists
                        </a>
                    </li>
                    @auth
                    <li>
                        <a href="{{ route('commissions.index') }}">
                        Commissions
                        </a>
                    </li>
                    @endauth

                    @auth
                        <li class="dropdown">
                            <a href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div>
                                <a href="{{ route('users.show', Auth::user()->id) }}">
                                    Edit Profile
                                </a>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}">
                                Login
                            </a>
                        </li>
                    @endauth

                    @auth
                    <li>
                        <a href="{{ route('chat.index') }}">
                            Chat
                        </a>
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
        <img src="{{ asset('assets/hero.png') }}" alt="Hero Image">
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
                    <div class="commission-header">
                        <h5>
                            <a href="{{ route('commissions.order', $commission->id) }}">
                                {{ $commission->description }}
                            </a>
                        </h5>
                        <div class="love-section">
                            @auth
                                @if($commission->loves->contains(auth()->user()))
                                    <span>💖</span>
                                @else
                                    <span>🤍</span>
                                @endif
                            @else
                                <span>🤍</span>
                            @endauth
                            <span>{{ $commission->loved_count }}</span>
                        </div>
                    </div>
                    <div class="commission-details">
                        <p>Made by: {{ $commission->user->name }}</p>
                        <p>Status: <span class="status {{ strtolower($commission->public_status) }}">{{ $commission->public_status }}</span></p>
                        <p class="price">Price: Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>
                        @if($commission->image)
                            <div class="commission-image">
                                <img src="{{ asset('storage/' . $commission->image) }}" alt="Commission Image">
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        function toggleNav() {
            const nav = document.getElementById('navbarNav');
            nav.classList.toggle('show');
        }

        // Close nav when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('navbarNav');
            const button = document.querySelector('nav button');
            
            if (!nav.contains(event.target) && !button.contains(event.target)) {
                nav.classList.remove('show');
            }
        });
    </script>
</body>
</html>