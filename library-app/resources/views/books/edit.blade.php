<form action="/books/{{ $book->id }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="title" value="{{ $book->title }}">
    <label for="author_id" class="form-label">Author</label>

    <select name="author_id" id="author_id" class="form-control">

        @foreach ($authors as $author)
            <option value="{{ $author->id }}" {{ $book->author_id == $author->id ? 'selected' : '' }}>

                {{ $author->name }}

            </option>
        @endforeach

    </select>
    <input type="text" name="isbn" value="{{ $book->isbn }}">
    <input type="number" name="published_year" value="{{ $book->published_year }}">
    <label for="category_id" class="form-label">Category</label>

    <select name="category_id" id="category_id" class="form-control">

        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>

                {{ $category->name }}

            </option>
        @endforeach

    </select>
    <input type="number" name="available_copies" value="{{ $book->avialable_copies }}">
    <button>Update Book</button>
</form>
