@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Library Overview</h2>
            <p class="text-muted mb-0">A quick snapshot of your collection, members, and circulation activity.</p>
        </div>
        <a href="{{ route('borrow-records.create') }}" class="btn btn-primary">Issue a book</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Books</div>
                    <div class="display-6 fw-bold">{{ $stats['books'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Authors</div>
                    <div class="display-6 fw-bold">{{ $stats['authors'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Members</div>
                    <div class="display-6 fw-bold">{{ $stats['members'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Active loans</div>
                    <div class="display-6 fw-bold">{{ $stats['activeLoans'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 fw-semibold">Recent circulation</div>
                <div class="card-body">
                    @if($recentLoans->isEmpty())
                        <div class="alert alert-info mb-0">No circulation activity yet.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Book</th>
                                        <th>Status</th>
                                        <th>Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLoans as $loan)
                                        <tr>
                                            <td>{{ $loan->member->name ?? 'N/A' }}</td>
                                            <td>{{ $loan->book->title ?? 'N/A' }}</td>
                                            <td><span class="badge bg-{{ $loan->status === 'overdue' ? 'danger' : 'primary' }}">{{ ucfirst($loan->status) }}</span></td>
                                            <td>{{ optional($loan->due_date)->toDateString() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 fw-semibold">At a glance</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">Categories: {{ $stats['categories'] }}</li>
                        <li class="list-group-item px-0">Overdue loans: {{ $stats['overdueLoans'] }}</li>
                        <li class="list-group-item px-0"><a href="{{ route('books.index') }}" class="text-decoration-none">Browse the book catalog</a></li>
                        <li class="list-group-item px-0"><a href="{{ route('members.index') }}" class="text-decoration-none">Manage members</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
