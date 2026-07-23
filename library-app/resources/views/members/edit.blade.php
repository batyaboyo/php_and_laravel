@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h4 fw-bold mb-0 text-dark">Edit Member</h2>
                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm">
                    Back to Members
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

                    <form action="{{ route('members.update', $member) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $member->name) }}" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $member->email) }}" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Max Books Limit -->
                        <div class="mb-4">
                            <label for="max_books" class="form-label fw-semibold">Max Borrowing Limit (Books) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="max_books" 
                                   id="max_books" 
                                   value="{{ old('max_books', $member->max_books ?? 3) }}" 
                                   min="1" 
                                   class="form-control @error('max_books') is-invalid @enderror" 
                                   required>
                            <div class="form-text small">Maximum number of books this member can borrow concurrently.</div>
                            @error('max_books')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('members.index') }}" class="btn btn-light border">Cancel</a>
                            <button type="submit" class="btn btn-warning px-4 fw-semibold text-dark">
                                Update Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
