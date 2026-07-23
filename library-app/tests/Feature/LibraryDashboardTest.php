<?php

use App\Models\User;

it('redirects guests away from book management actions', function () {
    $response = $this->get('/books/create');

    $response->assertRedirect('/login');
});

it('keeps the books catalog available to authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/books');

    $response->assertOk();
    $response->assertSee('Library Books');
});
