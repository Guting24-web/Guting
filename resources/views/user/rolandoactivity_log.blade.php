@extends('layout.app')

@section('content')
<div class="container mt-4">
    <h5 class="mb-3">
        <i class="fas fa-list-alt"></i> Activity Log
    </h5>
    @if ($logs->count() > 0)
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>
                            <i class="fas fa-user"></i> {{ $log->user->name }}
                        </td>
                        <td>
                            <i class="fas fa-clipboard-check"></i> {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        </td>
                        <td>{{ $log->description }}</td>
                        <td>
                            <i class="fas fa-clock"></i> {{ $log->created_at->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No activity logs available.
        </div>
    @endif
</div>
@endsection
