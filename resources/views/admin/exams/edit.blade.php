@extends('layouts.admin')

@section('title', 'Edit Exam')

@section('page_title', 'Edit Exam')

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
                    <h5 class="mb-0">Edit Exam</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Exam Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.exams.update', $exam->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Exam Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $exam->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $exam->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="examCategories" class="form-label">Categories</label>
                            <select class="form-select" id="examCategories" name="categories[]" multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $exam->categories->contains($category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple categories</div>
                        </div>
                        <div class="mb-3">
                            <label for="examTopics" class="form-label">Topics</label>
                            <select class="form-select" id="examTopics" name="topics[]" multiple>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ $exam->topics->contains($topic->id) ? 'selected' : '' }}>{{ $topic->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple topics</div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ $exam->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Exam</button>
                        <a href="{{ route('admin.exams') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
