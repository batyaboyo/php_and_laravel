@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h4 fw-bold mb-0 text-dark">Add New Book</h2>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">
                    Back to Catalog
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm mb-4" role="alert">
                            <h6 class="alert-heading fw-bold mb-2">
                                Please fix the following errors:
                            </h6>
                            <ul class="mb-0 ps-3 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title') }}" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   placeholder="e.g. The Great Gatsby" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Author -->
                        <div class="mb-3">
                            <label for="author" class="form-label fw-semibold">Author <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="author" 
                                   id="author" 
                                   value="{{ old('author') }}" 
                                   class="form-control @error('author') is-invalid @enderror" 
                                   placeholder="e.g. F. Scott Fitzgerald" 
                                   required>
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ISBN -->
                        <div class="mb-3">
                            <label for="isbn" class="form-label fw-semibold">ISBN <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="isbn" 
                                   id="isbn" 
                                   value="{{ old('isbn') }}" 
                                   class="form-control @error('isbn') is-invalid @enderror" 
                                   placeholder="e.g. 9780743273565" 
                                   required>
                            @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label fw-semibold">Category</label>
                            <input type="text" 
                                   name="category" 
                                   id="category" 
                                   value="{{ old('category') }}" 
                                   class="form-control @error('category') is-invalid @enderror" 
                                   placeholder="e.g. Fiction, History, Science">
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Total Copies -->
                        <div class="mb-3">
                            <label for="total_copies" class="form-label fw-semibold">Total Copies <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="total_copies" 
                                   id="total_copies" 
                                   value="{{ old('total_copies', 1) }}" 
                                   min="1" 
                                   class="form-control @error('total_copies') is-invalid @enderror" 
                                   required>
                            @error('total_copies')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cover Image -->
                        <div class="mb-4">
                            <label for="cover_image" class="form-label fw-semibold">Cover Image</label>
                            <input type="file" 
                                   name="cover_image" 
                                   id="cover_image" 
                                   class="form-control @error('cover_image') is-invalid @enderror" 
                                   accept="image/*">
                            <div class="form-text small">Optional image file (JPEG, PNG, max 2MB).</div>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('books.index') }}" class="btn btn-light border">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                Save Book
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
