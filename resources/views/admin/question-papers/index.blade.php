@extends('layouts.admin')

@section('title', 'Question Papers')

@section('page_title', 'Question Papers')

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
                    <h5 class="mb-0">Manage Question Papers</h5>
                    <a href="{{ route('admin.question-papers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Add New Question Paper
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filters</h5>
                    <form action="{{ route('admin.question-papers') }}" method="GET" class="row">
                        <div class="col-md-4 mb-3">
                            <label for="exam_filter" class="form-label">Exam</label>
                            <select name="exam_id" id="exam_filter" class="form-select">
                                <option value="">All Exams</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                        {{ $exam->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="topic_filter" class="form-label">Topic</label>
                            <select name="topic_id" id="topic_filter" class="form-select">
                                <option value="">All Topics</option>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                        {{ $topic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('admin.question-papers') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Papers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Exam</th>
                                    <th scope="col">Questions</th>
                                    <th scope="col">File</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questionPapers as $index => $paper)
                                    <tr>
                                        <th scope="row">{{ $questionPapers->firstItem() + $index }}</th>
                                        <td>{{ $paper->title }}</td>
                                        <td>{{ $paper->exam->name ?? 'N/A' }}</td>
                                        <td>{{ $paper->questions->count() }}</td>
                                        <td>
                                            @if($paper->file_path)
                                                <a href="{{ asset('storage/' . $paper->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-file-download"></i> View
                                                </a>
                                            @else
                                                <span class="text-muted">No file</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.question-papers.edit', $paper) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.question-papers.show', $paper) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteQuestionPaperModal{{ $paper->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No question papers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $questionPapers->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Question Paper Modals -->
@foreach($questionPapers as $paper)
<div class="modal fade" id="deleteQuestionPaperModal{{ $paper->id }}" tabindex="-1" aria-labelledby="deleteQuestionPaperModalLabel{{ $paper->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteQuestionPaperModalLabel{{ $paper->id }}">Delete Question Paper</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the question paper <strong>{{ $paper->title }}</strong>?</p>
                <p class="text-danger">This action cannot be undone. All associated questions will be disassociated from this question paper.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.question-papers.destroy', $paper) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Question Paper</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
