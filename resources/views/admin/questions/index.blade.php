@extends('layouts.admin')

@section('title', 'Questions')

@section('page_title', 'Questions')

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
                    <h5 class="mb-0">Manage Questions</h5>
                    <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Add New Question
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
                    <form action="{{ route('admin.questions') }}" method="GET" class="row">
                        <div class="col-md-3 mb-3">
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
                        <div class="col-md-3 mb-3">
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
                        <div class="col-md-3 mb-3">
                            <label for="question_paper_filter" class="form-label">Question Paper</label>
                            <select name="question_paper_id" id="question_paper_filter" class="form-select">
                                <option value="">All Question Papers</option>
                                @foreach($questionPapers as $paper)
                                    <option value="{{ $paper->id }}" {{ request('question_paper_id') == $paper->id ? 'selected' : '' }}>
                                        {{ $paper->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('admin.questions') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Question</th>
                                    <th scope="col">Exam</th>
                                    <th scope="col">Topic</th>
                                    <th scope="col">Question Paper</th>
                                    <th scope="col">Images</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questions as $index => $question)
                                    <tr>
                                        <th scope="row">{{ $questions->firstItem() + $index }}</th>
                                        <td>{{ Str::limit($question->question_text, 50) }}</td>
                                        <td>{{ $question->exam->name ?? 'N/A' }}</td>
                                        <td>{{ $question->topic->name ?? 'N/A' }}</td>
                                        <td>{{ $question->questionPaper->title ?? 'N/A' }}</td>
                                        <td>{{ $question->images->count() }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.questions.show', $question) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteQuestionModal{{ $question->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No questions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $questions->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Delete Question Modals -->
@foreach($questions as $question)
<div class="modal fade" id="deleteQuestionModal{{ $question->id }}" tabindex="-1" aria-labelledby="deleteQuestionModalLabel{{ $question->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteQuestionModalLabel{{ $question->id }}">Delete Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this question?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Question</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
