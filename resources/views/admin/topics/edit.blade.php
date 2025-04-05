@extends('layouts.admin')

@section('title', 'Edit Topic')

@section('page_title', 'Edit Topic')

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
                    <h5 class="mb-0">Edit Topic</h5>
                    <a href="{{ url('/admin/topics') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Topics
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Topic Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.topics.update', $topic) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Topic Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $topic->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="relatedExams" class="form-label">Related Exams</label>
                            <select class="form-select" id="relatedExams" name="exams[]" multiple>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" {{ $topic->exams->contains($exam->id) ? 'selected' : '' }}>
                                        {{ $exam->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple exams</div>
                        </div>
                        
                        <div class="mt-4">
                            <h6 class="fw-bold">Associated Content</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Question Papers</h5>
                                            <p class="card-text">This topic has {{ $topic->questionPapers->count() }} question papers.</p>
                                            <a href="#" class="btn btn-outline-primary">Manage Question Papers</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Questions</h5>
                                            <p class="card-text">This topic has {{ $topic->questions->count() }} questions.</p>
                                            <a href="#" class="btn btn-outline-primary">Manage Questions</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('admin.topics') }}" class="btn btn-secondary">Cancel</a>
                            <div>
                                <button type="submit" class="btn btn-primary">Update Topic</button>
                                <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteTopicModal">
                                    <i class="fas fa-trash me-2"></i> Delete Topic
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Question Papers Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Question Papers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Questions</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topic->questionPapers as $index => $paper)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $paper->title ?? 'Question Paper ' . ($index + 1) }}</td>
                                    <td>{{ $paper->questions_count ?? 0 }}</td>
                                    <td>{{ $paper->created_at ? $paper->created_at->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No question papers found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionPaperModal">
                            <i class="fas fa-plus-circle me-2"></i> Add Question Paper
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Questions Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Questions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Question</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Difficulty</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topic->questions->take(5) as $index => $question)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $question->question_text ?? 'Question ' . ($index + 1) }}</td>
                                    <td>{{ $question->question_type ?? 'MCQ' }}</td>
                                    <td>
                                        @php
                                            $difficulty = $question->difficulty ?? 'medium';
                                            $badgeClass = [
                                                'easy' => 'bg-success',
                                                'medium' => 'bg-warning',
                                                'hard' => 'bg-danger'
                                            ][$difficulty] ?? 'bg-warning';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($difficulty) }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No questions found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                            <i class="fas fa-plus-circle me-2"></i> Add Question
                        </button>
                        @if($topic->questions->count() > 5)
                            <a href="#" class="btn btn-outline-primary ms-2">View All Questions ({{ $topic->questions->count() }})</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Topic Modal -->
<div class="modal fade" id="deleteTopicModal" tabindex="-1" aria-labelledby="deleteTopicModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTopicModalLabel">Delete Topic</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the topic <strong>{{ $topic->name }}</strong>?</p>
                <p class="text-danger">This action cannot be undone. All associated question papers and questions will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Topic</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Question Paper Modal -->
<div class="modal fade" id="addQuestionPaperModal" tabindex="-1" aria-labelledby="addQuestionPaperModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuestionPaperModalLabel">Add Question Paper</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="paperTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="paperTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="paperDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="paperDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="paperQuestions" class="form-label">Select Questions</label>
                        <select class="form-select" id="paperQuestions" name="questions[]" multiple>
                            <option value="1">If f(x) = x² + 3x + 2, find f'(x)</option>
                            <option value="2">Solve the equation: 2x² - 5x + 3 = 0</option>
                            <option value="3">Find the integral of sin(x)cos(x)</option>
                            <option value="4">If A and B are two events such that P(A) = 0.6, P(B) = 0.4 and P(A∩B) = 0.2, find P(A|B)</option>
                            <option value="5">Find the value of lim(x→0) (sin x)/x</option>
                        </select>
                        <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple questions</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Question Paper</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuestionModalLabel">Add Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="questionText" class="form-label">Question Text</label>
                        <textarea class="form-control" id="questionText" name="question_text" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="questionType" class="form-label">Question Type</label>
                        <select class="form-select" id="questionType" name="question_type">
                            <option value="mcq" selected>Multiple Choice</option>
                            <option value="true_false">True/False</option>
                            <option value="fill_blank">Fill in the Blank</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="questionDifficulty" class="form-label">Difficulty Level</label>
                        <select class="form-select" id="questionDifficulty" name="difficulty">
                            <option value="easy">Easy</option>
                            <option value="medium" selected>Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                    
                    <div id="mcqOptions">
                        <h6 class="mt-4 mb-3">Answer Options</h6>
                        <div class="mb-3">
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="option_a" checked>
                                </div>
                                <input type="text" class="form-control" name="option_a" placeholder="Option A" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="option_b">
                                </div>
                                <input type="text" class="form-control" name="option_b" placeholder="Option B" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="option_c">
                                </div>
                                <input type="text" class="form-control" name="option_c" placeholder="Option C" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="option_d">
                                </div>
                                <input type="text" class="form-control" name="option_d" placeholder="Option D" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="explanation" class="form-label">Explanation</label>
                        <textarea class="form-control" id="explanation" name="explanation" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Question</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
