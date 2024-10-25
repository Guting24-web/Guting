@extends('layout.app')

@section('content')

<div class="container">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Page Content -->
    <style>
        body {
            background-color: #f0f2f5;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #43a5be;
            color: white;
            font-weight: bold;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .list-group-item {
            border: none;
            background-color: #ffffff;
            transition: background-color 0.3s;
        }

        .list-group-item:hover {
            background-color: #e9ecef;
        }

        .profile-card {
            width: 300px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid #e0e0e0;
            margin: 20px auto;
        }

        .profile-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 2px solid #ddd;
        }

        .profile-card h2 {
            font-size: 1.5rem;
            color: #333;
            font-weight: bold;
        }

        .profile-card p {
            margin: 10px 0 0;
            font-size: 0.9rem;
            color: #777;
        }

        .profile-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px;
            gap: 20px;
        }

        .update-profile-card {
            flex-grow: 1;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center mb-4"><i class="fas fa-user-circle"></i> User Profile</h1>

            <!-- Dashboard Statistics Section -->
            <div class="card mb-4">
                <div class="card-header">Dashboard Statistics</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-black mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-file-alt"></i> Total Posts</h5>
                                    <p class="card-text"><strong>{{ $totalPosts }}</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-comments"></i> Total Comments</h5>
                                    <p class="card-text"><strong>{{ $totalComments }}</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white mb-3" style="background-color: #ff66b2;">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-check-circle"></i> Approved Posts</h5>
                                    <p class="card-text"><strong>{{ $approvedPosts }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-container">
                <!-- Profile Card -->
                <div class="profile-card">
                    <img src="{{ asset('images/guting.jpg' . $user->profile_image) }}" alt="Profile Image">
                    <h2>{{ $user->name }}</h2>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                </div>

                <!-- Update Profile Card -->
                <div class="update-profile-card">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-user-edit"></i> Update Profile
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('update.profile') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user"></i> Name
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope"></i> Email
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock"></i> Password
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                                </div>
                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">
                                        <i class="fas fa-image"></i> Profile Image
                                    </label>
                                    <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-danger">Delete Account</div>
                        <div class="card-body">
                            <p>Are you sure you want to deactivate your account? This action cannot be undone.</p>
                            <form action="{{ route('user.deactivate') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Deactivate My Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Activity Logs</div>
                <div class="card-body">
                    <ul class="list-group">
                        @if($activityLogs->isEmpty())
                            <li class="list-group-item">No activity logs found.</li>
                        @else
                            @foreach($activityLogs as $log)
                                <li class="list-group-item">
                                    <strong>{{ $log->action }}</strong> on <strong>{{ $log->created_at->format('F j, Y') }}</strong>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
