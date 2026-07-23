@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h4 fw-bold mb-0 text-dark">Member Profile</h2>
        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm">
            Back to Members
        </a>
    </div>

    <!-- Profile Overview Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                <div>
                    <h3 class="h4 fw-bold text-dark mb-1">{{ $member->name }}</h3>
                    <p class="text-muted mb-0">{{ $member->email }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-warning text-dark btn-sm">
                        Edit Member
                    </a>
                    @if ($member->membership_status === 'active')
                        <form action="{{ route('members.suspend', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Suspend this member account?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                Suspend Account
                            </button>
                        </form>
                    @else
                        <form action="{{ route('members.activate', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Activate this member account?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                Activate Account
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="row g-3 border-top pt-3 small">
                <div class="col-md-3">
                    <span class="text-secondary d-block fw-medium">Membership Number</span>
                    <span class="fw-bold text-dark fs-6">{{ $member->membership_number ?? 'LIB-' . str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-secondary d-block fw-medium">Status</span>
                    @if ($member->membership_status === 'active')
                        <span class="badge bg-success text-white">Active</span>
                    @else
                        <span class="badge bg-danger text-white">Suspended</span>
                    @endif
                </div>
                <div class="col-md-3">
                    <span class="text-secondary d-block fw-medium">Borrowing Limit</span>
                    <span class="fw-semibold text-dark">{{ $member->max_books ?? 3 }} books</span>
                </div>
                <div class="col-md-3">
                    <span class="text-secondary d-block fw-medium">Joined Date</span>
                    <span class="fw-semibold text-dark">
                        {{ $member->joined_date ? $member->joined_date->format('M d, Y') : ($member->created_at ? $member->created_at->format('M d, Y') : 'N/A') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow History Section -->
    <h4 class="h5 fw-bold mb-3 text-dark">Full Borrow History</h4>

    @if ($borrowRecords->isEmpty())
        <div class="card border-0 shadow-sm text-center py-4">
            <div class="card-body">
                <p class="text-muted small mb-0">This member has no borrow history records.</p>
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
                            <th>Returned Date</th>
                            <th>Fine</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach ($borrowRecords as $record)
                            <tr>
                                <td class="ps-4 fw-semibold text-dark">
                                    {{ $record->book ? $record->book->title : 'Unknown Book' }}
                                </td>
                                <td>{{ $record->borrowed_date ? $record->borrowed_date->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $record->due_date ? $record->due_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @if ($record->returned_date)
                                        <span class="badge bg-success-subtle text-success border">
                                            {{ $record->returned_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-dark border">
                                            Not returned
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($record->fine > 0)
                                        <span class="badge bg-danger text-white">
                                            ${{ number_format($record->fine, 2) }}
                                        </span>
                                    @else
                                        <span class="text-muted small">$0.00</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
