@extends('layouts.admin')

@section('title', 'Categories')

@section('page_title', 'Categories')

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
                    <h5 class="mb-0">Manage Categories</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-plus-circle me-2"></i> Add New Category
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">
                                        Name
                                        <a href="{{ route('admin.categories', ['sort' => 'name', 'order' => $sortColumn == 'name' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}" class="btn btn-sm btn-link text-decoration-none">
                                            <i class="fas fa-sort{{ $sortColumn == 'name' ? ($sortOrder == 'asc' ? '-up' : '-down') : '' }}"></i>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        Parent Category
                                        <a href="{{ route('admin.categories', ['sort' => 'parent_id', 'order' => $sortColumn == 'parent_id' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}" class="btn btn-sm btn-link text-decoration-none">
                                            <i class="fas fa-sort{{ $sortColumn == 'parent_id' ? ($sortOrder == 'asc' ? '-up' : '-down') : '' }}"></i>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        Description
                                        <a href="{{ route('admin.categories', ['sort' => 'description', 'order' => $sortColumn == 'description' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}" class="btn btn-sm btn-link text-decoration-none">
                                            <i class="fas fa-sort{{ $sortColumn == 'description' ? ($sortOrder == 'asc' ? '-up' : '-down') : '' }}"></i>
                                        </a>
                                    </th>
                                    <th scope="col">
                                        Category Level
                                        <a href="{{ route('admin.categories', ['sort' => 'level', 'order' => $sortColumn == 'level' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}" class="btn btn-sm btn-link text-decoration-none">
                                            <i class="fas fa-sort{{ $sortColumn == 'level' ? ($sortOrder == 'asc' ? '-up' : '-down') : '' }}"></i>
                                        </a>
                                    </th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                <tr>
                                    <th scope="row">{{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}</th>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->parent ? $category->parent->name : '-' }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td>
                                        {{ $category->level }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $category->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No categories found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        @if ($categories->hasPages())
                            <nav>
                                <ul class="pagination">
                                    
                                    @if ($categories->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link" aria-hidden="true">&laquo;</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $categories->previousPageUrl() }}&sort={{ $sortColumn }}&order={{ $sortOrder }}" rel="prev" aria-label="@lang('pagination.previous')">&laquo;</a>
                                        </li>
                                    @endif

                                    
                                    @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                        @php
                                            $url = $url . '&sort=' . $sortColumn . '&order=' . $sortOrder;
                                        @endphp
                                        @if ($page == $categories->currentPage())
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    
                                    @if ($categories->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $categories->nextPageUrl() }}&sort={{ $sortColumn }}&order={{ $sortOrder }}" rel="next" aria-label="@lang('pagination.next')">&raquo;</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link" aria-hidden="true">&raquo;</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoryForm" action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Category</label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">None (Top Level)</option>
                            @foreach($allCategories as $cat)
                                @if($cat->level != 3)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} (Level {{ $cat->level }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Edit Category Modals -->
@foreach($categories as $category)
<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Edit Category: {{ $category->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name{{ $category->id }}" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit_name{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_parent_id{{ $category->id }}" class="form-label">Parent Category</label>
                        <select class="form-select" id="edit_parent_id{{ $category->id }}" name="parent_id">
                            <option value="">None (Top Level)</option>
                            @foreach($allCategories as $parentCategory)
                                @if($parentCategory->id != $category->id && $parentCategory->level <= 2)
                                    <option value="{{ $parentCategory->id }}" {{ $category->parent_id == $parentCategory->id ? 'selected' : '' }}>
                                        {{ $parentCategory->name }} (Level {{ $parentCategory->level }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description{{ $category->id }}" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description{{ $category->id }}" name="description" rows="3">{{ $category->description }}</textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="edit_is_popular{{ $category->id }}" name="is_popular" value="1" {{ $category->is_popular ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_is_popular{{ $category->id }}">Mark as Popular</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Delete Category Modals -->
@foreach($categories as $category)
<div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel{{ $category->id }}">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category <strong>{{ $category->name }}</strong>?</p>
                <p class="text-danger">This action cannot be undone. All associated data will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
