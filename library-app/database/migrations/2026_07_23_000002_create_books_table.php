<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('books')) {
            Schema::create('books', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('author')->nullable();
                $table->string('isbn')->nullable();
                $table->string('category')->nullable();
                $table->integer('total_copies')->default(1);
                $table->integer('available_copies')->default(1);
                $table->string('cover_image')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('books', function (Blueprint $table) {
            if (! Schema::hasColumn('books', 'author')) {
                $table->string('author')->nullable()->after('title');
            }

            if (! Schema::hasColumn('books', 'isbn')) {
                $table->string('isbn')->nullable()->after('author');
            }

            if (! Schema::hasColumn('books', 'category')) {
                $table->string('category')->nullable()->after('isbn');
            }

            if (! Schema::hasColumn('books', 'total_copies')) {
                $table->integer('total_copies')->default(1)->after('category');
            }

            if (! Schema::hasColumn('books', 'available_copies')) {
                $table->integer('available_copies')->default(1)->after('total_copies');
            }

            if (! Schema::hasColumn('books', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('available_copies');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
