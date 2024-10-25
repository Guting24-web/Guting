<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GutingActivityLogController extends Controller
{
    public function index()
    {
        // Fetch activity logs created by the authenticated user
        $logs = ActivityLog::with('user')
                    ->where('user_id', Auth::id()) // Filter by the logged-in user's ID
                    ->latest()
                    ->get();
    
        return view('user.rolandoactivity_log', compact('logs'));
    }

    public function store(Request $request)
{
    // Validate the request if needed
    $request->validate([
        'action' => 'required|string|max:255',
        'description' => 'required|string|max:255',
    ]);

    // Create an ActivityLog entry
    ActivityLog::create([
        'action' => $request->input('action'),
        'description' => $request->input('description'),  
        'user_id' => auth()->id(),            
    ]);

    // Return a response or redirect as needed
    return redirect()->back()->with('success', 'Activity logged successfully!');
}
}
