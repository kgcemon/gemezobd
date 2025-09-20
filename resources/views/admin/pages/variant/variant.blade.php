@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">All Variants for {{ $product->name }}</h4>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                Add New Variant
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price (৳)</th>
                        <th>Sort</th>
                        <th>Denom</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($variants as $variant)
                        <tr>
                            <td>{{ $variant->id }}</td>
                            <td>{{ $variant->name }}</td>
                            <td>{{ $variant->price }}৳</td>
                            <td>{{ $variant->sort }}</td>
                            <td>{{ $variant->denom }}</td>
                            <td>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editVariantModal"
                                    data-id="{{ $variant->id }}"
                                    data-name="{{ $variant->name }}"
                                    data-price="{{ $variant->price }}"
                                    data-sort="{{ $variant->sort }}"
                                    data-des="{{ $variant->description }}"
                                >Edit</button>


                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteVariantModal"
                                    data-id="{{ $variant->id }}"
                                    data-name="{{ $variant->name }}"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No variants found for this product.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addVariantModal" tabindex="-1" aria-labelledby="addVariantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.variant.store', request()->segment(3)) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVariantModalLabel">Add New Variant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="addVariantName" class="form-label">Variant Name</label>
                            <input type="text" class="form-control" id="addVariantName" name="name" required>
                            <input type="hidden" class="form-control" value="{{$product->id}}" name="productID">
                        </div>
                        <div class="mb-3">
                            <label for="addVariantPrice" class="form-label">Price (৳)</label>
                            <input type="number" class="form-control" id="addVariantPrice" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="addVariantPrice" class="form-label">Description</label>
                            <input size="90" type="text" class="form-control" id="addVariantDes" name="description">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Variant</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="editVariantModal" tabindex="-1" aria-labelledby="editVariantModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <form id="editVariantForm" method="POST" action="{{ route('admin.variant.update', $product->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editVariantModalLabel">Edit Variant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editVariantName" class="form-label">Variant Name</label>
                            <input type="text" class="form-control" id="editVariantName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editVariantPrice" class="form-label">Price (৳)</label>
                            <input type="number" class="form-control" id="editVariantPrice" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="editVariantSort" class="form-label">Sort</label>
                            <input type="number" class="form-control" id="editVariantSort" name="sort" required>
                        </div>
                        <div class="mb-3">
                            <label for="editVariantDenom" class="form-label">Denom</label>
                            <input type="text" class="form-control" id="editVariantDenom" name="denom">
                        </div>
                        <div class="mb-3">
                            <label for="editVariantDes" class="form-label">Description</label>
                            <input type="text" class="form-control" id="editVariantDes" name="description">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="deleteVariantModal" tabindex="-1" aria-labelledby="deleteVariantModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <form id="deleteVariantForm" method="POST" action="{{ route('admin.variant.destroy', $product->id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteVariantModalLabel">Delete Variant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the variant "<strong id="deleteVariantName"></strong>"?</p>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const editModal = document.getElementById('editVariantModal');

            editModal.addEventListener('show.bs.modal', function (event) {

                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const price = button.getAttribute('data-price');
                const sort = button.getAttribute('data-sort');
                const description = button.getAttribute('data-des');

                const form = document.getElementById('editVariantForm');
                const nameInput = document.getElementById('editVariantName');
                const priceInput = document.getElementById('editVariantPrice');
                const sorts = document.getElementById('editVariantSort');
                const des = document.getElementById('editVariantDes');
                const denom = document.getElementById('editVariantDenom');

                form.action = `/admin/variant/${id}`;


                nameInput.value = name;
                priceInput.value = price;
                sorts.value = sort;
                sorts.value = sort;
                des.value = description;
            });

            const deleteModal = document.getElementById('deleteVariantModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                const form = document.getElementById('deleteVariantForm');
                const namePlaceholder = document.getElementById('deleteVariantName');

                form.action = `/admin/variant/${id}`;
                namePlaceholder.textContent = name;
            });
        });
    </script>
@endpush
