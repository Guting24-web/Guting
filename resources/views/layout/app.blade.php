<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guting Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .nav-link {
            color: #555;
        }
        .nav-link:hover {
            color: #007bff;
            font-weight: bold;
        }
        .nav-link.active {
            color: #007bff;
            font-weight: bold;
        }
        .nav-brand {
            font-weight: bold;
            color: #333;
        }
        .logout-btn {
            margin-left: 20px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background-color: #ffffff;">
    <div class="container">
        <a class="navbar-brand nav-brand" href="#">User Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'user.dashboard' ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'activity.log' ? 'active' : '' }}" href="{{ route('activity.log') }}">
                        <i class="fas fa-file-alt"></i> Activity Logs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'addpost.index' ? 'active' : '' }}" href="{{ route('addpost.index') }}">
                        <i class="fas fa-plus-circle"></i> Add Post
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'blog.index' ? 'active' : '' }}" href="{{ route('blog.index') }}">
                        <i class="fas fa-blog"></i> Blog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'profile.edit' ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-edit"></i> Profile
                    </a>
                </li>
                @if (auth()->user()->can('create-posts'))
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'posts.create' ? 'active' : '' }}" href="{{ route('posts.create') }}">
                        <i class="fas fa-pencil-alt"></i> Create Post
                    </a>
                </li>
                @endif
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger btn-sm logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main content area -->
<div class="container mt-5">
    <!-- Dynamic content will be injected here -->
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
