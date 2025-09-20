@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <br>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Payment Methods</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMethodModal">
                <i class="bi bi-plus-circle me-1"></i> Add New
            </button>
        </div>

        <!-- Payment Methods Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Icon</th>
                            <th>Method</th>
                            <th>Description</th>
                            <th>Number</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($methods as $method)
                            <tr>
                                <td>{{ $method->id }}</td>
                                <td>
                                    <img src="{{ $method->icon }}" alt="icon" class="img-fluid rounded" style="height:40px;">
                                </td>
                                <td class="fw-bold">{{ $method->method }}</td>
                                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $method->description }}">
                                    {{ $method->description }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $method->number }}</span>
                                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyToClipboard('{{ $method->number }}')">
                                        Copy
                                    </button>
                                </td>
                                <td>
                                    @if($method->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $method->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $method->updated_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editMethodModal{{ $method->id }}">
                                            <i class="bi bi-pencil-square">Edit</i>
                                        </button>

                                        <!-- Delete -->
                                        <button class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteMethodModal{{ $method->id }}">
                                            <i class="bi bi-trash">Delete</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editMethodModal{{ $method->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.payment-methods.update', $method->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title">Edit Payment Method</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Icon</label>
                                                    <input type="file" name="icon" class="form-control" value="{{ $method->icon }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Method</label>
                                                    <input type="text" name="method" class="form-control" value="{{ $method->method }}">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="description" class="form-control">{{ $method->description }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Number</label>
                                                    <input type="text" name="number" class="form-control" value="{{ $method->number }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="1" {{ $method->status == 1 ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ $method->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteMethodModal{{ $method->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.payment-methods.destroy', $method->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Delete Confirmation</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <p>Are you sure you want to delete <strong>{{ $method->method }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
    <div class="modal fade" id="addMethodModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.payment-methods.store') }}"
                      method="POST"
                      enctype="multipart/form-data"> <!-- এখানে যুক্ত করা হলো -->
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Payment Method</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Icon</label>
                            <input type="file" name="icon" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Method</label>
                            <input type="text" name="method" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Number</label>
                            <input type="text" name="number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Method</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- JS for Copy -->
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert("Copied: " + text);
            });
        }
    </script>

@endsection
