<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .login-container {
            max-width: 350px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #ffffff;
            width: 100%;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        label {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Display success message for new user registration -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="text-center">Login</h2>
    <form method="POST" action="/login">
        @csrf
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-custom btn-block">Login</button>
    </form>
    <div class="text-center mt-3">
        <p>Don't have an account? <a href="/register">Go to Register</a></p>
    </div>
</div>

</body>
</html>
