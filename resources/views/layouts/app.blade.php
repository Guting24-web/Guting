<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>User</title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link {
            color: white; /* Ensures text color is visible */
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 5px;
        }

        .badge i {
            margin-right: 5px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        /* Styles for the navbar */
        .navbar {
            padding: 0.5rem 1rem;
            background-color: #007bff; /* Ensure a solid background color */
        }

        .navbar-toggler {
            background-color: white;
            border: none;
        }

        .navbar-collapse {
            transition: transform 0.3s ease-in-out;
        }

        .navbar-collapse.collapsing {
            transform: scaleY(0);
        }

        .navbar-collapse.show {
            transform: scaleY(1);
        }

        @media (max-width: 992px) {
            .nav {
                flex-direction: column;
                text-align: center;
            }

            .navbar-collapse {
                transform-origin: top;
                display: none;
            }

            .navbar-collapse.show {
                display: block;
            }
            .nav-link.active {
    background-color: rgba(255, 255, 255, 0.4);
    border-radius: 5px;
    color: #007bff; /* Change this to the color you want for the active link */
}

        }
    </style>
</head>
<body>
<div class="container">
    <!-- Page Content -->
    <div class="navbar navbar-expand-lg bg-primary text-white shadow-sm">
        <h4 class="mb-0 text-white"><i class="fas fa-tachometer-alt"></i> Dashboard</h4>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <nav class="nav ms-auto">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
        
                <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i> Users
                </a>
        
                <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="fas fa-list"></i> Category
                </a>
        
                <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="fas fa-box"></i> Products
                </a>
        
                <a class="nav-link {{ request()->routeIs('charts') ? 'active' : '' }}" href="{{ route('charts') }}">
                    <i class="fas fa-chart-line"></i> Reports
                </a>

                <a class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                    <i class="fas fa-pencil"></i> Post
                </a>
        
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
        
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>
        </div>
        
    </div>

    <div class="container">
        @yield('content')
    </div>


    <script src="{{ asset('js/app.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</body>
</html>
