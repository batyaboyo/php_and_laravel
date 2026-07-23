@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h2 class="text-2xl font-semibold mb-4">Borrow a Book</h2>
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded mb-4">
            No active members are available yet. Create a member to continue.
        </div>
        <a href="{{ route('members.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Create a member</a>
    </div>
@endsection
