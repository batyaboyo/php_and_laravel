<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="{{ route('books.index') }}">
            📚 Library Management System
        </a>

        <div class="ms-auto">

            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-light me-2">
                    Login
                </a>

                <a href="{{ route('register') }}" class="btn btn-success">
                    Register
                </a>
            @endguest

            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                Profile
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    Logout
                                </button>
                            </form>
                        </li>

                    </ul>
                </div>
            @endauth

        </div>

    </div>
</nav>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>