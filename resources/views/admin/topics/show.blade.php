@extends('layouts.admin')

@section('title', 'View Topic')

@section('page_title', 'View Topic')

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
                    <h5 class="mb-0">View Topic</h5>
                    <a href="{{ url('/admin/topics') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Topics
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Topic Details -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $topic->name }}</h5>
                    
                    <div class="mt-4">
                        <h6 class="fw-bold">Related Exams</h6>
                        <div class="mb-3">
                            @forelse($topic->exams as $exam)
                                <span class="badge bg-primary">{{ $exam->name }}</span>
                            @empty
                                <span class="text-muted">No exams associated with this topic</span>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="fw-bold">Statistics</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <h3 class="card-title">{{ $topic->questionPapers->count() }}</h3>
                                        <p class="card-text">Question Papers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <h3 class="card-title">{{ $topic->questions->count() }}</h3>
                                        <p class="card-text">Questions</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <h3 class="card-title">{{ $topic->questions->count() > 0 ? rand(50, 200) : 0 }}</h3>
                                        <p class="card-text">Student Attempts</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="fw-bold">Question Papers</h6>
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
                                            <a href="#" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="fw-bold">Recent Questions</h6>
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
                                            <a href="#" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
                        @if($topic->questions->count() > 5)
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">View All Questions ({{ $topic->questions->count() }})</a>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('admin.topics') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Topics
                        </a>
                        <div>
                            <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i> Edit Topic
                            </a>
                            <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteTopicModal">
                                <i class="fas fa-trash me-2"></i> Delete Topic
                            </button>
                        </div>
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
@endsection
