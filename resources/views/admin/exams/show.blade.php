@extends('layouts.admin')

@section('title', 'View Exam')

@section('page_title', 'View Exam')

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
                    <h5 class="mb-0">View Exam</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Details -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $exam->name }}</h5>
                    <p class="card-text">{{ $exam->description }}</p>
                    <p class="card-text">
                        <strong>Categories:</strong>
                        @forelse($exam->categories as $category)
                            <span class="badge bg-primary">{{ $category->name }}</span>
                        @empty
                            <span class="badge bg-secondary">No Categories</span>
                        @endforelse
                    </p>
                    <p class="card-text">
                        <strong>Topics:</strong>
                        @forelse($exam->topics as $topic)
                            <span class="badge bg-info">{{ $topic->name }}</span>
                        @empty
                            <span class="badge bg-secondary">No Topics</span>
                        @endforelse
                    </p>
                    <p class="card-text">
                        <strong>Status:</strong>
                        @if($exam->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </p>
                    <a href="{{ route('admin.exams') }}" class="btn btn-secondary">Back to Exams</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
