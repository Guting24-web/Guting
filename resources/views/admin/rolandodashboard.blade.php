@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        <!-- Categories, Users, and Products Cards -->
        <div class="row">
            <!-- Categories Card -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <i class="fas fa-th-list fa-2x text-primary"></i>
                        <h5 class="card-title mt-3">Total Categories</h5>
                        <p class="card-text">{{ $totalcategories }}</p> <!-- Use $totalCategories -->
                    </div>
                </div>
            </div>
            <!-- Users Card -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x text-success"></i>
                        <h5 class="card-title mt-3">Total Users</h5>
                        <p class="card-text">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
            <!-- Products Card -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <i class="fas fa-box fa-2x text-warning"></i>
                        <h5 class="card-title mt-3">Total Products</h5>
                        <p class="card-text">{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow">
                <div class="card-body">
                    <div id="otpAlert" class="alert alert-success" style="display: none;">
                        OTP verified successfully. Welcome, admin.
                    </div>
                    @if(Auth::check())
                        <div class="alert alert-info">
                            Welcome, {{ Auth::user()->name }}!
                        </div>
                        <div class="alert alert-info">
                            Your email: {{ Auth::user()->email }}
                        </div>
                    @else
                        <div class="alert alert-warning">
                            You are not logged in!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to show alert
        function showAlert() {
            var alertElement = document.getElementById('otpAlert');

            // Show the alert
            alertElement.style.display = 'block';

            // Hide after 2 seconds (2000 milliseconds)
            setTimeout(function() {
                alertElement.style.display = 'none';
            }, 2000);
        }

        // Call the function to show the alert when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            showAlert();
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
