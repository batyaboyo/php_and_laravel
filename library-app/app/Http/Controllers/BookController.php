<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['author', 'category']);

        if (request('search')) {

            $books->where('title', 'like', '%' . request('search') . '%');

        }

        $books = $books->paginate(5)->withQueryString();

        return view('books.index', compact('books'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $authors = Author::all();
        return view("books.create", compact("categories","authors"));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'isbn' => 'required|unique:books',
            'published_year' => 'required|integer',
            'available_copies' => 'required|integer|min:1',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id'
        ]);

        Book::create([
            'title' => $request->title,
            'isbn' => $request->isbn,
            'published_year' => $request->published_year,
            'available_copies' => $request->available_copies,
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
        ]);

        return redirect('/books')->with('success', 'Book added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact(
            'book',
            'authors',
            'categories'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required',
            'isbn' => 'required|unique:books,isbn,' . $book->id,
            'published_year' => 'required|integer',
            'available_copies' => 'required|integer|min:1',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book->update($request->all());

        return redirect('/books')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect('/books')->with('success', 'Book deleted successfully.');
    }
}
