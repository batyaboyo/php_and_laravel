@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h3 fw-bold mb-0 text-dark">Overdue Books</h2>
            <p class="text-muted small mb-0">Books that have not been returned after their due date</p>
        </div>
        <span class="badge bg-danger fs-6">{{ $records->count() }} overdue</span>
    </div>

    @if ($records->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <h5 class="text-secondary">No overdue books</h5>
                <p class="text-muted small mb-0">All borrowed books are currently within their due dates.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4">Book</th>
                            <th>Borrower</th>
                            <th>Borrowed Date</th>
                            <th>Due Date</th>
                            <th>Days Overdue</th>
                            <th class="text-end pe-4">Current Fine</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach ($records as $record)
                            @php
                                $daysOverdue = (int) $record->due_date->diffInDays(today());
                            @endphp
                            <tr class="table-danger-subtle">
                                <td class="ps-4 fw-semibold text-dark">{{ $record->book->title }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $record->user->name }}</div>
                                    <div class="text-muted small">{{ $record->user->email }}</div>
                                </td>
                                <td>{{ $record->borrowed_date->format('M d, Y') }}</td>
                                <td>{{ $record->due_date->format('M d, Y') }}</td>
                                <td class="fw-bold text-danger">
                                    {{ $daysOverdue }} {{ Str::plural('day', $daysOverdue) }}
                                </td>
                                <td class="text-end pe-4 fw-bold text-danger">
                                    UGX {{ number_format($record->accruedFine()) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection