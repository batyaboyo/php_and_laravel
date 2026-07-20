@extends('layouts.app')

@section('content')

    <h2 class="mb-4">

        Library Books

    </h2>

    <form action="/books" method="GET" class="mb-3">

        <div class="input-group">

            <input type="text" name="search" class="form-control" placeholder="Search books..."
                value="{{ request('search') }}">

            <button class="btn btn-primary">

                Search

            </button>

        </div>
    </form>

    @auth
        <a href="{{ route('books.create') }}" class="btn btn-success mb-3">
            Add Book
        </a>
    @endauth

    @if ($books->isEmpty())
        <div class="alert alert-info">

            No books available.

        </div>
    @else
        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>

                    <th>Title</th>

                    <th>Author</th>

                    <th>Category</th>

                    <th>Year</th>

                    <th>Copies</th>

                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

                @foreach ($books as $book)
                    <tr>

                        <td>{{ $book->title }}</td>

                        <td>{{ $book->author->name }}</td>

                        <td>{{ $book->category->name }}</td>

                        <td>{{ $book->published_year }}</td>

                        <td>{{ $book->available_copies }}</td>

                        <td>

                            @auth

                                <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline">

                                    @csrf

                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">

                                        Delete

                                    </button>

                                </form>

                            @endauth

                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>
    @endif

    {{ $books->links() }}

@endsection
