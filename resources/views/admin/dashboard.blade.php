@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page_title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-2">Welcome to MCQ Admin Panel</h3>
                            <p class="mb-0">Manage your competitive MCQ platform from one place</p>
                        </div>
                        <div>
                            <i class="fas fa-tachometer-alt fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Exams</h6>
                            <h4 class="fw-bold mb-0">24</h4>
                        </div>
                        <div class="bg-light-primary rounded-circle p-3">
                            <i class="fas fa-file-alt text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success">+12%</span>
                        <small class="text-muted ms-2">From last month</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Categories</h6>
                            <h4 class="fw-bold mb-0">8</h4>
                        </div>
                        <div class="bg-light-success rounded-circle p-3">
                            <i class="fas fa-folder text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success">+5%</span>
                        <small class="text-muted ms-2">From last month</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Questions</h6>
                            <h4 class="fw-bold mb-0">1,254</h4>
                        </div>
                        <div class="bg-light-warning rounded-circle p-3">
                            <i class="fas fa-question-circle text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success">+18%</span>
                        <small class="text-muted ms-2">From last month</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Users</h6>
                            <h4 class="fw-bold mb-0">5,678</h4>
                        </div>
                        <div class="bg-light-info rounded-circle p-3">
                            <i class="fas fa-users text-info"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success">+25%</span>
                        <small class="text-muted ms-2">From last month</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="#" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                                <i class="fas fa-plus-circle"></i> New Exam
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                                <i class="fas fa-plus-circle"></i> New Category
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-warning w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                                <i class="fas fa-plus-circle"></i> New Question
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-info w-100 d-flex align-items-center justify-content-center gap-2 py-2 text-white">
                                <i class="fas fa-plus-circle"></i> New User
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Exams</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Java Programming Basics</h6>
                                <small class="text-muted">3 days ago</small>
                            </div>
                            <p class="mb-1 text-muted">Basic concepts of Java programming language</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Data Structures</h6>
                                <small class="text-muted">5 days ago</small>
                            </div>
                            <p class="mb-1 text-muted">Arrays, Linked Lists, Trees, and Graphs</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Web Development</h6>
                                <small class="text-muted">1 week ago</small>
                            </div>
                            <p class="mb-1 text-muted">HTML, CSS, JavaScript, and PHP</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Database Management</h6>
                                <small class="text-muted">2 weeks ago</small>
                            </div>
                            <p class="mb-1 text-muted">SQL, NoSQL, and Database Design</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Users</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=0D8ABC&color=fff" alt="User" class="rounded-circle me-3" width="40" height="40">
                                <div>
                                    <h6 class="mb-0">John Doe</h6>
                                    <small class="text-muted">john.doe@example.com</small>
                                </div>
                                <small class="ms-auto text-muted">2 hours ago</small>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=4CAF50&color=fff" alt="User" class="rounded-circle me-3" width="40" height="40">
                                <div>
                                    <h6 class="mb-0">Jane Smith</h6>
                                    <small class="text-muted">jane.smith@example.com</small>
                                </div>
                                <small class="ms-auto text-muted">5 hours ago</small>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Robert+Johnson&background=FF5722&color=fff" alt="User" class="rounded-circle me-3" width="40" height="40">
                                <div>
                                    <h6 class="mb-0">Robert Johnson</h6>
                                    <small class="text-muted">robert.johnson@example.com</small>
                                </div>
                                <small class="ms-auto text-muted">1 day ago</small>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Emily+Williams&background=9C27B0&color=fff" alt="User" class="rounded-circle me-3" width="40" height="40">
                                <div>
                                    <h6 class="mb-0">Emily Williams</h6>
                                    <small class="text-muted">emily.williams@example.com</small>
                                </div>
                                <small class="ms-auto text-muted">2 days ago</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }
    .bg-light-success {
        background-color: rgba(25, 135, 84, 0.1);
    }
    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }
    .bg-light-info {
        background-color: rgba(13, 202, 240, 0.1);
    }
    .text-primary {
        color: #0d6efd !important;
    }
    .text-success {
        color: #198754 !important;
    }
    .text-warning {
        color: #ffc107 !important;
    }
    .text-info {
        color: #0dcaf0 !important;
    }
</style>
@endsection
