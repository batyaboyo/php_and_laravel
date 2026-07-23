@extends('layouts.app')

@section('content')
    <h2>Edit Member</h2>
    <form action="{{ route('members.update', $member) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ $member->name }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ $member->email }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ $member->phone }}" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Membership Date</label>
            <input type="date" name="membership_date" value="{{ old('membership_date', optional($member->membership_date)->toDateString()) }}" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" {{ $member->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ $member->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
@endsection
