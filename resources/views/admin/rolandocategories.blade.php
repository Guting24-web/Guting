@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Category Form Section -->
        <div class="col-md-12 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-light">
                    @if(isset($deleteCategory))
                        Confirm Delete Category: {{ $category->name }}
                    @elseif(isset($category))
                        Edit Category
                    @else
                        Add Category
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    
                    <!-- Form to handle Add/Edit/Delete -->
                    <form action="{{ isset($deleteCategory) ? route('categories.destroy', $category->id) : (isset($category) ? route('categories.update', $category->id) : route('categories.store')) }}" method="POST">
                        @csrf
                        @if(isset($category) && !isset($deleteCategory))
                            @method('PUT')
                        @elseif(isset($deleteCategory))
                            @method('DELETE')
                        @endif

                        <!-- Category Name Field -->
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="category_name" name="name" value="{{ isset($category) ? $category->name : old('name') }}" {{ isset($deleteCategory) ? 'disabled' : 'required' }}>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" {{ isset($deleteCategory) ? 'disabled' : '' }}>{{ isset($category) ? $category->description : old('description') }}</textarea>
                        </div>

                        <!-- Action Buttons Section -->
                        <div class="d-flex justify-content-between mt-3">
                            @if(isset($deleteCategory))
                                <button type="submit" class="btn btn-danger">Delete Category</button>
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                            @else
                                <button type="submit" class="btn btn-success">
                                    {{ isset($category) ? 'Update Category' : 'Add Category' }}
                                </button>
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="col-md-12 col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-light d-flex justify-content-between">
                    <h5 class="mb-0">Categories List</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Records per page dropdown -->
                        <div class="d-flex align-items-center">
                            <label for="records-per-page" class="me-2">Display</label>
                            <select id="records-per-page" class="form-select me-2" style="width:auto;">
                                <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('per_page', 5) == 15 ? 'selected' : '' }}>15</option>
                            </select>
                            <span>records per page</span>
                        </div>

                        <!-- Search Input -->
                        <div class="d-flex align-items-center">
                            <label for="search" class="me-2">Filter:</label>
                            <input type="text" id="search" class="form-control" placeholder="Search..." onkeyup="filterCategories()">
                        </div>
                    </div>
                
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="category-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="category-table-body">
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary me-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('categories.delete', $category->id) }}" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="pagination mt-3">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Change records per page and reload the page
document.getElementById('records-per-page').addEventListener('change', function() {
    const perPage = this.value;
    window.location.href = `{{ route('categories.index') }}?per_page=${perPage}`;
});

// Filter categories based on search input
function filterCategories() {
    const input = document.getElementById('search');
    const filter = input.value.toLowerCase();
    const table = document.getElementById("category-table-body");
    const rows = table.getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        let rowContainsFilter = false;

        for (let j = 0; j < cells.length; j++) {
            if (cells[j]) {
                const cellText = cells[j].textContent || cells[j].innerText;
                if (cellText.toLowerCase().includes(filter)) {
                    rowContainsFilter = true;
                    break;
                }
            }
        }

        rows[i].style.display = rowContainsFilter ? "" : "none";
    }
}
</script>

@endsection
