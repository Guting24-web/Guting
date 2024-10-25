<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GutingProductsController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10); // Eager load category
        $categories = Category::all();
        return view('admin.rolandoproducts', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stockquantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Optional image validation
        ]);
    
        $product = new Product();
        $product->category_id = $request->category_id;
        $product->name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stockquantity = $request->stockquantity;
    
        if ($request->hasFile('image')) {
            $filename = $request->file('image')->store('products', 'public'); // Store the image
            $product->image = $filename;
        }
    
        $product->save();
    
        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }
    

    public function edit(Product $product)
    {
        $categories = Category::all();
        $products = Product::with('category')->paginate(10); // Fetch products for the table
        return view('admin.rolandoproducts', compact('product', 'categories', 'products'));
    }
    

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stockquantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stockquantity = $request->stockquantity;
        $product->category_id = $request->category_id;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete the image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function delete($id)
    {
        // Attempt to find the product by ID
        $product = Product::find($id);
    
        // Check if the product exists
        if (!$product) {
            \Log::error("Product not found with ID: $id");
            return redirect()->route('products.index')->with('error', 'Product not found');
        }
    
        // Pass the product data to the view along with a delete flag
        return view('admin.rolandoproducts', [
            'product' => $product,
            'deleteProduct' => true,
            'products' => Product::with('category')->paginate(10), // Use paginate here as well
        ]);
    }
}
