<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a paginated list of books.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->input('category') . '%');
        }

        $books = $query->latest()->paginate(5);

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'author'       => ['required', 'string', 'max:255'],
            'isbn'         => ['required', 'string', 'max:255', 'unique:books,isbn'],
            'category'     => ['nullable', 'string', 'max:255'],
            'total_copies' => ['required', 'integer', 'min:1'],
            'cover_image'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $validated['available_copies'] = $validated['total_copies'];

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    /**
     * Display details of the specified book.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'author'       => ['required', 'string', 'max:255'],
            'isbn'         => ['required', 'string', 'max:255', Rule::unique('books', 'isbn')->ignore($book->id)],
            'category'     => ['nullable', 'string', 'max:255'],
            'total_copies' => ['required', 'integer', 'min:1'],
            'cover_image'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // Recalculate available copies based on currently active (unreturned) loans
        $activeLoansCount = $book->borrowRecords()->whereNull('returned_date')->count();
        $validated['available_copies'] = max(0, $validated['total_copies'] - $activeLoansCount);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
