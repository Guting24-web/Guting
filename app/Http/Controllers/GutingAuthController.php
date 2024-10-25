<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;


class GutingAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.rolandoregister');
    }

    public function register(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'g-recaptcha-response' => 'required|captcha',
        ]);
    
        // Assign default role as 'admin' for now
        $role = 'admin'; // You can make this dynamic later if needed

        // Log the request data
        \Log::info($request->all());

        // Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role, // Defaulting to 'admin'
        ]);

        return redirect('/login')->with('success', 'User registered successfully.');
    }

    public function showLoginForm()
    {
        return view('auth.rolandologin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            // Check if the user is active
            if ($user->is_active === 'active') { // Check if active
                // Generate OTP
                $otp = rand(100000, 999999);
    
                // Send the OTP email
                Mail::to($user->email)->send(new \App\Mail\OtpMail($otp, $user->name));
    
                // Store the OTP in session
                $request->session()->put('otp', $otp);
    
                // Redirect to OTP form
                return redirect()->route('otp.form');
            } else {
                // If the user is not active, log them out and show an error
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is inactive. Please contact support.']);
            }
        }
    
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }
    
    

    public function showOtpForm()
    {
        return view('auth.otp'); 
    }

    public function verifyOtp(Request $request)
    {
        // Validate the OTP input
        $request->validate([
            'otp' => 'required|numeric',
        ]);
    
        // Get the OTP stored in the session
        $sessionOtp = $request->session()->get('otp');
    
        // Check if the provided OTP matches the session OTP
        if ($request->otp == $sessionOtp) {
            // Forget the OTP from the session
            $request->session()->forget('otp');
    
            // Fetch the authenticated user
            $user = auth()->user();
    
            // Check the user role and redirect accordingly
            if ($user && $user->role === 'admin') {
                return redirect()->route('admin.dashboard'); // Redirect to admin dashboard
            } else {
                return redirect()->route('user.dashboard'); // Redirect to user dashboard
            }
        }
    
        // Return back with an error if the OTP is invalid
        return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
    }
    
    public function resendOtp(Request $request)
{
    $user = Auth::user();
    
    if ($user) {
        // Generate new OTP
        $otp = rand(100000, 999999);

        // Send the OTP email
        Mail::to($user->email)->send(new \App\Mail\OtpMail($otp, $user->name));

        // Store the new OTP in session
        $request->session()->put('otp', $otp);

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    return redirect()->route('login')->withErrors('Unable to resend OTP. Please try again.');
}

public function dashboard() {
    $totalCategories = Category::count(); // Ensure this variable is defined
    $totalUsers = User::count();
    $totalProducts = Product::count();

    return view('admin.rolandodashboard', compact('totalCategories', 'totalUsers', 'totalProducts'));
}



    public function adminDashboard()
    {
        return view('admin.rolandodashboard'); 
    }

    public function userDashboard()
    {
        return view('userdashboard'); 
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
