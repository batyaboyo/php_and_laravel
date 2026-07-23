@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h4 fw-bold mb-0 text-dark">Book Details</h2>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">
                    Back to Catalog
                </a>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-4">
                    <div class="row g-4">
                        @if($book->cover_image)
                            <div class="col-md-4 text-center">
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}"
                                    class="img-fluid rounded border shadow-sm" style="max-height: 250px; object-fit: cover;">
                            </div>
                        @endif

                        <div class="{{ $book->cover_image ? 'col-md-8' : 'col-12' }}">
                            <h3 class="h4 fw-bold text-dark mb-1">{{ $book->title }}</h3>
                            <p class="text-muted mb-3">By <span class="fw-semibold text-dark">{{ $book->author }}</span></p>

                            <div class="mb-3">
                                @if($book->category)
                                    <span class="badge bg-secondary-subtle text-secondary border me-2">
                                        {{ $book->category }}
                                    </span>
                                @endif

                                @if($book->available_copies > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        {{ $book->available_copies }} / {{ $book->total_copies }} available
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                        0 / {{ $book->total_copies }} available
                                    </span>
                                @endif
                            </div>

                            <ul class="list-group list-group-flush mb-4 small">
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-secondary fw-medium">ISBN</span>
                                    <span class="fw-semibold text-dark">{{ $book->isbn }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-secondary fw-medium">Total Copies</span>
                                    <span class="fw-semibold text-dark">{{ $book->total_copies }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-secondary fw-medium">Available Copies</span>
                                    <span class="fw-semibold text-dark">{{ $book->available_copies }}</span>
                                </li>
                            </ul>

                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                @auth
                                    <form action="{{ route('books.borrow', $book) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" @disabled($book->available_copies < 1)>
                                            Borrow Book
                                        </button>
                                    </form>

                                    <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-warning text-dark">
                                        Edit
                                    </a>

                                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this book?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection