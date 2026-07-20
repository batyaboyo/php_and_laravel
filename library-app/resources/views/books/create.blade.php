<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Book</title>
</head>

<body>
    <h1>Add New Book</h1>
    <form action="/books" method="POST">
        @csrf
        <label>Title</label>
        <input type="text" name="title">
        <br><br>
        <label>Author</label>
        <select name="author_id">
            @foreach ($authors as $author)
                <option value="{{ $author->id }}">{{ $author->name }}</option>
            @endforeach
        </select>
        <br><br>
        <label>Category</label>
        <select name="category_id">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <br><br>
        <label>ISBN</label>
        <input type="text" name="isbn">
        <br><br>
        <label>Published Year</label>
        <input type="number" name="published_year">
        <br><br>
        <label>Available Copies</label>
        <input type="number" name="available_copies">
        <br><br>
        <button type="submit">Save Book</button>
    </form>
</body>

</html>
