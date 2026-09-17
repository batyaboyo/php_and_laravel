<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Display a paginated list of books.
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->input('category') . '%');
        }

        $books = $query->latest()->paginate(10);

        // Resolve current user's active borrows once, pass to view to avoid N+1 in the template.
        $borrowedBookIds = [];
        $activeBorrowsCount = 0;
        $maxBooks = 3;
        $isSuspended = false;
        $isLimitReached = false;

        if (Auth::check()) {
            $user = Auth::user();
            $activeBorrows = $user->borrowRecords()->whereNull('returned_date')->get();
            $activeBorrowsCount = $activeBorrows->count();
            $maxBooks = $user->max_books ?? 3;
            $borrowedBookIds = $activeBorrows->pluck('book_id')->toArray();
            $isSuspended = $user->membership_status === 'suspended';
            $isLimitReached = $activeBorrowsCount >= $maxBooks;
        }

        return view('books.index', compact(
            'books',
            'borrowedBookIds',
            'activeBorrowsCount',
            'maxBooks',
            'isSuspended',
            'isLimitReached'
        ));
    }

    // Show the form for creating a new book.
    public function create()
    {
        return view('books.create');
    }

    // Store a newly created book in storage.
    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $validated['available_copies'] = $validated['total_copies'];

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    // Display details of the specified book.
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    // Show the form for editing the specified book.
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    // Update the specified book in storage.
    public function update(UpdateBookRequest $request, Book $book)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // Recalculate available copies based on currently active (unreturned) loans.
        $activeLoansCount = $book->borrowRecords()->whereNull('returned_date')->count();
        $validated['available_copies'] = max(0, $validated['total_copies'] - $activeLoansCount);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    // Remove the specified book from storage.
    public function destroy(Book $book)
    {
        $activeBorrows = $book->borrowRecords()->whereNull('returned_date')->count();
        if ($activeBorrows > 0) {
            return back()->with('error', 'Cannot delete book: This book is currently borrowed by members.');
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
