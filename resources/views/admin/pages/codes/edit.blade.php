@extends('admin.layouts.app')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <!-- Search & Filter -->
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search Code..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                        <option value="unused" {{ request('status') == 'unused' ? 'selected' : '' }}>Unused</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <!-- Codes Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Denom</th>
                        <th>Order Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($codes as $code)
                        <tr>
                            <td>{{ $code->id }}</td>
                            <td style="font-size: 12px">{{ $code->code }}</td>
                            <td>{{ $code->denom ?? '-' }}</td>
                            <td><a href="/admin/orders/{{$code->order_id}}">{{ $code->order_id ?? '' }}</a> </td>
                            <td>{{ $code->status }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editVariantModal"
                                        data-id="{{ $code->id }}"
                                        data-code="{{ $code->code }}"
                                        data-denom="{{ $code->denom }}"
                                        data-status="{{ $code->status }}"
                                        data-item_id="{{ $code->item_id }}"
                                    >Edit</button>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteVariantModal"
                                        data-id="{{ $code->id }}"
                                        data-name="{{ $code->code }}"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No codes found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $codes->appends(request()->query())->links('admin.layouts.partials.__pagination') }}
            </div>
        </div>
    </div>

    <!-- Edit Code Modal -->
    <div class="modal fade" id="editVariantModal" tabindex="-1" aria-labelledby="editVariantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editVariantForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editVariantModalLabel">Edit Code</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editCodeText" class="form-label">Code</label>
                            <input type="text" class="form-control" id="editCodeText" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCodeDenom" class="form-label">Denom</label>
                            <input type="text" class="form-control" id="editCodeDenom" name="denom" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCodestatus" class="form-label">Status</label>
                            <select class="form-select" id="editCodestatus" name="status" required>
                                <option value="used">Used</option>
                                <option value="unused">Unused</option>
                            </select>
                        </div>
                        <input type="hidden" name="item_id" id="editItemId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Code</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Code Modal -->
    <div class="modal fade" id="deleteVariantModal" tabindex="-1" aria-labelledby="deleteVariantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteVariantForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Code</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the code "<strong id="deleteVariantName"></strong>"?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- EDIT MODAL SCRIPT ---
            const editModal = document.getElementById('editVariantModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');
                const code = button.getAttribute('data-code');
                const denom = button.getAttribute('data-denom');
                const status = button.getAttribute('data-status');
                const itemId = button.getAttribute('data-item_id');

                const form = document.getElementById('editVariantForm');
                form.action = `/admin/codes/${id}`;

                document.getElementById('editCodeText').value = code;
                document.getElementById('editCodeDenom').value = denom;
                document.getElementById('editCodestatus').value = status;
                document.getElementById('editItemId').value = itemId;
            });

            // --- DELETE MODAL SCRIPT ---
            const deleteModal = document.getElementById('deleteVariantModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                const form = document.getElementById('deleteVariantForm');
                form.action = `/admin/codes/${id}`;
                document.getElementById('deleteVariantName').textContent = name;
            });
        });
    </script>
@endpush
