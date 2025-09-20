@extends('admin.layouts.app')

{{-- Add a section for custom page styles --}}
@push('styles')
    <style>
        /* Professional Dark Theme Adjustments */
        body {
            background-color: #212529; /* Dark background for the whole page */
            color: #f8f9fa;
        }
        .card {
            background-color: #343a40;
            border: 1px solid #495057;
        }
        .table {
            border-color: #495057;
        }
        .modal-content {
            background-color: #2c3034;
            color: #f8f9fa;
            border: 1px solid #495057;
        }
        .modal-header, .modal-footer {
            border-bottom-color: #495057;
            border-top-color: #495057;
        }
        .form-control {
            background-color: #495057;
            color: #f8f9fa;
            border-color: #6c757d;
        }
        .form-control:focus {
            background-color: #495057;
            color: #f8f9fa;
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .form-control::placeholder {
            color: #adb5bd;
        }
        .form-label {
            color: #ced4da;
        }
        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        /* Dark Pagination */
        .pagination .page-link {
            background-color: #343a40;
            border-color: #495057;
            color: #dee2e6;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .pagination .page-item.disabled .page-link {
            background-color: #212529;
            border-color: #495057;
            color: #6c757d;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid mt-4">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-white">Category Management</h1>
            <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i> Add New Category
            </button>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Category Table Card -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-dark table-hover align-middle text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($category->thumbnail)
                                    <img src="{{ url("storage/$category->thumbnail") }}" alt="Thumbnail" class="rounded" width="60" height="60" style="object-fit: cover;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->created_at?->diffForHumans() ?? 'N/A' }}</td>
                            <td>
                                <!-- Edit Button with Data Attributes -->
                                <button class="btn btn-sm btn-outline-info me-2 edit-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-description="{{ $category->description }}"
                                        data-sort="{{ $category->sort }}"
                                        data-thumbnail="{{ $category->thumbnail ? asset('storage/' . $category->thumbnail) : '' }}"
                                        data-action="{{ route('admin.categories.update', $category->id) }}">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No categories found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="mt-3 d-flex justify-content-center">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Category Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="addName" class="form-label">Category Name</label>
                            <input type="text" id="addName" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="addDescription" class="form-label">Description</label>
                            <textarea id="addDescription" name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="addThumbnail" class="form-label">Thumbnail</label>
                            <input type="file" id="addThumbnail" name="thumbnail" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="addSort" class="form-label">Sort Order</label>
                            <input type="number" id="addSort" name="sort" class="form-control" value="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SINGLE Edit Category Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                {{-- The form action will be set dynamically via JavaScript --}}
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Category Name</label>
                            <input type="text" id="editName" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea id="editDescription" name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editThumbnail" class="form-label">New Thumbnail (Optional)</label>
                            <input type="file" id="editThumbnail" name="thumbnail" class="form-control">
                            <div class="mt-2">
                                <small class="text-muted">Current Image:</small><br>
                                <img id="currentThumbnail" src="" alt="Current Thumbnail" class="rounded mt-1" width="100" style="display: none;">
                                <span id="noThumbnail" class="text-muted" style="display: none;">None</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editSort" class="form-label">Sort Order</label>
                            <input type="number" id="editSort" name="sort" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Update Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editModal = document.getElementById('editModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function (event) {
                    // Button that triggered the modal
                    const button = event.relatedTarget;

                    // Extract info from data-* attributes
                    const name = button.getAttribute('data-name');
                    const description = button.getAttribute('data-description');
                    const sort = button.getAttribute('data-sort');
                    const thumbnailSrc = button.getAttribute('data-thumbnail');
                    const action = button.getAttribute('data-action');

                    // Update the modal's content.
                    const modalForm = editModal.querySelector('#editCategoryForm');
                    const modalTitle = editModal.querySelector('.modal-title');
                    const nameInput = editModal.querySelector('#editName');
                    const descriptionInput = editModal.querySelector('#editDescription');
                    const sortInput = editModal.querySelector('#editSort');
                    const currentThumbnailImg = editModal.querySelector('#currentThumbnail');
                    const noThumbnailSpan = editModal.querySelector('#noThumbnail');

                    // Set the form action dynamically
                    modalForm.action = action;

                    // Populate the form fields
                    modalTitle.textContent = `Edit Category: ${name}`;
                    nameInput.value = name;
                    descriptionInput.value = description;
                    sortInput.value = sort;

                    // Handle the current thumbnail preview
                    if (thumbnailSrc) {
                        currentThumbnailImg.src = thumbnailSrc;
                        currentThumbnailImg.style.display = 'block';
                        noThumbnailSpan.style.display = 'none';
                    } else {
                        currentThumbnailImg.style.display = 'none';
                        noThumbnailSpan.style.display = 'inline';
                    }
                });
            }
        });
    </script>
@endpush
