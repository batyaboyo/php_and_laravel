<?php

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;

it('allows any authenticated user to create, edit, and delete books', function () {
    $member = User::factory()->create(['role' => 'member']);

    // Create book
    $response = $this->actingAs($member)->post('/books', [
        'title'        => 'New Book by Member',
        'author'       => 'Jane Doe',
        'isbn'         => '978-9999999999',
        'category'     => 'Tech',
        'total_copies' => 3,
    ]);

    $response->assertRedirect('/books');
    $this->assertDatabaseHas('books', [
        'title'            => 'New Book by Member',
        'isbn'             => '978-9999999999',
        'total_copies'     => 3,
        'available_copies' => 3,
    ]);

    $book = Book::where('isbn', '978-9999999999')->first();

    // Access edit page
    $this->actingAs($member)->get("/books/{$book->id}/edit")->assertOk();

    // Update book
    $updateResponse = $this->actingAs($member)->put("/books/{$book->id}", [
        'title'        => 'Updated Title by Member',
        'author'       => 'Jane Doe',
        'category'     => 'Tech',
        'total_copies' => 5,
    ]);
    $updateResponse->assertRedirect('/books');
    expect($book->fresh()->title)->toBe('Updated Title by Member');

    // Delete book
    $deleteResponse = $this->actingAs($member)->delete("/books/{$book->id}");
    $deleteResponse->assertRedirect('/books');
    $this->assertDatabaseMissing('books', ['id' => $book->id]);
});

it('blocks unauthenticated guests from book CRUD actions', function () {
    $this->get('/books/create')->assertRedirect('/login');
    $this->post('/books', ['title' => 'Test'])->assertRedirect('/login');
});

it('blocks borrowing when no copies are available', function () {
    $user = User::factory()->create(['role' => 'member']);
    $book = Book::create([
        'title'            => 'Unavailable Book',
        'author'           => 'Author',
        'isbn'             => '978-2222222222',
        'category'         => 'Fiction',
        'total_copies'     => 1,
        'available_copies' => 0,
    ]);

    $response = $this->actingAs($user)->post("/books/{$book->id}/borrow");

    $response->assertRedirect();
    $response->assertSessionHas('error', 'No copies are currently available for this book.');
});

it('allows borrowing when copies are available', function () {
    $user = User::factory()->create(['role' => 'member']);
    $book = Book::create([
        'title'            => 'Available Book',
        'author'           => 'Author',
        'isbn'             => '978-5555555555',
        'category'         => 'Fiction',
        'total_copies'     => 2,
        'available_copies' => 2,
    ]);

    $response = $this->actingAs($user)->post("/books/{$book->id}/borrow");

    $response->assertRedirect();
    expect($book->fresh()->available_copies)->toBe(1);
    $this->assertDatabaseHas('borrow_records', [
        'book_id' => $book->id,
        'user_id' => $user->id,
    ]);
});

it('calculates fine at 500 per day when returning a late borrow record', function () {
    $user = User::factory()->create(['role' => 'member']);
    $book = Book::create([
        'title'            => 'Late Book',
        'author'           => 'Author',
        'isbn'             => '978-3333333333',
        'category'         => 'Fiction',
        'total_copies'     => 1,
        'available_copies' => 0,
    ]);

    $record = BorrowRecord::create([
        'book_id'       => $book->id,
        'user_id'       => $user->id,
        'borrowed_date' => now()->subDays(20)->toDateString(),
        'due_date'      => now()->subDays(5)->toDateString(),
        'returned_date' => null,
        'fine'          => 0,
    ]);

    $response = $this->actingAs($user)->post("/borrow-records/{$record->id}/return");

    $response->assertRedirect();
    $record->refresh();
    expect($record->returned_date)->not->toBeNull();
    expect((float) $record->fine)->toBe(2500.00); // 5 days * 500
    expect($book->fresh()->available_copies)->toBe(1);
});

it('prevents a member from returning another member\'s borrow record', function () {
    $owner = User::factory()->create(['role' => 'member']);
    $other = User::factory()->create(['role' => 'member']);
    $book  = Book::create([
        'title'            => 'Protected Book',
        'author'           => 'Author',
        'isbn'             => '978-4444444444',
        'category'         => 'Fiction',
        'total_copies'     => 1,
        'available_copies' => 0,
    ]);
    $record = BorrowRecord::create([
        'book_id'       => $book->id,
        'user_id'       => $owner->id,
        'borrowed_date' => now()->toDateString(),
        'due_date'      => now()->addDays(14)->toDateString(),
    ]);

    $response = $this->actingAs($other)->post("/borrow-records/{$record->id}/return");

    $response->assertForbidden();
});

it('displays the user\'s active borrow records on /my-books', function () {
    $user  = User::factory()->create(['role' => 'member']);
    $book  = Book::create([
        'title'            => 'My Borrowed Book',
        'author'           => 'Author',
        'isbn'             => '978-7777777777',
        'category'         => 'Fiction',
        'total_copies'     => 1,
        'available_copies' => 0,
    ]);
    $record = BorrowRecord::create([
        'book_id'       => $book->id,
        'user_id'       => $user->id,
        'borrowed_date' => now()->toDateString(),
        'due_date'      => now()->addDays(14)->toDateString(),
    ]);

    $response = $this->actingAs($user)->get('/my-books');

    $response->assertOk();
    $response->assertSee('My Borrowed Book');
});
