@extends('layouts.admin')

@section('title', 'View Question')

@section('page_title', 'View Question')

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
                    <h5 class="mb-0">View Question</h5>
                    <a href="{{ route('admin.questions') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Questions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Details -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Question Details</h5>
                    
                    <div class="p-3 bg-light mb-4 rounded">
                        <h6 class="fw-bold">Question Text</h6>
                        <p class="mb-0">{{ $question->question_text }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Options</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Option</th>
                                            <th>Text</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="{{ $question->correct_option == 'A' ? 'table-success' : '' }}">
                                            <td>A</td>
                                            <td>{{ $question->option_a }}</td>
                                        </tr>
                                        <tr class="{{ $question->correct_option == 'B' ? 'table-success' : '' }}">
                                            <td>B</td>
                                            <td>{{ $question->option_b }}</td>
                                        </tr>
                                        <tr class="{{ $question->correct_option == 'C' ? 'table-success' : '' }}">
                                            <td>C</td>
                                            <td>{{ $question->option_c }}</td>
                                        </tr>
                                        <tr class="{{ $question->correct_option == 'D' ? 'table-success' : '' }}">
                                            <td>D</td>
                                            <td>{{ $question->option_d }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p><strong>Correct Option:</strong> {{ $question->correct_option }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Relationships</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Exam</th>
                                            <td>
                                                @if($question->exam)
                                                    <a href="{{ route('admin.exams.show', $question->exam) }}">
                                                        {{ $question->exam->name }}
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Topic</th>
                                            <td>
                                                @if($question->topic)
                                                    <a href="{{ route('admin.topics.show', $question->topic) }}">
                                                        {{ $question->topic->name }}
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Question Paper</th>
                                            <td>
                                                @if($question->questionPaper)
                                                    {{ $question->questionPaper->title }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if($question->images->count() > 0)
                    <div class="mt-4">
                        <h6 class="fw-bold">Images</h6>
                        <div class="row">
                            @foreach($question->images as $image)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="{{ asset($image->image_path) }}" class="card-img-top" alt="Question Image">
                                    <div class="card-body text-center">
                                        <a href="{{ asset($image->image_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-external-link-alt"></i> View Full Size
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <h6 class="fw-bold">Metadata</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>ID</th>
                                        <td>{{ $question->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $question->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $question->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('admin.questions') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Questions
                        </a>
                        <div>
                            <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i> Edit Question
                            </a>
                            <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteQuestionModal">
                                <i class="fas fa-trash me-2"></i> Delete Question
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Question Modal -->
<div class="modal fade" id="deleteQuestionModal" tabindex="-1" aria-labelledby="deleteQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteQuestionModalLabel">Delete Question</h5>
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
@endsection
