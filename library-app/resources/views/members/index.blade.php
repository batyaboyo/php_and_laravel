@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h3 fw-bold mb-0 text-dark">Library Members</h2>
            <p class="text-muted small mb-0">Manage system members, borrowing limits, and membership statuses</p>
        </div>
    </div>

    @if ($members->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <h5 class="text-secondary">No members found</h5>
                <p class="text-muted small mb-0">No member accounts exist in the system yet.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Membership #</th>
                            <th>Status</th>
                            <th>Borrowed / Max Books</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach ($members as $member)
                            <tr>
                                <td class="ps-4 fw-semibold text-dark">
                                    <a href="{{ route('members.show', $member) }}" class="text-decoration-none text-dark fw-bold">
                                        {{ $member->name }}
                                    </a>
                                    <div class="text-muted small fw-normal">{{ $member->email }}</div>
                                </td>
                                <td class="fw-mono text-dark">{{ $member->membership_number ?? 'LIB-' . str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    @if (($member->membership_status ?? 'active') === 'active')
                                        <span class="badge bg-success text-white px-2 py-1">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger text-white px-2 py-1">
                                            Suspended
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">
                                        {{ $member->active_borrows_count }} / {{ $member->max_books ?? 3 }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1 align-items-center">
                                        <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-info text-dark">
                                            View
                                        </a>
                                        <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-warning text-dark">
                                            Edit
                                        </a>

                                        @if ($member->membership_status === 'active')
                                            <form action="{{ route('members.suspend', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Suspend this member account?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Suspend
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('members.activate', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Activate this member account?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    Activate
                                                </button>
                                            </form>
                                        @endif
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
            {{ $members->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection
