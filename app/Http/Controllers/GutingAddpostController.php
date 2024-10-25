<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; 
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GutingAddpostController extends Controller
{
    public function index()
    {
        $posts = Post::with('comments')
        ->where('author', Auth::user()->name) // Filter by the logged-in user's name
        ->orderBy('created_at', 'desc')
        ->paginate(10);

return view('user.rolandoaddpost', compact('posts'));
    }

    // Store a new blog post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
            'image' => 'nullable|image|max:2048',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => Auth::user()->name,
            'is_published' => $request->has('is_published'),
           
        ]);

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('images', 'public'); 
            $post->save(); 
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'post_created',
            'description' => 'New post created.',
        ]);

        return redirect()->route('addpost.index')->with('success', 'Post created successfully!');
    }

    // Show the form for editing a post
    public function edit(Post $post)
    {
        $posts = Post::with('comments')
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('user.rolandoaddpost', compact('post', 'posts'));
    }

    // Update an existing post
    public function update(Request $request, Post $post)
    { 
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
            'image' => 'nullable|image|max:2048',
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_published' => $request->has('is_published'),
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->image = $request->file('image')->store('images', 'public');
        }

        $post->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'post_updated',
            'description' => 'Post updated.',
        ]);

        return redirect()->route('addpost.index')->with('success', 'Post updated successfully!');
    }

    
    public function destroy($id)
    {
        $post = Post::find($id);

        // Check if the post exists before deleting
        if (!$post) {
            return redirect()->route('addpost.index')->with('error', 'Post not found.');
        }

        $post->delete();
        return redirect()->route('addpost.index')->with('success', 'Post deleted successfully.');
    }

    public function togglePublish(Post $post)
    {
        $post->is_published = !$post->is_published; // Toggle the boolean value
        $post->save(); // Save the changes

        return redirect()->route('addpost.index')->with('success', 'Post publication status updated successfully.');
    }

    public function delete($id)
    {
        // Attempt to find the post by ID
        $post = Post::find($id);

        // Check if the post exists
        if (!$post) {
            \Log::error("Post not found with ID: $id");
            return redirect()->route('addpost.index')->with('error', 'Post not found');
        }

        // Pass the post data to the view along with a delete flag
        return view('user.rolandoaddpost', [
            'post' => $post,
            'deletePost' => true,
            'posts' => Post::paginate(10),  // Use paginate here as well
        ]);
    }
}
