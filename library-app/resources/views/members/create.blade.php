@extends('layouts.app')

@section('content')
    <h2>Add Member</h2>
    <form action="{{ route('members.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Membership Date</label>
            <input type="date" name="membership_date" class="form-control" value="{{ old('membership_date', now()->toDateString()) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
        <button class="btn btn-primary">Save</button>
    </form>
@endsection
