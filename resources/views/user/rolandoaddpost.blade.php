@extends('layout.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Post Form Section -->
        <div class="col-md-12 col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-light">
                    @if(isset($post) && !isset($deletePost))
                        <i class="fas fa-edit"></i> Edit Post
                    @elseif(isset($deletePost) && isset($post)) 
                        <i class="fas fa-trash-alt"></i> Confirm Delete Post: {{ $post->content }}  
                    @else
                        <i class="fas fa-plus-circle"></i> Add Post
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <!-- Display Validation Errors -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ isset($deletePost) ? route('addpost.destroy', $post->id) : (isset($post) ? route('addposts.update', $post->id) : route('addposts.store')) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($post) && !isset($deletePost))
                            @method('PUT')
                        @elseif(isset($deletePost) && isset($post))
                            @method('DELETE')
                        @endif
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" value="{{ old('title', isset($post) ? $post->title : '') }}" {{ isset($deletePost) ? 'disabled' : 'required' }}>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" name="content" rows="3" {{ isset($deletePost) ? 'disabled' : 'required' }}>{{ old('content', isset($post) ? $post->content : '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control" name="author" value="{{ Auth::user()->name }}" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control" name="image" {{ isset($deletePost) ? 'disabled' : '' }}>
                            @if(isset($post) && $post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-fluid mt-2" style="max-height: 200px; max-width: 100%;">
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            @if(isset($deletePost) && isset($post))
                                <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ route('addpost.destroy', $post->id) }}')"><i class="fas fa-trash"></i> Delete Post</button>
                                <a href="{{ route('addpost.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                            @else
                                <button type="submit" class="btn btn-success">{{ isset($post) ? 'Update Post' : 'Add Post' }}</button>
                                @if(isset($post))
                                    <a href="{{ route('addpost.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                                @endif
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Post List Section -->
        <div class="col-md-12 col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Posts List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
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
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ Str::limit($post->content, 50) }}</td>
                                    <td>{{ $post->author }}</td>
                                    <td>
                                        @if($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-thumbnail" style="max-height: 50px; max-width: 50px;">
                                        @endif
                                    </td>
                                    <td>{{ $post->is_published ? 'Yes' : 'No' }}</td>
                                    <td>{{ $post->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $post->updated_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('addpost.edit', $post->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('addpost.destroy', $post->id) }}')"><i class="fas fa-trash"></i> Delete</button>
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

<script>
    document.querySelector('input[name="image"]').addEventListener('change', function(event) {
        const output = document.createElement('img');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.className = 'img-fluid mt-2';
        output.style.maxHeight = '200px';
        output.style.maxWidth = '100%';

        // Clear previous preview if exists
        const previousImage = document.querySelector('.image-preview');
        if (previousImage) {
            previousImage.remove();
        }

        // Append new preview
        event.target.parentNode.appendChild(output);
        output.classList.add('image-preview');
    });
</script>


@endsection
