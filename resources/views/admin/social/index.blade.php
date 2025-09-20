@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <br>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Social Links</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSocialModal">
                <i class="bi bi-plus-circle me-1"></i> Add New
            </button>
        </div>

        <!-- Social Links Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>URL</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($links as $link)
                            <tr>
                                <td>{{ $link->id }}</td>
                                <td>
                                    <img src="{{ asset($link->image) }}" alt="icon" class="img-fluid rounded"
                                         style="height:40px;">
                                </td>
                                <td class="fw-bold">{{ $link->name }}</td>
                                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                    title="{{ $link->url }}">
                                    <a href="{{ $link->url }}" target="_blank" class="text-decoration-none">
                                        {{ $link->url }}
                                    </a>
                                    <button class="btn btn-sm btn-outline-primary ms-2"
                                            onclick="copyToClipboard('{{ $link->url }}')">
                                        Copy
                                    </button>
                                </td>
                                <td>{{ $link->created_at ? $link->created_at->format('Y-m-d H:i') : '-' }}</td>
                                <td>{{ $link->updated_at ? $link->updated_at->format('Y-m-d H:i') : '-' }}</td>
                                <td>
                                    <!-- Edit -->
                                    <button class="btn btn-sm btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editSocialModal{{ $link->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Delete -->
                                    <button class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteSocialModal{{ $link->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSocialModal{{ $link->id }}" tabindex="-1"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.social-links.update', $link->id) }}"
                                              method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title">Edit Social Link</h5>
                                                <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Current Image</label><br>
                                                    <img src="{{ asset($link->image) }}" alt="" height="40"
                                                         class="mb-2">
                                                    <input type="file" name="image" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                           value="{{ $link->name }}">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">URL</label>
                                                    <input type="url" name="url" class="form-control"
                                                           value="{{ $link->url }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel
                                                </button>
                                                <button type="submit" class="btn btn-warning">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteSocialModal{{ $link->id }}" tabindex="-1"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.social-links.destroy', $link->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Delete Confirmation</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <p>Are you sure you want to delete
                                                    <strong>{{ $link->name }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel
                                                </button>
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addSocialModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.social-links.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Social Link</h5>
                        <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">URL</label>
                            <input type="url" name="url" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">Add Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- JS for Copy -->
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function () {
                alert("Copied: " + text);
            });
        }
    </script>
@endsection
