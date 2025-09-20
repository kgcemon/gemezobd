@extends('admin.layouts.app')

{{-- You might need to add a link to an icon library like Bootstrap Icons in your main layout file --}}
{{-- Example for layouts/app.blade.php: <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> --}}

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Manage Users</h5>

                {{-- Search Form --}}
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex" style="max-width: 300px;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                {{-- Session Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Wallet Balance</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ number_format($user->wallet ?? 0, 2) }} ৳</td>
                                <td>{{ $user->created_at ? $user->created_at->diffForHumans() : 'N/A' }}</td>
                                <td>
                                    {{-- Edit Button to trigger modal --}}
                                    <button type="button" class="btn btn-sm btn-warning me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal"
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}"
                                            data-wallet="{{ $user->wallet ?? 0 }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No users found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-3 d-flex justify-content-center">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm" method="POST" action=""> {{-- Action will be set dynamically by JS --}}
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        {{-- User ID (hidden) --}}
                        <input type="hidden" name="id" id="editUserId">

                        <div class="mb-3">
                            <label for="editUserName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editUserName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="editUserEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserWallet" class="form-label">Wallet Balance (৳)</label>
                            <input type="number" step="0.01" class="form-control" id="editUserWallet" name="wallet">
                        </div>
                        <div class="mb-3">
                            <label for="editUserPassword" class="form-label">New Password (Optional)</label>
                            <input type="password" class="form-control" id="editUserPassword" name="password" placeholder="Leave blank to keep current password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the modal element
            var editUserModal = document.getElementById('editUserModal');

            // Add an event listener for when the modal is about to be shown
            editUserModal.addEventListener('show.bs.modal', function (event) {
                // Get the button that triggered the modal
                var button = event.relatedTarget;

                // Extract data from the data-* attributes of the button
                var userId = button.getAttribute('data-id');
                var userName = button.getAttribute('data-name');
                var userEmail = button.getAttribute('data-email');
                var userWallet = button.getAttribute('data-wallet');

                // Get the form and its input elements inside the modal
                var modalForm = document.getElementById('editUserForm');
                var modalTitle = editUserModal.querySelector('.modal-title');
                var inputId = document.getElementById('editUserId');
                var inputName = document.getElementById('editUserName');
                var inputEmail = document.getElementById('editUserEmail');
                var inputWallet = document.getElementById('editUserWallet');

                // --- Update the modal's content ---

                // 1. Set the form's action attribute dynamically
                //    This assumes your update route is named 'admin.users.update'
                //    and follows the pattern /admin/users/{user}
                modalForm.action = `/admin/users/${userId}`;

                // 2. Update the modal title and form fields with the user's data
                modalTitle.textContent = 'Edit User: ' + userName;
                inputId.value = userId;
                inputName.value = userName;
                inputEmail.value = userEmail;
                inputWallet.value = userWallet;

                // Clear the password field every time the modal opens
                document.getElementById('editUserPassword').value = '';
            });
        });
    </script>
@endpush
