@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3" style="padding: 15px!important;">
            <h4 class="fw-bold">{{ $product->name}} UniPin</h4>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                Add New Code
            </button>
        </div>

        {{-- Session Messages for Success/Error Feedback --}}
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif



        <div class="container py-1">
            <div class="card shadow-sm border-0">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead class="table-dark">
                        <tr>
                            <th>Variant</th>
                            <th>Unuse/Used</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($codesCountPerVariant as $row)
                            <tr>
                                <td>{{ $row->codeByDenom->name ?? 'Unknown Variant' }}</td>
                                <td>
                                    <strong class="{{ $row->total_unused < 3 ? 'text-danger' : '' }}">
                                        {{ $row->total_unused }} / {{$row->total_used}}
                                    </strong>
                                </td>
                                <td>
{{--                                    <a href="/admin/code/{{$row->codeByDenom->denom}}">View</a>--}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    No unused codes found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




    </div>

    <!-- Add Code Modal -->
    <div class="modal fade" id="addVariantModal" tabindex="-1" aria-labelledby="addVariantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.codes.store', $product->id) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVariantModalLabel">Add New Code</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Select Item/Variant -->
                        <div class="mb-3">
                            <label for="variant_id" class="form-label">Select Item</label>
                            <select class="form-select" name="item_id" id="item_id" required>
                                <option value="" selected disabled>-- Choose an Item --</option>
                                @foreach ($product->items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Codes Textarea -->
                        <div class="mb-3">
                            <label for="codes" class="form-label">Codes (one per line)</label>
                            <textarea class="form-control" id="codes" name="codes" rows="5" placeholder="Enter each code on a new line" required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Codes</button>
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

            // --- EDIT MODAL SCRIPT ---
            const editModal = document.getElementById('editVariantModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');
                const code = button.getAttribute('data-code');
                const eDenom = button.getAttribute('data-denom');
                const estatus = button.getAttribute('data-status');
                const itemId = button.getAttribute('data-item_id');


                const form = document.getElementById('editVariantForm');
                const codeInput = document.getElementById('editCodeText');
                const itemSelect = document.getElementById('editVariantItem');
                const denom = document.getElementById('editCodeDenom');
                const status = document.getElementById('editCodestatus');

                form.action = `/admin/codes/${id}`; // Route must match your update route
                codeInput.value = code;
                denom.value = eDenom;
                status.value = estatus;

                // Set selected item
                [...itemSelect.options].forEach(option => {
                    option.selected = option.value === itemId;
                });
            });

            // --- DELETE MODAL SCRIPT ---
            const deleteModal = document.getElementById('deleteVariantModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                const form = document.getElementById('deleteVariantForm');
                const namePlaceholder = document.getElementById('deleteVariantName');

                form.action = `/admin/codes/${id}`;
                namePlaceholder.textContent = name;
            });
        });
    </script>
@endpush
