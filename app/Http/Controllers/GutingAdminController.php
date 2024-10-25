<?php

// app/Http/Controllers/AdminController.php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class GutingAdminController extends Controller
{

    public function index()
    {
        $users = User::paginate(10); // Fetch users with pagination
        return view('admin.rolandouser', compact('users'));
    }
    
    public function adminDashboard()
    {
       //para sa admin // Fetch the totals
        $totalcategories = Category::count();
        $totalUsers = User::count();
        $totalProducts = Product::count();

        // Pass the totals to the view
        return view('admin.rolandodashboard', compact('totalcategories', 'totalUsers', 'totalProducts'));
    }
}
