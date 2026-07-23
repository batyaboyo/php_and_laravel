<?php

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\User;

it('restricts member management routes to admin users only', function () {
    $member = User::factory()->create(['role' => 'member']);
    $target = User::factory()->create(['role' => 'member']);

    $this->actingAs($member)->get('/members')->assertForbidden();
    $this->actingAs($member)->get("/members/{$target->id}")->assertForbidden();
    $this->actingAs($member)->get("/members/{$target->id}/edit")->assertForbidden();
    $this->actingAs($member)->put("/members/{$target->id}", ['name' => 'Test'])->assertForbidden();
    $this->actingAs($member)->post("/members/{$target->id}/suspend")->assertForbidden();
    $this->actingAs($member)->post("/members/{$target->id}/activate")->assertForbidden();
});

it('allows admin users to view members index and member profile', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $member = User::factory()->create(['role' => 'member', 'name' => 'Jane Member']);

    $response = $this->actingAs($admin)->get('/members');
    $response->assertOk();
    $response->assertSee('Jane Member');

    $showResponse = $this->actingAs($admin)->get("/members/{$member->id}");
    $showResponse->assertOk();
    $showResponse->assertSee('Jane Member');
});

it('allows admin to edit and update member details', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $member = User::factory()->create(['role' => 'member', 'name' => 'Old Name', 'email' => 'old@example.com', 'max_books' => 3]);

    $response = $this->actingAs($admin)->put("/members/{$member->id}", [
        'name'      => 'New Name',
        'email'     => 'new@example.com',
        'max_books' => 5,
    ]);

    $response->assertRedirect('/members');
    expect($member->fresh()->name)->toBe('New Name');
    expect($member->fresh()->email)->toBe('new@example.com');
    expect($member->fresh()->max_books)->toBe(5);
});

it('allows admin to suspend and activate a member', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $member = User::factory()->create(['role' => 'member', 'membership_status' => 'active']);

    // Suspend
    $suspendResponse = $this->actingAs($admin)->post("/members/{$member->id}/suspend");
    $suspendResponse->assertRedirect();
    expect($member->fresh()->membership_status)->toBe('suspended');

    // Activate
    $activateResponse = $this->actingAs($admin)->post("/members/{$member->id}/activate");
    $activateResponse->assertRedirect();
    expect($member->fresh()->membership_status)->toBe('active');
});

it('blocks suspending a member if they have unreturned books with unpaid fines', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $member = User::factory()->create(['role' => 'member', 'membership_status' => 'active']);
    $book = Book::create([
        'title'            => 'Sample Book',
        'author'           => 'Sample Author',
        'isbn'             => '978-0000000001',
        'category'         => 'Fiction',
        'total_copies'     => 1,
        'available_copies' => 0,
    ]);

    BorrowRecord::create([
        'book_id'       => $book->id,
        'user_id'       => $member->id,
        'borrowed_date' => now()->subDays(20)->toDateString(),
        'due_date'      => now()->subDays(5)->toDateString(),
        'returned_date' => null,
        'fine'          => 2500, // Unpaid fine > 0 on unreturned book
    ]);

    $response = $this->actingAs($admin)->post("/members/{$member->id}/suspend");

    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect($member->fresh()->membership_status)->toBe('active');
});

it('rejects borrowing if the user is suspended', function () {
    $suspendedUser = User::factory()->create(['role' => 'member', 'membership_status' => 'suspended']);
    $book = Book::create([
        'title'            => 'Borrowable Book',
        'author'           => 'Author',
        'isbn'             => '978-0000000002',
        'category'         => 'Fiction',
        'total_copies'     => 2,
        'available_copies' => 2,
    ]);

    $response = $this->actingAs($suspendedUser)->post("/books/{$book->id}/borrow");

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Your membership is suspended. Contact the library.');
    expect($book->fresh()->available_copies)->toBe(2);
});

it('rejects borrowing if the user has reached their max_books limit', function () {
    $user = User::factory()->create(['role' => 'member', 'membership_status' => 'active', 'max_books' => 2]);
    $book1 = Book::create(['title' => 'B1', 'author' => 'A1', 'isbn' => '978-0000000003', 'total_copies' => 1, 'available_copies' => 0]);
    $book2 = Book::create(['title' => 'B2', 'author' => 'A2', 'isbn' => '978-0000000004', 'total_copies' => 1, 'available_copies' => 0]);
    $book3 = Book::create(['title' => 'B3', 'author' => 'A3', 'isbn' => '978-0000000005', 'total_copies' => 1, 'available_copies' => 1]);

    // Already borrowed 2 active books
    BorrowRecord::create(['book_id' => $book1->id, 'user_id' => $user->id, 'borrowed_date' => now(), 'due_date' => now()->addDays(14)]);
    BorrowRecord::create(['book_id' => $book2->id, 'user_id' => $user->id, 'borrowed_date' => now(), 'due_date' => now()->addDays(14)]);

    // Attempting 3rd borrow should fail
    $response = $this->actingAs($user)->post("/books/{$book3->id}/borrow");

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You have reached your borrowing limit of 2 books.');
    expect($book3->fresh()->available_copies)->toBe(1);
});

it('rejects duplicate borrowing of the same book by the same member', function () {
    $user = User::factory()->create(['role' => 'member', 'membership_status' => 'active', 'max_books' => 3]);
    $book = Book::create([
        'title'            => 'Introduction to Algorithms',
        'author'           => 'CLRS',
        'isbn'             => '978-0262033848',
        'category'         => 'Computer Science',
        'total_copies'     => 5,
        'available_copies' => 5,
    ]);

    // First borrow
    $this->actingAs($user)->post("/books/{$book->id}/borrow")->assertRedirect();
    expect($book->fresh()->available_copies)->toBe(4);

    // Duplicate borrow attempt
    $duplicateResponse = $this->actingAs($user)->post("/books/{$book->id}/borrow");
    $duplicateResponse->assertRedirect();
    $duplicateResponse->assertSessionHas('error', 'You already have this book borrowed.');

    // Copies should still be 4
    expect($book->fresh()->available_copies)->toBe(4);
});
