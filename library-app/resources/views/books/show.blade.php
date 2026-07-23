@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $book->title }}</h2>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Back to books</a>
    </div>

    <div class="card">
        <div class="card-body">
            <p><strong>Author:</strong> {{ $book->author->name ?? 'N/A' }}</p>
            <p><strong>Category:</strong> {{ $book->category->name ?? 'N/A' }}</p>
            <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
            <p><strong>Published Year:</strong> {{ $book->published_year }}</p>
            <p><strong>Available Copies:</strong> {{ $book->available_copies }}</p>
        </div>
    </div>

    @auth
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Delete this book?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>
    @endauth
@endsection
