@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Borrowing & Returning</h2>
        <a href="{{ route('borrow-records.create') }}" class="btn btn-primary">Issue Book</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">Active Loans</div>
                <div class="card-body">
                    @if($activeLoans->isEmpty())
                        <div class="alert alert-info mb-0">No active loans.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Member</th>
                                        <th>Borrowed</th>
                                        <th>Due</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeLoans as $loan)
                                        <tr>
                                            <td>{{ $loan->book->title ?? 'N/A' }}</td>
                                            <td>{{ $loan->member->name ?? 'N/A' }}</td>
                                            <td>{{ optional($loan->borrowed_date)->toDateString() }}</td>
                                            <td>{{ optional($loan->due_date)->toDateString() }}</td>
                                            <td>
                                                <form action="{{ route('borrow-records.return', $loan) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success">Return</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">Overdue Loans</div>
                <div class="card-body">
                    @if($overdueLoans->isEmpty())
                        <div class="alert alert-info mb-0">No overdue loans.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Member</th>
                                        <th>Borrowed</th>
                                        <th>Due</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overdueLoans as $loan)
                                        <tr>
                                            <td>{{ $loan->book->title ?? 'N/A' }}</td>
                                            <td>{{ $loan->member->name ?? 'N/A' }}</td>
                                            <td>{{ optional($loan->borrowed_date)->toDateString() }}</td>
                                            <td>{{ optional($loan->due_date)->toDateString() }}</td>
                                            <td>
                                                <form action="{{ route('borrow-records.return', $loan) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success">Return</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection