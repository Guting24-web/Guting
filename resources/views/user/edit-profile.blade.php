{{-- resources/views/auth/edit-profile.blade.php --}}

@extends('layout.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-user-edit"></i> Edit Profile</h2>
    
    {{-- Display success or error messages --}}
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Profile Update Form --}}
    <form action="{{ route('profile.update') }}" method="POST" class="mb-4">
        @csrf
        @method('PUT')

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5><i class="fas fa-user"></i> Update Profile</h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="name"><i class="fas fa-user"></i> Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Profile</button>
            </div>
        </div>
    </form>

    {{-- Password Update Form --}}
    <h4 class="mb-3"><i class="fas fa-lock"></i> Change Password</h4>
    <form action="{{ route('profile.update.password') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5><i class="fas fa-lock"></i> Password Change</h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="current_password"><i class="fas fa-key"></i> Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password"><i class="fas fa-key"></i> New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation"><i class="fas fa-key"></i> Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-lock-open"></i> Change Password</button>
            </div>
        </div>
    </form>
</div>
@endsection
