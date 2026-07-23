<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'membership_status')) {
                $table->string('membership_status')->default('active')->after('role');
            }

            if (! Schema::hasColumn('users', 'membership_number')) {
                $table->string('membership_number')->unique()->nullable()->after('membership_status');
            }

            if (! Schema::hasColumn('users', 'max_books')) {
                $table->integer('max_books')->default(3)->after('membership_number');
            }

            if (! Schema::hasColumn('users', 'joined_date')) {
                $table->date('joined_date')->nullable()->after('max_books');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['membership_status', 'membership_number', 'max_books', 'joined_date']);
        });
    }
};
