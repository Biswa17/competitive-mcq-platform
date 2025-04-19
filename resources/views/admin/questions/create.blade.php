@extends('layouts.admin')

@section('title', 'Create Question')

@section('page_title', 'Create Question')

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
                    <h5 class="mb-0">Create New Question</h5>
                    <a href="{{ route('admin.questions') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Questions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Question Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="question_text" class="form-label">Question Text <span class="text-danger">*</span></label>
                            <textarea name="question_text" id="question_text" rows="4" class="form-control @error('question_text') is-invalid @enderror" required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="option_a" class="form-label">Option A <span class="text-danger">*</span></label>
                                <input type="text" name="option_a" id="option_a" class="form-control @error('option_a') is-invalid @enderror" value="{{ old('option_a') }}" required>
                                @error('option_a')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="option_b" class="form-label">Option B <span class="text-danger">*</span></label>
                                <input type="text" name="option_b" id="option_b" class="form-control @error('option_b') is-invalid @enderror" value="{{ old('option_b') }}" required>
                                @error('option_b')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="option_c" class="form-label">Option C <span class="text-danger">*</span></label>
                                <input type="text" name="option_c" id="option_c" class="form-control @error('option_c') is-invalid @enderror" value="{{ old('option_c') }}" required>
                                @error('option_c')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="option_d" class="form-label">Option D <span class="text-danger">*</span></label>
                                <input type="text" name="option_d" id="option_d" class="form-control @error('option_d') is-invalid @enderror" value="{{ old('option_d') }}" required>
                                @error('option_d')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="correct_option" class="form-label">Correct Option <span class="text-danger">*</span></label>
                                <select name="correct_option" id="correct_option" class="form-select @error('correct_option') is-invalid @enderror" required>
                                    <option value="">Select Correct Option</option>
                                    <option value="A" {{ old('correct_option') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('correct_option') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ old('correct_option') == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ old('correct_option') == 'D' ? 'selected' : '' }}>D</option>
                                </select>
                                @error('correct_option')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="exam_id" class="form-label">Exam <span class="text-danger">*</span></label>
                                <select name="exam_id" id="exam_id" class="form-select @error('exam_id') is-invalid @enderror" required>
                                    <option value="">Select Exam</option>
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}" {{ old('exam_id') == $exam->id ? 'selected' : '' }}>
                                            {{ $exam->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('exam_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="topic_id" class="form-label">Topic</label>
                                <select name="topic_id" id="topic_id" class="form-select @error('topic_id') is-invalid @enderror">
                                    <option value="">Select Topic</option>
                                    @foreach($topics as $topic)
                                        <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                            {{ $topic->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('topic_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="question_paper_id" class="form-label">Question Paper</label>
                                <select name="question_paper_id" id="question_paper_id" class="form-select @error('question_paper_id') is-invalid @enderror">
                                    <option value="">Select Question Paper</option>
                                    @foreach($questionPapers as $paper)
                                        <option value="{{ $paper->id }}" {{ old('question_paper_id') == $paper->id ? 'selected' : '' }}>
                                            {{ $paper->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('question_paper_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Upload Images</label>
                            <input type="file" name="images[]" id="images" class="form-control @error('images.*') is-invalid @enderror" multiple>
                            <small class="form-text text-muted">You can select multiple images. Allowed formats: jpeg, png, jpg, gif (max: 2MB each)</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('admin.questions') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Create Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
