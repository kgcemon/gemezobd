@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">All Products</h4>
            <a href="{{ route('admin.products.create') }}" class="btn btn-success">Add New Product</a>
        </div>

        <div class="card shadow-sm border-0">
            @if(session('success') || session('error'))
                {{session('success')}}
            @endif
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Inputs</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset($product->image) }}" alt="Image" width="50">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>
                                @if($product->stock)
                                    <span class="badge bg-success">In Stock</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                {{ $product->input_name }}
                                @if($product->input_others)
                                    <br><small class="text-muted">{{ $product->input_others }}</small>
                                @endif
                            </td>
                            <td>{{ $product->created_at ? $product->created_at->diffForHumans() : 'N/A' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>

                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No products found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
<!-- SweetAlert -->
@endsection
