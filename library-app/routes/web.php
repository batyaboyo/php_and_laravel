<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowRecordController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public welcome page
Route::get('/', function () {
    return view('welcome');
});

// All book and borrowing actions are enabled for any logged-in user
Route::middleware('auth')->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    Route::post('/books/{book}/borrow', [BorrowRecordController::class, 'store'])->name('books.borrow');
    Route::post('/borrow-records/{record}/return', [BorrowRecordController::class, 'returnBook'])->name('borrow-records.return');
    Route::get('/my-books', [BorrowRecordController::class, 'myBooks'])->name('my-books');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin-only Membership Management routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::post('/members/{member}/suspend', [MemberController::class, 'suspend'])->name('members.suspend');
    Route::post('/members/{member}/activate', [MemberController::class, 'activate'])->name('members.activate');
    Route::post('/members/{member}/make-admin', [MemberController::class, 'makeAdmin'])->name('members.make-admin');
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
});

require __DIR__ . '/auth.php';
 