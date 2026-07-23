@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h3 fw-bold mb-0 text-dark">Library Books</h2>
            <p class="text-muted small mb-0">Browse, add, and manage books in the catalog</p>
        </div>
        @auth
            <a href="{{ route('books.create') }}" class="btn btn-primary shadow-sm">
                Add Book
            </a>
        @endauth
    </div>

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('books.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-medium text-secondary small mb-1">Search by Title</label>
                    <input type="text" name="search" class="form-control" placeholder="Type book title..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium text-secondary small mb-1">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="Filter category..." value="{{ request('category') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Filter
                    </button>
                    @if(request()->has('search') || request()->has('category'))
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Books Table -->
    @if ($books->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <h5 class="text-secondary">No books found</h5>
                <p class="text-muted small mb-0">Try searching with a different term or clear filters.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4">Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Copies Available</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach ($books as $book)
                            <tr>
                                <td class="ps-4 fw-semibold text-dark">
                                    {{ $book->title }}
                                    @if($book->isbn)
                                        <div class="text-muted small fw-normal">ISBN: {{ $book->isbn }}</div>
                                    @endif
                                </td>
                                <td>{{ $book->author }}</td>
                                <td>
                                    @if($book->category)
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                            {{ $book->category }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($book->available_copies > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            {{ $book->available_copies }} / {{ $book->total_copies }} available
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                            0 / {{ $book->total_copies }} available
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1 align-items-center">
                                        @auth
                                            {{-- Borrow Form --}}
                                            <form action="{{ route('books.borrow', $book) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success" 
                                                        @disabled($book->available_copies < 1)>
                                                    Borrow
                                                </button>
                                            </form>

                                            {{-- CRUD Action Buttons for any logged in user --}}
                                            <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-warning text-dark">
                                                Edit
                                            </a>
                                            <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        @endauth
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    @endif
@endsection
