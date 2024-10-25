<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GutingPostController extends Controller
{
    // Display the list of posts
    public function index()
    {
        $posts = Post::paginate(10); // Example of paginated posts
        return view('admin.rolandopost', compact('posts'));
    }

    // Store a new post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'author' => 'required',
            'image' => 'image|nullable|max:2048',
        ]);

        // Create the post instance
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,  // Ensure this is passed
            'is_published' => $request->has('is_published'),
        ]);

        // Check if an image is uploaded
        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('images', 'public'); // Store the image in the 'public/images' directory
            $post->save(); // Save after assigning the image
        }

        return redirect()->route('posts.index')->with('success', 'Post added successfully.');
    }

    // Edit a specific post
    public function edit(Post $post)
    {
        // Get all posts for the list display
        $posts = Post::paginate(10);
        return view('admin.rolandopost', compact('post', 'posts')); // Pass both the single post and the list of posts
    }

    // Update an existing post
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'author' => 'required', // Ensure author is validated
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author, // Update the author field
            'is_published' => $request->has('is_published'), // Optional if needed
        ]);

        if ($request->hasFile('image')) {
            // Delete the old image if necessary
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->image = $request->file('image')->store('images', 'public');
        }
    
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    // Delete a post
    public function destroy($id)
    {
        $post = Post::find($id);

        // Check if the post exists before deleting
        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function togglePublish(Post $post)
    {
        $post->is_published = !$post->is_published; // Toggle the boolean value
        $post->save(); // Save the changes

        return redirect()->route('posts.index')->with('success', 'Post publication status updated successfully.');
    }

    // Delete confirmation view
    public function delete($id)
    {
        // Attempt to find the post by ID
        $post = Post::find($id);

        // Check if the post exists
        if (!$post) {
            \Log::error("Post not found with ID: $id");
            return redirect()->route('posts.index')->with('error', 'Post not found');
        }

        // Pass the post data to the view along with a delete flag
        return view('admin.rolandopost', [
            'post' => $post,
            'deletePost' => true,
            'posts' => Post::paginate(10),  // Use paginate here as well
        ]);
    }
}


