@extends('layout.app')

@section('content')
<div class="container mt-4">
    <div class="row">

        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <h5 class="mb-3"><i class="fas fa-pencil-alt"></i> Posts</h5>
            @if($posts->count() > 0)
                @foreach($posts as $post)
                <div class="card mb-3 mx-auto" style="max-width: 800px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; overflow: hidden;">
                    <div class="card-header" style="background-color: #f8f9fa; padding: 1rem;">
                        <div class="d-flex align-items-center">
                            <img src="{{ $post->user->profile_image_url ?? 'images/guting.jpg' }}" alt="{{ $post->author }}'s profile" class="rounded-circle" style="width: 40px; height: 40px; margin-right: 10px;">
                            <strong style="color: #007bff;">{{ $post->author }}</strong>
                            <span class="text-muted ms-auto" style="font-size: 0.9rem;">{{ $post->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                    <div class="card-body text-center" style="background-color: #ffffff; padding: 1.5rem;">
                        <h6 class="card-title" style="font-weight: bold; color: #343a40;">{{ $post->title }}</h6>
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-fluid mb-3" style="max-height: 300px; object-fit: cover;">
                        @endif
                        <p class="card-text" style="color: #6c757d; line-height: 1.6;">{{ $post->content }}</p>
                        <div class="d-flex justify-content-center mb-3">
                            @if(Auth::check() && Auth::user()->name === $post->author)
                                <button class="btn btn-sm btn-danger mx-1" style="padding: 0.4rem 0.8rem;" 
                                        onclick="confirmDelete({{ $post->id }})">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="card-footer" style="background-color: #f8f9fa;">
                        <h6 style="font-weight: bold; margin-bottom: 1rem;"><i class="fas fa-comments"></i> Comments</h6>
                        @foreach($post->comments as $comment)
                            <div class="mb-2" style="border-bottom: 1px solid #dee2e6; padding-bottom: 0.8rem; display: flex; justify-content: flex-start;">
                                <div style="flex-grow: 1; text-align: left;">
                                    <strong style="color: #007bff;">{{ $comment->author }}</strong>
                                    <span class="text-muted" style="font-size: 0.85rem;">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                                    <p style="color: #6c757d; margin-top: 0.5rem;" id="comment-content-{{ $comment->id }}">{{ $comment->content }}</p>

                                    <!-- Edit and Delete buttons for the comment author -->
                                    @if(Auth::check() && Auth::user()->name === $comment->author)
                                        <button class="btn btn-sm btn-danger" style="padding: 0.3rem 0.7rem;" 
                                                onclick="confirmDeleteComment({{ $comment->id }})">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    @endif

                                    <form id="delete-comment-form-{{ $comment->id }}" action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        <!-- Add a new comment -->
                        @auth
                        <form action="{{ route('comments.store') }}" method="POST" style="margin-top: 1rem;">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="mb-3">
                                <textarea class="form-control" name="content" rows="2" placeholder="Add a comment..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary" style="padding: 0.3rem 0.7rem;"><i class="fas fa-paper-plane"></i> Post Comment</button>
                        </form>
                        @endauth
                    </div>
                </div>

                <form id="delete-form-{{ $post->id }}" action="{{ route('blog.destroy', $post->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                @endforeach
                {{ $posts->links() }}
            @else
                <div class="alert alert-info"><i class="fas fa-info-circle"></i> No published posts available.</div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for Add Post -->
<div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="addPostModalLabel"><i class="fas fa-plus-circle"></i> What's on your mind?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPostForm" action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="author" value="{{ Auth::check() ? Auth::user()->name : 'Guest' }}">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    <div class="mb-3">
                        <textarea class="form-control" name="content" rows="4" placeholder="Write something..." required style="border: 1px solid #ced4da; border-radius: 20px; resize: none;"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" name="image" accept="image/*" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="submitAddPostForm()"><i class="fas fa-paper-plane"></i> Post</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Edit Post -->
<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel"><i class="fas fa-edit"></i> Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPostForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editContent" class="form-label">Content</label>
                        <textarea class="form-control" id="editContent" name="content" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitEditPostForm()"><i class="fas fa-save"></i> Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    function submitAddPostForm() {
        document.getElementById('addPostForm').submit();
    }

    function editPost(id, title, content) {
        document.getElementById('editPostModal').querySelector('#editTitle').value = title;
        document.getElementById('editPostModal').querySelector('#editContent').value = content;
        document.getElementById('editPostForm').action = `/blog/edit/${id}`; // Adjust the route as necessary
        var myModal = new bootstrap.Modal(document.getElementById('editPostModal'));
        myModal.show();
    }

    function submitEditPostForm() {
        document.getElementById('editPostForm').submit();
    }

    function confirmDelete(postId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + postId).submit();
            }
        });
    }

    function confirmDeleteComment(commentId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-comment-form-' + commentId).submit();
            }
        });
    }
</script>
@endsection
