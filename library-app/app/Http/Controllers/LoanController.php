<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    public function index()
    {
        $this->refreshOverdueLoans();

        $activeLoans = Loan::query()->with(['book', 'member'])
            ->where('status', 'borrowed')
            ->whereNull('returned_date')
            ->orderBy('due_date')
            ->get();

        $overdueLoans = Loan::query()->with(['book', 'member'])
            ->where('status', 'overdue')
            ->whereNull('returned_date')
            ->orderBy('due_date')
            ->get();

        return view('borrow-records.index', compact('activeLoans', 'overdueLoans'));
    }

    public function create()
    {
        $members = Member::query()->where('status', 'active')->orderBy('name')->get();
        $books = Book::query()->where('available_copies', '>', 0)->orderBy('title')->get();

        return view('borrow-records.create', compact('members', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'book_id' => ['required', 'exists:books,id'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $dueDate = $request->filled('due_date')
            ? $request->date('due_date')->toDateString()
            : now()->addDays(14)->toDateString();

        DB::transaction(function () use ($validated, $dueDate): void {
            $member = Member::query()->lockForUpdate()->findOrFail($validated['member_id']);
            $book = Book::query()->lockForUpdate()->findOrFail($validated['book_id']);

            if ($member->status !== 'active') {
                throw ValidationException::withMessages([
                    'member_id' => 'This member is not active.',
                ]);
            }

            if ($book->available_copies < 1) {
                throw ValidationException::withMessages([
                    'book_id' => 'No copies are available for this book.',
                ]);
            }

            $data = [
                'member_id' => $member->id,
                'book_id' => $book->id,
                'due_date' => $dueDate,
                'status' => 'borrowed',
            ];

            if (Schema::hasColumn('borrow_records', 'borrowed_date')) {
                $data['borrowed_date'] = now()->toDateString();
            }

            Loan::create($data);

            $book->decrement('available_copies');
        });

        return redirect('/members')->with('success', 'Book issued successfully.');
    }

    public function returnBook(Loan $loan)
    {
        if ($loan->getAttribute('returned_date')) {
            return back()->withErrors(['record' => 'This book has already been returned.']);
        }

        DB::transaction(function () use ($loan): void {
            $lockedLoan = Loan::query()->lockForUpdate()->findOrFail($loan->getKey());
            $book = Book::query()->lockForUpdate()->findOrFail($lockedLoan->getAttribute('book_id'));

            $lockedLoan->update([
                'returned_date' => now()->toDateString(),
                'status' => 'returned',
            ]);

            $book->increment('available_copies');
        });

        return back()->with('success', 'Book returned successfully.');
    }

    private function refreshOverdueLoans(): void
    {
        Loan::query()->whereNull('returned_date')
            ->whereDate('due_date', '<', today())
            ->where('status', 'borrowed')
            ->update(['status' => 'overdue']);
    }
}