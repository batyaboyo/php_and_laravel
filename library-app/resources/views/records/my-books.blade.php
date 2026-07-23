@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h3 fw-bold mb-0 text-dark">My Borrowed Books</h2>
            <p class="text-muted small mb-0">Manage your active book loans and return books</p>
        </div>
        <a href="{{ route('books.index') }}" class="btn btn-outline-primary btn-sm">
            Borrow More Books
        </a>
    </div>

    @if ($records->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <h5 class="text-secondary">No active borrowed books</h5>
                <p class="text-muted small">You do not currently have any active borrow records.</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm mt-2">
                    Browse Library Catalog
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4">Book Title</th>
                            <th>Borrowed Date</th>
                            <th>Due Date</th>
                            <th>Fine Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach ($records as $record)
                            @php
                                $isOverdue = now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($record->due_date)->startOfDay());
                            @endphp
                            <tr class="{{ $isOverdue ? 'table-danger-subtle' : '' }}">
                                <td class="ps-4 fw-semibold text-dark">
                                    {{ $record->book->title }}
                                </td>
                                <td>{{ $record->borrowed_date ? $record->borrowed_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @if($isOverdue)
                                        <span class="badge bg-danger text-white">
                                            {{ $record->due_date ? $record->due_date->format('M d, Y') : 'N/A' }} (Overdue)
                                        </span>
                                    @else
                                        <span class="text-dark">
                                            {{ $record->due_date ? $record->due_date->format('M d, Y') : 'N/A' }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($record->fine > 0)
                                        <span class="badge bg-danger text-white">
                                            ${{ number_format($record->fine, 2) }}
                                        </span>
                                    @else
                                        <span class="text-success small fw-semibold">
                                            No Fine
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('borrow-records.return', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('Return this book to the library?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm">
                                            Return Book
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
