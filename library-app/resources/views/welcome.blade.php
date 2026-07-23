@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <p class="text-primary fw-semibold mb-2">Library operations, simplified</p>
                        <h1 class="display-6 fw-bold mb-3">Keep your library organized with a simple, focused catalog experience.</h1>
                        <p class="lead text-muted mb-4">
                            Track books, authors, categories, members, and circulation activity in a streamlined workflow designed for everyday library staff.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg">Browse books</a>
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg">Register</a>
                            @else
                                <a href="{{ route('books.index') }}" class="btn btn-outline-primary btn-lg">Browse books</a>
                            @endguest
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-4 h-100">
                            <h5 class="fw-semibold mb-3">What you can do</h5>
                            <ul class="list-unstyled mb-0 text-muted">
                                <li class="mb-2">• Manage catalog records</li>
                                <li class="mb-2">• Issue and return books</li>
                                <li class="mb-2">• Track active and overdue loans</li>
                                <li>• Keep member details up to date</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Catalog books</h5>
                        <p class="card-text text-muted">Keep track of inventory, ISBNs, availability, and publication years in one place.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Organize authors</h5>
                        <p class="card-text text-muted">Maintain a clean list of authors and link them directly to the books they wrote.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Group by category</h5>
                        <p class="card-text text-muted">Filter your collection quickly by genre or category to find what you need faster.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
