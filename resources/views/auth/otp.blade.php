<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .otp-container {
            width: 100%;
            max-width: 350px;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        .countdown {
            font-size: 14px;
            color: #555;
            text-align: center;
            margin-top: 10px;
        }

        .form-group label {
            float: left;
        }

        #resendLink {
            float: left;
        }

        .alert {
            font-size: 14px;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="otp-container text-center">
            <h2>Verify OTP</h2>
            <br>

            <!-- Display the resend OTP success message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <div class="form-group">
                    <label for="otp">Enter OTP</label>
                    <input type="text" name="otp" id="otp" class="form-control" placeholder="Enter OTP" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
            </form>
            <div class="mt-3 text-left">
                <a href="{{ route('otp.resend') }}" id="resendLink">Resend OTP</a>
            </div>
            <br><br>
            <p class="countdown">Time remaining: <span id="timer">10:00</span></p>
        </div>
    </div>

    <script>
        function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    display.textContent = "00:00";
                }
            }, 1000);
        }

        window.onload = function () {
            var tenMinutes = 60 * 10,
                display = document.querySelector('#timer');
            startTimer(tenMinutes, display);
        };
    </script>
</body>
</html>
