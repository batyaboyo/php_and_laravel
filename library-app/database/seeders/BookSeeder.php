<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title'            => 'The Great Gatsby',
                'author'           => 'F. Scott Fitzgerald',
                'isbn'             => '9780743273565',
                'category'         => 'Fiction',
                'total_copies'     => 5,
                'available_copies' => 5,
            ],
            [
                'title'            => 'To Kill a Mockingbird',
                'author'           => 'Harper Lee',
                'isbn'             => '9780061120084',
                'category'         => 'Classic',
                'total_copies'     => 3,
                'available_copies' => 3,
            ],
            [
                'title'            => '1984',
                'author'           => 'George Orwell',
                'isbn'             => '9780451524935',
                'category'         => 'Dystopian',
                'total_copies'     => 4,
                'available_copies' => 4,
            ],
            [
                'title'            => 'Clean Code',
                'author'           => 'Robert C. Martin',
                'isbn'             => '9780132350884',
                'category'         => 'Technology',
                'total_copies'     => 2,
                'available_copies' => 2,
            ],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(['isbn' => $book['isbn']], $book);
        }
    }
}
