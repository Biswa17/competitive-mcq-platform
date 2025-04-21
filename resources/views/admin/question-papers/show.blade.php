@extends('layouts.admin')

@section('title', 'View Question Paper')

@section('page_title', 'View Question Paper')

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
                    <h5 class="mb-0">View Question Paper</h5>
                    <a href="{{ route('admin.question-papers') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Question Papers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Paper Details -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $questionPaper->name }}</h5>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Details</h6>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Exam</th>
                                        <td>
                                            @if($questionPaper->exam)
                                                <a href="{{ route('admin.exams.show', $questionPaper->exam) }}">
                                                    {{ $questionPaper->exam->name }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Year</th>
                                        <td>{{ $questionPaper->year }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Questions</th>
                                        <td>{{ $questionPaper->questions->count() }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $questionPaper->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $questionPaper->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sync Status</th>
                                        <td>
                                            @if ($questionPaper->is_sync === true)
                                                <span class="badge bg-success">Synced</span>
                                            @elseif ($questionPaper->is_sync === false)
                                                <span class="badge bg-warning text-dark">Not Synced</span>
                                            @else
                                                <span class="badge bg-secondary">Not Set</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($questionPaper->description)
                                <h6 class="fw-bold">Description</h6>
                                <div class="p-3 bg-light mb-4 rounded">
                                    {{ $questionPaper->description }}
                                </div>
                            @endif
                            
                            @if($questionPaper->file_path)
                                <h6 class="fw-bold">Attached File</h6>
                                <div class="d-grid gap-2">
                                    {{-- Changed href to use the new view-file route --}}
                                    <a href="{{ route('admin.question-papers.view-file', $questionPaper) }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-file-alt me-2"></i> View File {{-- Changed icon and text --}}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Questions</h6>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionsModal">
                                <i class="fas fa-plus-circle me-2"></i> Add Questions
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Question</th>
                                        {{-- Removed Topic Header --}}
                                        <th scope="col">Correct Option</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($questionPaper->questions as $index => $question)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ Str::limit($question->question_text, 50) }}</td>
                                        {{-- Removed Topic Data --}}
                                        <td>{{ $question->correct_option }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.questions.show', $question) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#removeQuestionModal{{ $question->id }}">
                                                    <i class="fas fa-unlink"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No questions found in this question paper</td> {{-- Adjusted colspan --}}
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('admin.question-papers') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Question Papers
                        </a>
                        <div>
                            <a href="{{ route('admin.question-papers.edit', $questionPaper) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i> Edit Question Paper
                            </a>
                            <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteQuestionPaperModal">
                                <i class="fas fa-trash me-2"></i> Delete Question Paper
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Questions Modal -->
<div class="modal fade" id="addQuestionsModal" tabindex="-1" aria-labelledby="addQuestionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuestionsModalLabel">Add Questions to Question Paper</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.question-papers.add-questions', $questionPaper) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="questionSearch" class="form-label">Search Questions</label>
                        <input type="text" class="form-control" id="questionSearch" placeholder="Type to search...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Select Questions</label>
                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-hover">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th scope="col" width="50px">Select</th>
                                        <th scope="col">Question</th>
                                        {{-- Removed Topic Header from Modal --}}
                                    </tr>
                                </thead>
                                <tbody id="questionsTableBody">
                                    <!-- This would be populated with available questions via JavaScript -->
                                    <!-- For demonstration, adding some sample questions -->
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="question_ids[]" value="1">
                                            </div>
                                        </td>
                                        <td>What is the capital of France?</td>
                                        {{-- Removed Topic Data from Modal Example --}}
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="question_ids[]" value="2">
                                            </div>
                                        </td>
                                        <td>What is 2 + 2?</td>
                                        {{-- Removed Topic Data from Modal Example --}}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-text">Select questions to add to this question paper.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Selected Questions</button>
                </div>
            </form>
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
                <p>Are you sure you want to delete the question paper <strong>{{ $questionPaper->name }}</strong>?</p>
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

<!-- Remove Question Modals -->
@foreach($questionPaper->questions as $question)
<div class="modal fade" id="removeQuestionModal{{ $question->id }}" tabindex="-1" aria-labelledby="removeQuestionModalLabel{{ $question->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeQuestionModalLabel{{ $question->id }}">Remove Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this question from the question paper?</p>
                <p class="text-muted">The question will not be deleted, just removed from this question paper.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.question-papers.remove-question', ['questionPaper' => $questionPaper, 'question' => $question]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Remove Question</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
