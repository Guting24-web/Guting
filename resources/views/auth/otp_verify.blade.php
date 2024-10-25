@extends('layout.app')

@section('content')
<div class="container">
    <h2>Verify OTP</h2>

    {{-- Display success or error messages --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- OTP Verification Form --}}
    <form action="{{ route('profile.otp.verify') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="otp">Enter OTP</label>
            <input type="text" name="otp" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Verify OTP</button>
    </form>
</div>
@endsection
