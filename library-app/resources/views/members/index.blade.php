@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Members</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('borrow-records.create') }}" class="btn btn-primary">Borrow Book</a>
            <a href="{{ route('members.create') }}" class="btn btn-success">Add Member</a>
        </div>
    </div>

    @if($members->isEmpty())
        <div class="alert alert-info">No members yet.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Membership Date</th>
                    <th>Status</th>
                    <th>Loans</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ optional($member->membership_date)->toDateString() ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $member->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </td>
                        <td>{{ $member->borrow_records_count }}</td>
                        <td>
                            <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-info">History</a>
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this member?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{ $members->links() }}
@endsection
