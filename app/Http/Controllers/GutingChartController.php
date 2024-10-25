<?php

namespace App\Http\Controllers;

use App\Models\Category;  // Import Category model
use Illuminate\Http\Request;

class GutingChartController extends Controller
{
    // Method to show charts
    public function showCharts()
    {
        // Retrieve categories and number of products in each category
        $categories = Category::withCount('products')->get();

        $totalProducts = $categories->sum('products_count');
    
        return view('admin.rolandocharts', compact('categories'));
    }

    // Method to show product form
    public function showProductForm()
    {
        // Retrieve all categories
        $categories = Category::all();

        // Pass the categories to the view
        return view('products.create', compact('categories'));
    }
}
