<?php

namespace App\Http\Controllers;

use App\Models\Category; // Ensure this model is imported
use Illuminate\Http\Request;

class GutingCategoryController extends Controller
{
    public function index()
    {
        // Use paginate() instead of get() to retrieve paginated results
        $categories = Category::paginate(10); // Adjust the number to your needs

        // Pass the paginated result to the view
        return view('admin.rolandocategories', compact('categories'));
    }

    public function create()
    {
        // Fetch categories for the create view
        $categories = Category::paginate(10);
        return view('admin.rolandocategories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Category::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        $categories = Category::paginate(10);
        return view('admin.rolandocategories', compact('categories', 'category')); // Pass the singular 'category'
    }

    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $category->update($validatedData);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }

    public function delete($id)
    {
        // Attempt to find the category by ID
        $category = Category::find($id);

        // Check if the category exists
        if (!$category) {
            \Log::error("Category not found with ID: $id");
            return redirect()->route('categories.index')->with('error', 'Category not found');
        }

        // Pass the category data to the view along with a delete flag
        return view('admin.rolandocategories', [
            'category' => $category,
            'deleteCategory' => true,
            'categories' => Category::paginate(10), // Use paginate here as well
        ]);
    }
}
