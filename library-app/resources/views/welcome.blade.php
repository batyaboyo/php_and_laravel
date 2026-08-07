@extends('layouts.app')

@section('content')
    <div class="py-4">
        <!-- Hero Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 mb-3">
                            Library Management System
                        </span>
                        <h1 class="display-6 fw-bold mb-3 text-dark">
                            Simplified Book Catalog & Circulation System
                        </h1>
                        <p class="lead text-muted mb-4">
                            Easily browse library books, manage book loans and returns, track member accounts, and handle automated late fine calculations in one focused platform.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            @guest
                                <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg px-4 fw-semibold">
                                    Browse Books
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    Log In
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4 fw-semibold text-dark">
                                    Register
                                </a>
                            @else
                                <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg px-4 fw-semibold">
                                    View Books Catalog
                                </a>
                                <a href="{{ route('my-books') }}" class="btn btn-outline-primary btn-lg px-4">
                                    My Borrowed Books
                                </a>
                            @endguest
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-4 h-100 border border-primary border-opacity-10">
                            <h5 class="fw-bold mb-3 text-dark">System Overview</h5>
                            <ul class="list-unstyled mb-0 text-muted small space-y-2">
                                <li class="mb-2"><strong>• Book Catalog:</strong> Browse, search, filter, and manage books.</li>
                                <li class="mb-2"><strong>• Circulation:</strong> Borrow and return books with 14-day loan terms.</li>
                                <li class="mb-2"><strong>• Late Fines:</strong> Automatic fine calculation (UGX 500/day late).</li>
                                <li><strong>• Membership:</strong> Track member status, membership numbers, and limits.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-dark mb-2">Book Management</h5>
                        <p class="card-text text-muted small">
                            Search and filter books by title or category. View copy availability, ISBNs, and cover details.
                        </p>
                        @auth
                            <a href="{{ route('books.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 fw-semibold">
                                Open Catalog &rarr;
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-dark mb-2">Borrowing & Returns</h5>
                        <p class="card-text text-muted small">
                            Borrow available books instantly and track active loans. Returns automatically recalculate inventory and fines.
                        </p>
                        @auth
                            <a href="{{ route('my-books') }}" class="btn btn-sm btn-link text-decoration-none p-0 fw-semibold">
                                My Loans &rarr;
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-dark mb-2">Membership Controls</h5>
                        <p class="card-text text-muted small">
                            Track unique membership numbers (e.g. LIB-0001), active/suspended statuses, and borrowing limits.
                        </p>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('members.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 fw-semibold">
                                Manage Members &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
