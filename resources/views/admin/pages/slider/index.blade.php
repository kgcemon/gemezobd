@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h4 class="fw-bold mb-3">All Sliders</h4>

        <!-- Add Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Table -->
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Link</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sliders as $slider)
                <tr>
                    <td>{{ $slider->id }}</td>
                    <td><img src="{{ url("$slider->images_url") }}" width="60" alt=""></td>
                    <td>{{ $slider->link }}</td>
                    <td>
                        <!-- Edit -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $slider->id }}">Edit</button>

                        <!-- Delete -->
                        <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this slider?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Slider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Link</label>
                    <input type="text" name="link" class="form-control mb-3" required>

                    <label>Image</label>
                    <input type="file" name="images_url" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach($sliders as $slider)
        <div class="modal fade" id="editModal{{ $slider->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Slider</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Link</label>
                        <input type="text" name="link" class="form-control mb-3" value="{{ $slider->link }}" required>

                        <label>Image (optional)</label>
                        <input type="file" name="images_url" class="form-control mb-2">
                        <small>Current: <img src="{{ asset('storage/' . $slider->images_url) }}" width="60" alt=""></small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
