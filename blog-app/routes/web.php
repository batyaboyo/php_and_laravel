<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Models\Post;

Route::get('/', function () {
    $posts = Post::all();
    return view('home', ['posts' => $posts]);
});

Route::post('/register', [UserController::class,'register']);
Route::post('/logout', [UserController::class,'logout']);
Route::post('/login', [UserController::class,'login']);

// Blog post related routes

Route::post('/create-post', [PostController::class,'createPost']);
