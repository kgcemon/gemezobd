@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0">Edit Product</h4>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops!</strong> Please correct the following errors:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug) }}" required>
                            @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Short Description</label>
                            <input type="text" name="short_description" class="form-control @error('short_description') is-invalid @enderror" value="{{ old('short_description', $product->short_description) }}">
                            @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Tags (comma-separated)</label>
                            <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" value="{{ old('tags', $product->tags) }}">
                            @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Delivery System</label>
                            <input type="text" name="delivery_system" class="form-control @error('delivery_system') is-invalid @enderror" value="{{ old('delivery_system', $product->delivery_system) }}">
                            @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Support Country</label>
                            <input type="text" name="support_country" class="form-control @error('support_country') is-invalid @enderror" value="{{ old('support_country', $product->support_country) }}">
                            @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>SEO Title</label>
                            <input type="text" name="seo_title" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title', $product->seo_title) }}">
                            @error('keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>SEO Description</label>
                            <input type="text" name="seo_description" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title', $product->seo_description) }}">
                            @error('keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Keywords</label>
                            <input type="text" name="seo_keywords" class="form-control @error('keywords') is-invalid @enderror" value="{{ old('keywords', $product->seo_keywords) }}">
                            @error('keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Input Name <span class="text-danger">*</span></label>
                            <input type="text" name="input_name" class="form-control @error('input_name') is-invalid @enderror" value="{{ old('input_name', $product->input_name) }}" required>
                            @error('input_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Input Others</label>
                            <input type="text" name="input_others" class="form-control @error('input_others') is-invalid @enderror" value="{{ old('input_others', $product->input_others) }}">
                            @error('input_others')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label>Total Inputs</label>
                            <input type="number" name="total_input" class="form-control @error('total_input') is-invalid @enderror" value="{{ old('total_input', $product->total_input) }}">
                            @error('total_input')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label>Sort</label>
                            <input type="number" name="sort" class="form-control @error('sort') is-invalid @enderror" value="{{ old('sort', $product->sort) }}">
                            @error('sort')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label>Stock Status</label>
                            <select name="stock" class="form-select @error('stock') is-invalid @enderror">
                                <option value="1" {{ old('stock', $product->stock) == 1 ? 'selected' : '' }}>In Stock</option>
                                <option value="0" {{ old('stock', $product->stock) == 0 ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                            @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="Image" class="img-thumbnail mt-2" width="100">
                            @endif
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Cover Image</label>
                            <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror">
                            @if($product->cover_image)
                                <img src="{{ asset($product->cover_image) }}" alt="Cover" class="img-thumbnail mt-2" width="100">
                            @endif
                            @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label>Description <span class="text-danger">*</span></label>
                            {{-- Add an ID to the textarea for TinyMCE --}}
                            <textarea name="description" id="description_editor" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- TinyMCE CDN --}}
    <script src="https://cdn.tiny.cloud/1/rx33nh9mrg7zvtjoq6t8vd2ddu0l67uiw9stt1scrdjlb1dh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: '#description_editor', // Target the textarea by its ID
            plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            height: 300, // Adjust height as needed
            // You can add more options here, e.g., for image upload handling
            // file_picker_callback: function (cb, value, meta) { ... }
        });
    </script>
@endpush
