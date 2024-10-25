<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your OTP Code</title>
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f0f0; margin: 0;">
    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px; width: 300px;">
        <h1 style="color:white;background-color: #007BFF; text-align: center; padding: 20px; margin: 0; box-sizing: border-box;">
            Hello {{ $name }}! <!-- Include the user's name -->
        </h1>
       
        <p>Thank you for registering with us! To complete your sign-up process, please verify your email address using the OTP below.</p>
        
        <h2 style="color:#007BFF;"> <strong>{{ $otp }}</strong></h2>
    </div>
</body>
</html>
