<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; 
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class GutingBlogController extends Controller
{
    // Display all posts (paginated)
    public function index()
    {
        $posts = Post::where('is_published', true)
                     ->with('comments')
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('user.rolandoblog', compact('posts'));
    }

    // Store a new blog post
    public function store(Request $request)
    {
        // Validate the form data, including title and author
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
            'author' => 'required|string|max:255',
            'image' => 'image|nullable|max:2048',
        ]);

        // Create the post instance
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->Content,
            'author' => $request->author,
            'is_published' => false, // Set to false, require admin approval
        ]);

        // Store the image if provided
        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('images', 'public'); // Store the image in the 'public/images' directory
            $post->save(); // Save after assigning the image
        }

        return redirect()->back()->with('success', 'Post added successfully! Please wait for admin approval.');
    }

    // Show the form for editing a post
    public function edit(Post $post)
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);

        return view('user.rolandoblog', compact('post', 'posts'));
    }

    // Update an existing post
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
         
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->Content,
     
         
        ]);

        // Update the image if a new one is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if necessary
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->image = $request->file('image')->store('images', 'public');
        }

        $post->save();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'post_updated',
            'description' => 'Post updated.',
        ]);

        return redirect()->route('addpost.index')->with('success', 'Post updated successfully!');
    }


    // Delete a post
    public function destroy(Post $post)
    {
        // Delete the image if it exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        // Delete the post
        $post->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'post_deleted',
            'description' => 'User deleted a post.',
        ]);

        return redirect()->route('blog.index')->with('success', 'Post deleted successfully.');
    }

    // Toggle publish status of a post (Admin only)
    public function togglePublish(Post $post)
    {
        $post->is_published = !$post->is_published; // Toggle the boolean value
        $post->save(); // Save the changes

        return redirect()->route('blog.index')->with('success', 'Post publish status updated.');
    }

    // Delete confirmation view
    public function delete(Post $post)
    {
        // Pass the post data to the view along with a delete flag
        return view('admin.rolandopost', [
            'post' => $post,
            'deletePost' => true,
            'posts' => Post::paginate(10),  // Use paginate here as well
        ]);
    }
      
    
}
