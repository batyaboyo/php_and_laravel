<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowRecordController extends Controller
{
    /**
     * Borrow a book.
     * POST /books/{book}/borrow
     */
    public function store(Request $request, Book $book)
    {
        $user = Auth::user();

        // 1. available_copies check
        if ($book->available_copies < 1) {
            return back()->with('error', 'No copies are available for this book.');
        }

        // 2. Duplicate borrow check
        $alreadyBorrowed = BorrowRecord::where('book_id', $book->id)
            ->where('user_id', $user->id)
            ->whereNull('returned_date')
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'You already have this book borrowed.');
        }

        // 3. Membership status check
        if ($user->membership_status === 'suspended') {
            return back()->with('error', 'Your membership is suspended. Contact the library.');
        }

        // 4. Borrow count check (max_books limit)
        $activeBorrowsCount = $user->borrowRecords()
            ->whereNull('returned_date')
            ->count();

        $maxBooks = $user->max_books ?? 3;

        if ($activeBorrowsCount >= $maxBooks) {
            return back()->with('error', "You have reached your borrowing limit of {$maxBooks} books.");
        }

        // Create borrow record
        BorrowRecord::create([
            'book_id'       => $book->id,
            'user_id'       => $user->id,
            'borrowed_date' => now()->toDateString(),
            'due_date'      => now()->addDays(14)->toDateString(),
            'fine'          => 0,
        ]);

        $book->decrement('available_copies');

        return back()->with('success', 'Book borrowed successfully. Return it within 14 days.');
    }

    /**
     * Return a borrowed book.
     * POST /borrow-records/{record}/return
     */
    public function returnBook(BorrowRecord $record)
    {
        // Security: only the borrower may return their own record
        if ($record->user_id !== Auth::id()) {
            abort(403, 'You are not authorised to return this borrow record.');
        }

        if ($record->returned_date) {
            return back()->with('error', 'This book has already been returned.');
        }

        $dueDate  = \Carbon\Carbon::parse($record->due_date)->startOfDay();
        $today    = now()->startOfDay();
        $daysLate = 0;

        if ($today->greaterThan($dueDate)) {
            $daysLate = (int) $dueDate->diffInDays($today);
        }

        $fine = $daysLate * 500; // 500 per day late

        $record->update([
            'returned_date' => now()->toDateString(),
            'fine'          => $fine,
        ]);

        $record->book->increment('available_copies');

        $message = $daysLate > 0
            ? "Book returned successfully. Late fine: \${$fine}"
            : 'Book returned successfully.';

        return back()->with('success', $message);
    }

    /**
     * Show the current user's active borrows.
     * GET /my-books
     */
    public function myBooks()
    {
        $records = auth()->user()
            ->borrowRecords()
            ->with('book')
            ->whereNull('returned_date')
            ->latest('borrowed_date')
            ->get();

        return view('records.my-books', compact('records'));
    }
}
