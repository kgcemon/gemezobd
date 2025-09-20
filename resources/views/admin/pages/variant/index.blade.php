@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">

        <div class="card shadow-sm border-0">
            @if(session('success') || session('error'))
                {{session('success')}}
            @endif
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <img src="{{ asset($product->image) }}" alt="Image" width="50">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>
                                <a href="{{ route('admin.variant', $product->id) }}" class="btn btn-sm btn-primary me-1">View Variant</a>
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
@endsection
