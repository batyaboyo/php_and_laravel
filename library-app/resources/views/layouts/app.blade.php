<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Library System') }} — Library Management System</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('books.index') }}">
                Library Management System
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('books.*') ? 'active fw-semibold' : '' }}"
                                href="{{ route('books.index') }}">
                                Books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('my-books') ? 'active fw-semibold' : '' }}"
                                href="{{ route('my-books') }}">
                                My Books
                            </a>
                        </li>
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('members.*') ? 'active fw-semibold' : '' }}"
                                    href="{{ route('members.index') }}">
                                    Members
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2"
                                type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{ Auth::user()->name }}</span>
                                <span
                                    class="badge {{ Auth::user()->membership_status === 'suspended' ? 'bg-danger' : 'bg-success' }}">
                                    {{ ucfirst(Auth::user()->membership_status ?? 'active') }}
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                                <li class="px-3 py-2 bg-light border-bottom">
                                    <div class="small text-muted">Membership #</div>
                                    <div class="fw-bold text-dark">
                                        {{ Auth::user()->membership_number ?? 'LIB-' . str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="small text-muted mt-1">Role: <span
                                            class="fw-semibold text-dark">{{ ucfirst(Auth::user()->role) }}</span></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        Profile Settings
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            Log Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Log In</a>
                        <a href="{{ route('register') }}" class="btn btn-warning btn-sm fw-semibold">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container py-4 flex-grow-1">
        <!-- Suspended Membership Global Banner -->
        @auth
            @if (Auth::user()->membership_status === 'suspended')
                <div class="alert alert-danger shadow-sm mb-4 border-danger" role="alert">
                    <h6 class="alert-heading fw-bold mb-1">Account Suspended</h6>
                    Your library membership status is currently <strong>Suspended</strong>. You are blocked from borrowing new
                    books until your membership is reactivated by an administrator.
                </div>
            @endif
        @endauth

        <!-- Flash Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container">
            &copy; {{ date('Y') }} Library Management System. All rights reserved.
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>