<?php


namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GutingUserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login'); // Or handle unauthorized access accordingly
        }

     //para sa user   // Count posts and comments
        $totalPosts = Post::where('author', $user->name)->count();
        $totalComments = Comment::where('author', $user->name)->count();
        $approvedPosts = Post::where('author', $user->name)->where('is_published', true)->count();
        $activityLogs = ActivityLog::where('user_id', $user->id)->get(); 

        return view('user.rolandodashboard', compact('totalPosts', 'totalComments', 'approvedPosts', 'activityLogs'), ['user' => $user]);
    }   //yung isa o ito

    public function updateProfile(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(), // Ensure email is unique except for the current user
            'password' => 'nullable|string|min:8', // Password is optional; it can be left blank
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Update user details
        $user->name = $request->name;
        $user->email = $request->email;

        // If a new password is provided, hash it and update
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the updated user information
        $user->save();

        
        
        return redirect()->route('user.dashboard')->with('succes', 'Profile updated successfully.');
    }

    public function deactivate(Request $request)
{
    $user = Auth::user();

    if ($user) {
        $user->is_active = 'inactive';
        $user->save();

        // Log out the user after deactivation
        Auth::logout(); // Log the user out
        return redirect('/login')->with('success', 'Your account has been deactivated.');
    }

    return redirect()->back()->withErrors(['error' => 'Unable to deactivate account.']);
}

}
