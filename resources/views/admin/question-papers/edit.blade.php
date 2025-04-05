@extends('layouts.admin')

@section('title', 'Edit Question Paper')

@section('page_title', 'Edit Question Paper')

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

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Question Paper</h5>
                    <a href="{{ route('admin.question-papers') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Question Papers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Question Paper Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.question-papers.update', $questionPaper) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $questionPaper->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="exam_id" class="form-label">Exam <span class="text-danger">*</span></label>
                            <select class="form-select @error('exam_id') is-invalid @enderror" id="exam_id" name="exam_id" required>
                                <option value="">Select Exam</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" {{ old('exam_id', $questionPaper->exam_id) == $exam->id ? 'selected' : '' }}>
                                        {{ $exam->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('exam_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $questionPaper->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Upload File</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
                            <div class="form-text">Allowed formats: PDF, JPG, JPEG, PNG (max: 10MB)</div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if($questionPaper->file_path)
                                <div class="mt-2">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">Current file:</span>
                                        <a href="{{ asset('storage/' . $questionPaper->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-file-download me-1"></i> View File
                                        </a>
                                    </div>
                                    <div class="form-text">Uploading a new file will replace the current one.</div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4">
                            <h6 class="fw-bold">Associated Questions</h6>
                            <p>This question paper has {{ $questionPaper->questions->count() }} questions. You can manage questions from the <a href="{{ route('admin.question-papers.show', $questionPaper) }}">question paper details</a> page.</p>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('admin.question-papers') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Question Paper
                                </button>
                                <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteQuestionPaperModal">
                                    <i class="fas fa-trash me-2"></i> Delete Question Paper
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Question Paper Modal -->
<div class="modal fade" id="deleteQuestionPaperModal" tabindex="-1" aria-labelledby="deleteQuestionPaperModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteQuestionPaperModalLabel">Delete Question Paper</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the question paper <strong>{{ $questionPaper->title }}</strong>?</p>
                <p class="text-danger">This action cannot be undone. All associated questions will be disassociated from this question paper.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.question-papers.destroy', $questionPaper) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Question Paper</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
