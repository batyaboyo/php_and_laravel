<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@library.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Regular Member User
        User::updateOrCreate(
            ['email' => 'member@library.com'],
            [
                'name'     => 'Regular Member',
                'password' => Hash::make('password'),
                'role'     => 'member',
            ]
        );

        // Seed sample books
        $this->call(BookSeeder::class);
    }
}
