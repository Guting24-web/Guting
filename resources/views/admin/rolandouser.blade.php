@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- User Form Section -->
        <div class="col-md-12 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-light">
                    @if(isset($deleteUser))
                        Confirm Delete User: {{ $user->name }}
                    @elseif(isset($user))
                        Edit User
                    @else
                        Add User
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
                    <form action="{{ isset($deleteUser) ? route('users.destroy', $user->id) : (isset($user) ? route('users.update', $user->id) : route('users.store')) }}" method="POST">
                        @csrf
                        @if(isset($user) && !isset($deleteUser))
                            @method('PUT')
                        @elseif(isset($deleteUser))
                            @method('DELETE')
                        @endif

                        <!-- Name Field -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ isset($user) ? $user->name : old('name') }}" {{ isset($deleteUser) ? 'disabled' : 'required' }}>
                        </div>

                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ isset($user) ? $user->email : old('email') }}" {{ isset($deleteUser) ? 'disabled' : 'required' }}>
                        </div>

                        <!-- Password Field (Only for Adding) -->
                        @if(!isset($user) && !isset($deleteUser))
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        @endif

                        <!-- Role Field -->
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" {{ isset($deleteUser) ? 'disabled' : 'required' }}>
                                <option value="">Select Role</option>
                                <option value="admin" {{ (isset($user) && $user->role == 'admin') ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ (isset($user) && $user->role == 'user') ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <!-- Account Status Field -->
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Account Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="is_active" name="is_active" {{ isset($deleteUser) ? 'disabled' : 'required' }}>
                                <option value="">Select Status</option>
                                <option value="1" {{ (isset($user) && $user->is_active == 1) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (isset($user) && $user->is_active == 0) ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Confirm Delete Section -->
                        @if(isset($deleteUser))
                        <div class="mb-3">
                            <label for="account-status" class="form-label">Account Status</label>
                            <input type="text" class="form-control" value="{{ $user->is_active ? 'Active' : 'Inactive' }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" class="form-control" disabled>
                        </div>
                        @endif

                        <!-- Action Buttons Section -->
                        <div class="d-flex justify-content-between mt-3">
                            @if(isset($deleteUser))
                                <button type="submit" class="btn btn-danger">Delete User</button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                            @else
                                <button type="submit" class="btn btn-success">
                                    {{ isset($user) ? 'Update User' : 'Add User' }}
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="col-md-12 col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Users List</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <label for="records-per-page" class="me-2">Display</label>
                            <select id="records-per-page" class="form-select me-2" style="width:auto;">
                                <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('per_page', 5) == 15 ? 'selected' : '' }}>15</option>
                            </select>
                            <span>records per page</span>
                        </div>

                        <div class="d-flex align-items-center">
                            <label for="search" class="me-2">Filter:</label>
                            <input type="text" id="search" class="form-control" placeholder="Search..." onkeyup="filterUsers()">
                        </div>
                    </div>
                
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="example">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Account Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="user-table-body">
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>
                                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary me-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('users.delete', $user->id) }}" class="btn btn-sm btn-danger" title="Delete">
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
                            {{ $users->links() }}
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
    window.location.href = `{{ route('users.index') }}?per_page=${perPage}`;
});

// Filter users based on search input
function filterUsers() {
    const input = document.getElementById('search');
    const filter = input.value.toLowerCase();
    const table = document.getElementById("user-table-body");
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
