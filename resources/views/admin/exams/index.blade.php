@extends('layouts.admin')

@section('title', 'Exams')

@section('page_title', 'Exams')

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
                    <h5 class="mb-0">Manage Exams</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExamModal">
                        <i class="fas fa-plus-circle me-2"></i> Add New Exam
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="row g-3" method="GET" action="{{ route('admin.exams') }}">
                        <div class="col-md-4">
                            <label for="filterCategory" class="form-label">Category</label>
                            <select class="form-select" id="filterCategory" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (isset($filters['category']) && $filters['category'] == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->exams_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filterStatus" class="form-label">Status</label>
                            <select class="form-select" id="filterStatus" name="status">
                                <option value="">All Status</option>
                                <option value="1" {{ (isset($filters['status']) && $filters['status'] == '1') ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (isset($filters['status']) && $filters['status'] == '0') ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="searchExam" class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchExam" name="search" placeholder="Search exams..." value="{{ $filters['search'] ?? '' }}">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('admin.exams') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Exams Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Categories</th>
                                    <th scope="col">Topics</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exams as $index => $exam)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $exam->name }}</td>
                                    <td>
                                        @forelse($exam->categories as $category)
                                            <span class="badge bg-primary">{{ $category->name }}</span>
                                        @empty
                                            <span class="badge bg-secondary">No Categories</span>
                                        @endforelse
                                    </td>
                                    <td>{{ $exam->topics->count() }}</td>
                                    <td>
                                        @if($exam->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $exam->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.exams.edit', $exam->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.exams.show', $exam->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $exam->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No exams found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $exams->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Exam Modal -->
<div class="modal fade" id="addExamModal" tabindex="-1" aria-labelledby="addExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExamModalLabel">Add New Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.exams.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Exam Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="examCategories" class="form-label">Categories</label>
                        <select class="form-select" id="examCategories" name="categories[]" multiple>
                            @foreach($categories as $category)
                                @if($category->level == 2 || $category->level == 3)
                                    <option value="{{ $category->id }}">
                                        {{ $category->level == 3 ? '-- ' : '' }}{{ $category->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple categories</div>
                    </div>
                    <div class="mb-3">
                        <label for="examTopics" class="form-label">Topics</label>
                        <select class="form-select" id="examTopics" name="topics[]" multiple>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple topics</div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Exam</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(examId) {
        if (confirm('Are you sure you want to delete this exam?')) {
            // Use AJAX to delete the exam
            fetch('/admin/exams/' + examId + '/delete', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
            }).then(response => {
                if (response.ok) {
                    // Reload the page to reflect the changes
                    location.reload();
                } else {
                    alert('Failed to delete exam.');
                }
            });
        }
    }
</script>
@endsection
