<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class GutingProfileController extends Controller
{
    // Show the edit form
    public function edit()
    {
        return view('user.edit-profile');
    }

    // Update the profile information (name and email)
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        // Update user information
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    // Step 1: Update password with OTP flow
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Generate OTP and send it to the user's email
        $otp = rand(100000, 999999);
        Mail::to($user->email)->send(new \App\Mail\OtpMail($otp, $user->name));

        // Store OTP and new password in session
        $request->session()->put('otp', $otp);
        $request->session()->put('new_password', $request->password);

        // Redirect to OTP verification form
        return redirect()->route('profile.otp.form');
    }

    // Step 2: Show OTP form
    public function showOtpForm()
    {
        return view('auth.otp_verify');
    }

    // Step 3: Verify OTP and update password
    public function verifyOtp(Request $request)
    {
        // Validate the OTP input
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        // Retrieve OTP and new password from session
        $sessionOtp = $request->session()->get('otp');
        $newPassword = $request->session()->get('new_password');

        // Check if the provided OTP matches the session OTP
        if ($request->otp == $sessionOtp) {
            // OTP is correct, proceed to update the password
            $user = Auth::user();
            $user->password = Hash::make($newPassword);
            $user->save();

            // Clear the session data (OTP and new password)
            $request->session()->forget(['otp', 'new_password']);

            return redirect()->route('profile.edit')->with('success', 'Password changed successfully.');
        }

        // OTP is incorrect
        return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
    }
}
