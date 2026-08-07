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

    <!-- Member Membership Status Card -->
    @auth
        @php
            $user = auth()->user();
            $activeCount = $records->count();
            $maxLimit = $user->max_books ?? 3;
            $isSuspended = $user->membership_status === 'suspended';
        @endphp
        <div class="card border-0 shadow-sm mb-4 bg-white">
            <div class="card-body py-3">
                <div class="row align-items-center text-center text-md-start g-2">
                    <div class="col-md-3">
                        <span class="text-muted small d-block">Member Name</span>
                        <span class="fw-bold text-dark">{{ $user->name }}</span>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted small d-block">Membership Number</span>
                        <span class="fw-bold text-dark">{{ $user->membership_number ?? 'LIB-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
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
                        <span class="text-muted small d-block">Borrowing Capacity</span>
                        <span class="fw-bold text-dark">{{ $activeCount }} / {{ $maxLimit }} Books Used</span>
                    </div>
                </div>
            </div>
        </div>
    @endauth

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
                                            UGX {{ number_format($record->fine) }}
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
