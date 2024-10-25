<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Ensure this line is present

class GutingAdminUserController extends Controller
{
    // List and search users
    public function index(Request $request)
    {
        // Get the number of records per page from the request, defaulting to 10
        $perPage = $request->input('per_page', 10); 
        $search = $request->input('search');
    
        // Start the query to fetch users
        $query = User::query();
    
        // Filter by name or email if the search term is provided
        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
        }
    
        // Paginate the results based on the number of records per page
        $users = $query->paginate($perPage);
    
        // Return the view with the users, search term, and records per page
        return view('admin.rolandouser', compact('users', 'search', 'perPage'));
    }
    
    // Create form view
    public function create()
    {
        // Paginate users for the view
        $users = User::paginate(10);
        return view('admin.rolandouser', compact('users'));
    }

    // Store new user in the database
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'is_active' => 'required|boolean', // Ensure is_active is a boolean
        ]);

        // Create a new user
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
            'is_active' => $validatedData['is_active'],
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    // Edit user
    public function edit(User $user)
    {
        // Paginate users for the edit view
        $users = User::paginate(10);
        return view('admin.rolandouser', compact('user', 'users'));
    }

    // Update user details
    public function update(Request $request, User $user)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',
            'is_active' => 'required|boolean', // Ensure is_active is a boolean
        ]);

        // Update the user's information
        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    // Delete user
    public function destroy(User $user)
    {
        // Delete the user
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    // Confirm delete action for user
    public function delete($id)
    {
        // Attempt to find the user by ID
        $user = User::find($id);
    
        // Check if the user exists
        if (!$user) {
            \Log::error("User not found with ID: $id");
            return redirect()->route('users.index')->with('error', 'User not found');
        }
    
        // Retrieve all users for the view (if needed for listing)
        $users = User::paginate(10); // Pagination instead of `all()`
    
        // Pass the user data to the view along with a delete flag
        return view('admin.rolandouser', compact('users', 'user'))->with('deleteUser', true);
    }
    
    // Show user details
    public function show($id)
    {
        // Retrieve a single user
        $user = User::find($id);
    
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }
    
        return view('admin.rolandouser', compact('user')); // Pass the user to the view
    }
}
