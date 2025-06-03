{{-- Original Path: resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bergambar</title>
</head>
<body>
    <div>
    <!-- Navbar -->
    <nav>
        <div>
            <a href="/">
                <img src="{{ asset('assets/Logo.png') }}" alt="Logo">
            </a>
            <button type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span></span>
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
                        <li>
                            <a href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
    <div>
        <div>
            <h1>Welcome to Bergambar</h1>
            <p>Arts for all, from heart to crafts</p>
        </div>
        <img src="{{ asset('assets/hero.png') }}" alt="...">
    </div>

    <!-- Commission Section -->
    <div>
        <h2>Discover Commissions</h2>
        <div>
            @if($commissions->isEmpty())
                <p>No commissions are currently available.</p>
            @else
                @foreach($commissions as $commission)
            <div>
                <div>
                    <div>
                        <div>
                            <h5>
                                <a href="{{ route('commissions.order', $commission->id) }}">
                                    {{ $commission->description }}
                                </a>
                            </h5>
                             @auth
                                @if($commission->loves->contains(auth()->user()))
                                        <span>Loved</span>
                                    @else
                                        <span>Love</span>
                                    @endif
                             @else
                                <span>Love Icon (Guest)</span>
                             @endauth
                            <span>{{ $commission->loved_count }}</span>
                        </div>
                        <p>Made by: {{ $commission->user->name }}</p>
                        <p>Status: {{ $commission->public_status }}</p>
                        <p>Price:  Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>
                        @if($commission->image)
                            <img src="{{ asset('storage/' . $commission->image) }}" alt="Commission Image">
                        @endif
                    </div>
                </div>
            </div>
                @endforeach
            @endif
        </div>
    </div>
</body>
</html>