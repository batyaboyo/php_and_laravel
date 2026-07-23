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
        if ($book->available_copies < 1) {
            return back()->with('error', 'No copies are currently available for this book.');
        }

        BorrowRecord::create([
            'book_id'       => $book->id,
            'user_id'       => Auth::id(),
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
        $daysLate = max(0, $dueDate->diffInDays($today, false) * -1);

        // diffInDays with false preserves sign; negative means today > due_date
        // Simpler: use ceiling difference
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
