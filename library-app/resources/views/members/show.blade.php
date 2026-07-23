@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ $member->name }}'s History</h2>
        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Email:</strong> {{ $member->email }}</p>
            <p><strong>Phone:</strong> {{ $member->phone ?? 'N/A' }}</p>
            <p><strong>Membership Date:</strong> {{ optional($member->membership_date)->toDateString() ?? 'N/A' }}</p>
            <p><strong>Status:</strong> <span class="badge {{ $member->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($member->status) }}</span></p>
        </div>
    </div>

    <h4>Borrow Records</h4>
    @if($member->borrowRecords->isEmpty())
        <div class="alert alert-info">No borrow history yet.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Borrowed</th>
                    <th>Due Date</th>
                    <th>Returned</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($member->borrowRecords as $record)
                    <tr>
                        <td>{{ $record->book->title ?? 'N/A' }}</td>
                        <td>{{ optional($record->borrowed_date)->toDateString() }}</td>
                        <td>{{ $record->due_date }}</td>
                        <td>{{ optional($record->returned_date)->toDateString() ?? 'Not returned' }}</td>
                        <td>
                            @if(!$record->returned_date)
                                <form action="{{ route('borrow-records.return', $record) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Return</button>
                                </form>
                            @else
                                <span class="text-muted">Completed</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
