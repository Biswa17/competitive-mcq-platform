@extends('layouts.admin')

@section('title', 'View User')

@section('page_title', 'View User')

@section('content')
<div class="container-fluid">
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">View User</h5>
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">User Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 30%">Name</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            <td>{{ $user->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Role</th>
                                            <td>
                                                <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : ($user->role == 'teacher' ? 'bg-primary' : 'bg-success') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>New User</th>
                                            <td>{{ $user->is_new_user ? 'Yes' : 'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ $user->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title">Activity</h5>
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">User Statistics</h6>
                                    <div class="row text-center">
                                        <div class="col-md-4 mb-3">
                                            <div class="p-3 border rounded">
                                                <h3 class="text-primary">{{ rand(0, 50) }}</h3>
                                                <p class="mb-0">Exams Taken</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="p-3 border rounded">
                                                <h3 class="text-success">{{ rand(0, 500) }}</h3>
                                                <p class="mb-0">Questions Answered</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="p-3 border rounded">
                                                <h3 class="text-info">{{ rand(50, 100) }}%</h3>
                                                <p class="mb-0">Avg. Score</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="card-title mt-4">Recent Activity</h5>
                            <div class="list-group">
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Completed Exam</h6>
                                        <small>3 days ago</small>
                                    </div>
                                    <p class="mb-1">Mathematics - Advanced Calculus</p>
                                    <small>Score: 85%</small>
                                </div>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Started Topic</h6>
                                        <small>1 week ago</small>
                                    </div>
                                    <p class="mb-1">Physics - Quantum Mechanics</p>
                                    <small>Progress: 60%</small>
                                </div>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Account Created</h6>
                                        <small>{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">User registered with email {{ $user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Users
                        </a>
                        <div>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i> Edit User
                            </a>
                            <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                <i class="fas fa-trash me-2"></i> Delete User
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the user <strong>{{ $user->name }}</strong>?</p>
                <p class="text-danger">This action cannot be undone. All associated data will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
