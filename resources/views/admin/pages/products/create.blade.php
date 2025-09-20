@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0">Add New Product</h4>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
            </div>
            <div class="card-body">
                {{-- Alert messages --}}
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

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        {{-- Category --}}
                        <div class="col-md-6">
                            <label>Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="nameInput" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="col-md-6">
                            <label>Slug <span class="text-danger">*</span> <small>(auto-filled, editable)</small></label>
                            <input type="text" name="slug" id="slugInput" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" required>
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Short Description --}}
                        <div class="col-md-6">
                            <label>Short Description</label>
                            <input type="text" name="short_description" class="form-control @error('short_description') is-invalid @enderror" value="{{ old('short_description') }}">
                            @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- SEO Section --}}
                        <div class="col-md-12">
                            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#seoSection" aria-expanded="false" aria-controls="seoSection">
                                <strong>SEO Settings (optional)</strong>
                            </button>
                            <div class="collapse" id="seoSection">
                                <div class="card card-body">
                                    <div class="col-md-6">
                                        <label>SEO Title</label>
                                        <input type="text" name="seo_title" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title') }}">
                                        @error('keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>SEO Description</label>
                                        <input type="text" name="seo_description" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title') }}">
                                        @error('keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>Keywords</label>
                                        <input type="text" name="seo_keywords" class="form-control @error('keywords') is-invalid @enderror" value="{{ old('keywords') }}">
                                        @error('keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Input Fields --}}
                        <div class="col-md-6">
                            <label>Input Name <span class="text-danger">*</span></label>
                            <input type="text" name="input_name" class="form-control @error('input_name') is-invalid @enderror" value="{{ old('input_name') }}" required>
                            @error('input_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Input Others</label>
                            <input type="text" name="input_others" class="form-control @error('input_others') is-invalid @enderror" value="{{ old('input_others') }}">
                            @error('input_others') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label>Total Inputs</label>
                            <input type="number" name="total_input" value="{{ old('total_input', 1) }}" class="form-control @error('total_input') is-invalid @enderror" required>
                            @error('total_input') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label>Sort</label>
                            <input type="number" name="sort" class="form-control @error('sort') is-invalid @enderror" value="{{ old('sort') }}">
                            @error('sort') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label>Stock Status</label>
                            <select name="stock" class="form-select @error('stock') is-invalid @enderror">
                                <option value="1" {{ old('stock', 1) == 1 ? 'selected' : '' }}>In Stock</option>
                                <option value="0" {{ old('stock', 1) == 0 ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Images --}}
                        <div class="col-md-6">
                            <label>Image <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" required>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Cover Image <span class="text-danger">*</span></label>
                            <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" required>
                            @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-md-12">
                            <label>Description</label>
                            <textarea name="description" id="description_editor" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
    <script src="https://cdn.tiny.cloud/1/rx33nh9mrg7zvtjoq6t8vd2ddu0l67uiw9stt1scrdjlb1dh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: '#description_editor',
            plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            height: 300,
        });

        const nameInput = document.getElementById('nameInput');
        const slugInput = document.getElementById('slugInput');
        let manualSlugEdit = false;

        slugInput.addEventListener('input', () => manualSlugEdit = true);

        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/--+/g, '-');
        }

        function updateSlug() {
            const base = slugify(nameInput.value);
            slugInput.value = base;
        }

        nameInput.addEventListener('input', () => {
            if (!manualSlugEdit) updateSlug();
        });

        // Initial slug update on page load if name already filled
        document.addEventListener('DOMContentLoaded', () => {
            if (nameInput.value && !slugInput.value) {
                updateSlug();
            }
        });
    </script>
