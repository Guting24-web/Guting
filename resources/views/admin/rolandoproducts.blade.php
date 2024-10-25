@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Product Form Section -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header bg-light">
                    @if(isset($deleteProduct))
                        Confirm Delete Product: {{ $product->name }}
                    @elseif(isset($product))
                        Edit Product
                    @else
                        Add Product
                    @endif
                </div>
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <!-- Form to handle Add/Edit/Delete -->
                    <form action="{{ isset($deleteProduct) ? route('products.destroy', $product->id) : (isset($product) ? route('products.update', $product->id) : route('products.store')) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($product) && !isset($deleteProduct))
                            @method('PUT')
                        @elseif(isset($deleteProduct))
                            @method('DELETE')
                        @endif

                        <!-- Category Select Field -->
                        @if(!isset($deleteProduct))
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Product Name Field -->
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', isset($product) ? $product->name : '') }}" required>
                            @error('product_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', isset($product) ? $product->description : '') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price Field -->
                        <div class="mb-3">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', isset($product) ? $product->price : '') }}" required>
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Quantity Field -->
                        <div class="mb-3">
                            <label for="stockquantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stockquantity" name="stockquantity" value="{{ old('stockquantity', isset($product) ? $product->stockquantity : '') }}" required>
                            @error('stockquantity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Input Field -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" onchange="previewImage(event)">

                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Preview -->
                        <div id="imagePreview" style="display: {{ isset($product) && $product->image ? 'block' : 'none' }}">
                            <img id="preview" src="{{ isset($product) ? asset('storage/' . $product->image) : '' }}" alt="Image Preview" class="img-thumbnail" style="width:100px;height:100px;">
                        </div>
                        @endif

                        <!-- Confirm Delete Section -->
                        @if(isset($deleteProduct))
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <input type="text" class="form-control" value="{{ $product->category->name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" value="{{ $product->name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="product_description" class="form-label">Description</label>
                            <textarea class="form-control" rows="3" disabled>{{ $product->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="product_price" class="form-label">Price</label>
                            <input type="text" class="form-control" value="{{ number_format($product->price, 2) }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="product_stock" class="form-label">Stock Quantity</label>
                            <input type="text" class="form-control" value="{{ $product->stockquantity }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="product_image" class="form-label">Current Image</label>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width:100px;height:100px;">
                            @else
                                <span>No Image</span>
                            @endif
                        </div>
                        @endif

                        <!-- Action Buttons Section -->
                        <div class="d-flex justify-content-between mt-3">
                            @if(isset($deleteProduct))
                                <button type="submit" class="btn btn-danger">Delete Product</button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                            @else
                                <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update' : 'Add' }}</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product List Section -->
        <div class="col-lg-8 col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-light">
                    Product List
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name & Description</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            <strong>{{ Str::limit($product->name, 10) }}</strong><br>
                                            <span>{{ Str::limit($product->description, 10) }}</span>
                                        </td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>{{ number_format($product->price, 2) }}</td>
                                        <td>{{ $product->stockquantity }}</td>
                                        <td>
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="img-thumbnail" style="width:50px;height:50px;">
                                            @else
                                                <span>No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-start">
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary me-2" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('products.delete', $product->id) }}" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    // Preview uploaded image
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const preview = document.getElementById('preview');

        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.style.display = 'block';
            };

            reader.readAsDataURL(event.target.files[0]);
        } else {
            imagePreview.style.display = 'none';
        }
    }
</script>