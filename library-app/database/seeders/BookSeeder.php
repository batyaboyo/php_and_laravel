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
            [
                'title'            => 'Pride and Prejudice',
                'author'           => 'Jane Austen',
                'isbn'             => '9780141439518',
                'category'         => 'Romance',
                'total_copies'     => 4,
                'available_copies' => 4,
            ],
            [
                'title'            => 'The Catcher in the Rye',
                'author'           => 'J.D. Salinger',
                'isbn'             => '9780316769488',
                'category'         => 'Fiction',
                'total_copies'     => 3,
                'available_copies' => 3,
            ],
            [
                'title'            => 'The Hobbit',
                'author'           => 'J.R.R. Tolkien',
                'isbn'             => '9780547928227',
                'category'         => 'Fantasy',
                'total_copies'     => 6,
                'available_copies' => 6,
            ],
            [
                'title'            => 'Fahrenheit 451',
                'author'           => 'Ray Bradbury',
                'isbn'             => '9781451673319',
                'category'         => 'Dystopian',
                'total_copies'     => 3,
                'available_copies' => 3,
            ],
            [
                'title'            => 'The Pragmatic Programmer',
                'author'           => 'Andrew Hunt, David Thomas',
                'isbn'             => '9780135957059',
                'category'         => 'Technology',
                'total_copies'     => 4,
                'available_copies' => 4,
            ],
            [
                'title'            => 'Brave New World',
                'author'           => 'Aldous Huxley',
                'isbn'             => '9780060850524',
                'category'         => 'Dystopian',
                'total_copies'     => 3,
                'available_copies' => 3,
            ],
            [
                'title'            => 'Design Patterns',
                'author'           => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
                'isbn'             => '9780201633610',
                'category'         => 'Technology',
                'total_copies'     => 2,
                'available_copies' => 2,
            ],
            [
                'title'            => 'Moby-Dick',
                'author'           => 'Herman Melville',
                'isbn'             => '9781503280786',
                'category'         => 'Adventure',
                'total_copies'     => 2,
                'available_copies' => 2,
            ],
            [
                'title'            => 'War and Peace',
                'author'           => 'Leo Tolstoy',
                'isbn'             => '9781400079988',
                'category'         => 'Historical Fiction',
                'total_copies'     => 2,
                'available_copies' => 2,
            ],
            [
                'title'            => 'Crime and Punishment',
                'author'           => 'Fyodor Dostoevsky',
                'isbn'             => '9780486415871',
                'category'         => 'Classics',
                'total_copies'     => 3,
                'available_copies' => 3,
            ],
            [
                'title'            => 'The Alchemist',
                'author'           => 'Paulo Coelho',
                'isbn'             => '9780062315007',
                'category'         => 'Philosophy',
                'total_copies'     => 5,
                'available_copies' => 5,
            ],
            [
                'title'            => 'Introduction to Algorithms',
                'author'           => 'Thomas H. Cormen',
                'isbn'             => '9780262033848',
                'category'         => 'Computer Science',
                'total_copies'     => 4,
                'available_copies' => 4,
            ],
            [
                'title'            => 'Sapiens: A Brief History of Humankind',
                'author'           => 'Yuval Noah Harari',
                'isbn'             => '9780062316097',
                'category'         => 'Non-Fiction',
                'total_copies'     => 5,
                'available_copies' => 5,
            ],
            [
                'title'            => 'Atomic Habits',
                'author'           => 'James Clear',
                'isbn'             => '9780735211292',
                'category'         => 'Self-Help',
                'total_copies'     => 6,
                'available_copies' => 6,
            ],
            [
                'title'            => 'Dune',
                'author'           => 'Frank Herbert',
                'isbn'             => '9780441172719',
                'category'         => 'Science Fiction',
                'total_copies'     => 4,
                'available_copies' => 4,
            ],
            [
                'title'            => 'Refactoring: Improving the Design of Existing Code',
                'author'           => 'Martin Fowler',
                'isbn'             => '9780134757599',
                'category'         => 'Technology',
                'total_copies'     => 3,
                'available_copies' => 3,
            ],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(['isbn' => $book['isbn']], $book);
        }
    }
}
