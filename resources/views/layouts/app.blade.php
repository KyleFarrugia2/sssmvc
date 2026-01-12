<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Template base: Glassmorphism form from freefrontend.com/bootstrap-forms -->
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.1), transparent 25%),
                        radial-gradient(circle at 90% 10%, rgba(16, 185, 129, 0.1), transparent 25%),
                        linear-gradient(135deg, #0f172a, #0b1120 45%, #0f172a);
            color: #e2e8f0;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
            backdrop-filter: blur(12px);
            border-radius: 18px;
        }
        .nav-link.active {
            font-weight: 600;
        }
        .form-floating label {
            color: #cbd5e1;
        }
        a, a:hover {
            color: #a5b4fc;
        }
        .form-control, .form-select {
            color: #e2e8f0 !important;
            background-color: rgba(0, 0, 0, 0.2) !important;
        }
        .form-control:focus, .form-select:focus {
            color: #e2e8f0 !important;
            background-color: rgba(0, 0, 0, 0.3) !important;
            border-color: rgba(165, 180, 252, 0.5) !important;
            box-shadow: 0 0 0 0.25rem rgba(165, 180, 252, 0.25) !important;
        }
        .form-control::placeholder {
            color: #94a3b8 !important;
            opacity: 0.7;
        }
        input[type="date"], input[type="text"], input[type="number"], select {
            color: #e2e8f0 !important;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.7;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark border-bottom border-secondary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}"><i class="bi bi-clipboard2-pulse"></i> NutriTrack</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('meal-plans.*') ? 'active' : '' }}" href="{{ route('meal-plans.index') }}">Meal Plans</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('foods.*') ? 'active' : '' }}" href="{{ route('foods.index') }}">Foods</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('calorie-calculator.*') ? 'active' : '' }}" href="{{ route('calorie-calculator.index') }}"><i class="bi bi-calculator"></i> Calculator</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}"><i class="bi bi-person-plus"></i> Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @include('partials.alerts')
    @yield('content')
</main>

<footer class="container pb-4 text-secondary small">
    <div class="d-flex align-items-center justify-content-between glass-card px-3 py-2">
        <span>Laravel 10 | Nutrition MVP</span>
        <span>Inspired by <a href="https://freefrontend.com/bootstrap-forms/" target="_blank" rel="noreferrer">freefrontend forms</a></span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
