<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('borrow_records')) {
            Schema::create('borrow_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('book_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->date('borrowed_date');
                $table->date('due_date');
                $table->date('returned_date')->nullable();
                $table->decimal('fine', 8, 2)->default(0);
                $table->timestamps();
            });

            return;
        }

        Schema::table('borrow_records', function (Blueprint $table) {
            if (! Schema::hasColumn('borrow_records', 'book_id')) {
                $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('borrow_records', 'user_id')) {
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('borrow_records', 'borrowed_date')) {
                $table->date('borrowed_date');
            }

            if (! Schema::hasColumn('borrow_records', 'due_date')) {
                $table->date('due_date');
            }

            if (! Schema::hasColumn('borrow_records', 'returned_date')) {
                $table->date('returned_date')->nullable();
            }

            if (! Schema::hasColumn('borrow_records', 'fine')) {
                $table->decimal('fine', 8, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_records');
    }
};
