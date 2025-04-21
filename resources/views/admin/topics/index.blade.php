@extends('layouts.admin')

@section('title', 'Topics')

@section('page_title', 'Topics')

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
                    <h5 class="mb-0">Manage Topics</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTopicModal">
                        <i class="fas fa-plus-circle me-2"></i> Add New Topic
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Topics Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" style="table-layout: fixed;"> {{-- Add table-layout: fixed --}}
                            <colgroup> {{-- Define column widths --}}
                                <col style="width: 5%;">
                                <col style="width: 30%;">
                                <col style="width: 30%;">
                                <col style="width: 15%;">
                                <col style="width: 20%;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Related Exams</th>
                                    {{-- Removed Question Papers Header --}}
                                    <th scope="col" class="text-center">Questions</th> {{-- Center align count --}}
                                    <th scope="col" class="text-center">Actions</th> {{-- Center align actions --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topics as $index => $topic)
                                <tr>
                                    <th scope="row">{{ $topics->firstItem() + $index }}</th>
                                    <td>{{ $topic->name }}</td>
                                    <td>
                                        @forelse($topic->exams->unique('id') as $exam)
                                            <span class="badge bg-primary">{{ $exam->name }}</span>
                                        @empty
                                            <span class="text-muted">No exams</span>
                                        @endforelse
                                    </td>
                                    {{-- Removed Question Papers Count --}}
                                    <td class="text-center">{{ $topic->questions->count() }}</td> {{-- Center align count --}}
                                    <td class="text-center"> {{-- Center align actions --}}
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            </a>
                                            <a href="{{ route('admin.topics.show', $topic) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTopicModal{{ $topic->id }}" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No topics found</td> {{-- Adjusted colspan --}}
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $topics->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Topic Modal -->
<div class="modal fade" id="addTopicModal" tabindex="-1" aria-labelledby="addTopicModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTopicModalLabel">Add New Topic</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/admin/topics') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Topic Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Topic</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Topic Modals -->
@foreach($topics as $topic)
<div class="modal fade" id="deleteTopicModal{{ $topic->id }}" tabindex="-1" aria-labelledby="deleteTopicModalLabel{{ $topic->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTopicModalLabel{{ $topic->id }}">Delete Topic</h5>
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
@endforeach
@endsection
