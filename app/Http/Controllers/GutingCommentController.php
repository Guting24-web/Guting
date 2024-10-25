<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\Auth;

class GutingCommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the comment data
        $request->validate([
            'content' => 'required|string|max:255',
            'post_id' => 'required|exists:posts,id',
        ]);

        // Create and save the comment
        Comment::create([
            'content' => $request->content,
            'post_id' => $request->post_id,
            'author' => Auth::check() ? Auth::user()->name : 'Guest',
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'comment_added',
            'description' => 'User commented on post ID ' . $request->post_id,
        ]);

        // Redirect back to the post with a success message
        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    public function destroy(Comment $comment)
{
    // Ensure that the user is the author or an admin
    if (Auth::check() && (Auth::user()->name === $comment->author || Auth::user()->isAdmin())) {
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
    return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
}
public function update(Request $request, $id)
{
    $comment = Comment::findOrFail($id); // Find the comment by ID

    $request->validate([
        'content' => 'required|string', // Validate the content
    ]);

    // Update the comment with new data
    $comment->content = $request->input('content');
    $comment->save();

    return redirect()->route('blog.index')->with('success', 'Comment updated successfully!');
}


}
