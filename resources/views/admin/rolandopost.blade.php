@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Post Form Section -->
        <div class="col-md-12 col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-light text-center">
                    @if(isset($post) && !isset($deletePost))
                        <h5>Edit Post</h5>
                    @elseif(isset($deletePost) && isset($post))
                        <h5>Confirm Delete Post</h5>
                    @else
                        <h5>Add Post</h5>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <form action="{{ isset($deletePost) ? route('posts.destroy', $post->id) : (isset($post) ? route('posts.update', $post->id) : route('posts.store')) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($post) && !isset($deletePost))
                            @method('PUT')
                        @elseif(isset($deletePost) && isset($post))
                            @method('DELETE')
                        @endif
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" value="{{ isset($post) ? $post->title : '' }}" {{ isset($deletePost) ? 'disabled' : 'required' }} placeholder="Enter post title">
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" name="content" rows="3" {{ isset($deletePost) ? 'disabled' : 'required' }} placeholder="Write post content...">{{ isset($post) ? $post->content : '' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control" name="author" value="{{ isset($post) ? $post->author : '' }}" {{ isset($deletePost) ? 'disabled' : 'required' }} placeholder="Author name">
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control" name="image" {{ isset($deletePost) ? 'disabled' : '' }}>
                            @if(isset($post) && $post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-fluid mt-2" style="max-height: 200px; max-width: 100%;">
                            @endif
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_published" id="is_published" {{ isset($post) && $post->is_published ? 'checked' : '' }} {{ isset($deletePost) ? 'disabled' : '' }}>
                            <label for="is_published" class="form-check-label">Publish</label>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            @if(isset($deletePost) && isset($post))
                                <button type="submit" class="btn btn-danger">Delete Post</button>
                                <a href="{{ route('posts.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                            @else
                                <button type="submit" class="btn btn-success">{{ isset($post) ? 'Update Post' : 'Add Post' }}</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Post List Section -->
        <div class="col-md-12 col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-light d-flex justify-content-between">
                    <h5 class="mb-0">Posts List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>TITLE</th>
                                <th>Content</th>
                                <th>Author</th>
                                <th>Image</th>
                                <th>Published</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($post->title, 20, '...') }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($post->content, 20, '...') }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($post->author, 4, '...') }}</td>
                                <td>
                                    @if($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" style="max-height: 50px; max-width: 50px;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>{{ $post->is_published ? 'Yes' : 'No' }}</td>
                                <td>{{ $post->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $post->updated_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('posts.delete', $post->id) }}" class="btn btn-sm btn-danger">Delete</a>
                                    <form action="{{ route('posts.togglePublish', $post->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $post->is_published ? 'btn-warning' : 'btn-success' }}">
                                            {{ $post->is_published ? 'Unpublish' : 'Publish' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $posts->links() }} <!-- Pagination links -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
