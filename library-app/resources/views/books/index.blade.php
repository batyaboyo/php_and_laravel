@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h3 fw-bold mb-0 text-dark">Library Books</h2>
            <p class="text-muted small mb-0">Browse, add, and manage books in the catalog</p>
        </div>
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('books.create') }}" class="btn btn-primary shadow-sm">
                Add Book
            </a>
        @endif
    </div>

    <!-- Member Membership Status Bar -->
    @auth
        @php
            $currentUser = auth()->user();
            $activeBorrows = $currentUser->borrowRecords()->whereNull('returned_date')->get();
            $activeBorrowsCount = $activeBorrows->count();
            $maxBooks = $currentUser->max_books ?? 3;
            $borrowedBookIds = $activeBorrows->pluck('book_id')->toArray();
            $isSuspended = $currentUser->membership_status === 'suspended';
            $isLimitReached = $activeBorrowsCount >= $maxBooks;
        @endphp

        <div class="card border-0 shadow-sm mb-4 bg-white">
            <div class="card-body py-3">
                <div class="row align-items-center text-center text-md-start g-2">
                    <div class="col-md-3">
                        <span class="text-muted small d-block">Member Number</span>
                        <span class="fw-bold text-dark">{{ $currentUser->membership_number ?? 'LIB-' . str_pad($currentUser->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted small d-block">Membership Status</span>
                        @if ($isSuspended)
                            <span class="badge bg-danger text-white">Suspended</span>
                        @else
                            <span class="badge bg-success text-white">Active</span>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted small d-block">Current Borrows</span>
                        <span class="fw-bold {{ $isLimitReached ? 'text-danger' : 'text-dark' }}">
                            {{ $activeBorrowsCount }} / {{ $maxBooks }} Books
                        </span>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <a href="{{ route('my-books') }}" class="btn btn-outline-primary btn-sm">
                            View My Borrows
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('books.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-medium text-secondary small mb-1">Search <!--by Title --> /</label>
                    <input type="text" name="search" class="form-control" placeholder="Type book title..." value="{{ request('search') }}">
                </div>
              <!--  <div class="col-md-4">
                    <label class="form-label fw-medium text-secondary small mb-1">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="Filter category..." value="{{ request('category') }}">
                </div> -->
                <div class="col-md-3 d-flex gap-2"> 
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Search <!--Filter -->
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
                            <th class="ps-4" style="width: 70px;">Cover</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Copies Available</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach ($books as $book)
                            @php
                                $alreadyBorrowed = auth()->check() && in_array($book->id, $borrowedBookIds ?? []);
                            @endphp
                            <tr>
                                <td class="ps-4 py-2">
                                    @if($book->cover_image)
                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                                             class="rounded border shadow-sm" style="width: 45px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary-subtle text-secondary rounded d-flex align-items-center justify-content-center border" 
                                             style="width: 45px; height: 60px; font-size: 1.2rem;">
                                            📖
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold text-dark">
                                    <a href="{{ route('books.show', $book) }}" class="text-decoration-none text-dark fw-bold">
                                        {{ $book->title }}
                                    </a>
                                    @if($alreadyBorrowed)
                                        <span class="badge bg-warning-subtle text-dark border ms-1">Already Borrowed</span>
                                    @endif
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
                                            {{-- View Book Action --}}
                                            <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-info text-dark">
                                                View
                                            </a>

                                            {{-- Borrow Form --}}
                                            <form action="{{ route('books.borrow', $book) }}" method="POST" class="d-inline">
                                                @csrf
                                                @if ($alreadyBorrowed)
                                                    <button type="button" class="btn btn-sm btn-secondary" disabled title="You already have this book borrowed">
                                                        Borrowed
                                                    </button>
                                                @elseif ($isSuspended ?? false)
                                                    <button type="button" class="btn btn-sm btn-secondary" disabled title="Your membership is suspended">
                                                        Suspended
                                                    </button>
                                                @elseif ($isLimitReached ?? false)
                                                    <button type="button" class="btn btn-sm btn-secondary" disabled title="Borrowing limit reached">
                                                        Limit Reached
                                                    </button>
                                                @else
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success" 
                                                            @disabled($book->available_copies < 1)>
                                                        Borrow
                                                    </button>
                                                @endif
                                            </form>

                                            {{-- Edit Button (Admin only) --}}
                                            @if(auth()->user()->role === 'admin')
                                                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-warning text-dark">
                                                    Edit
                                                </a>

                                                {{-- Delete Button --}}
                                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bootstrap 5 Pagination -->
        <div class="d-flex justify-content-center">
            {{ $books->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection
