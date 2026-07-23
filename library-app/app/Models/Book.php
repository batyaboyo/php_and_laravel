<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'category',
        'total_copies',
        'available_copies',
        'cover_image',
    ];

    public function borrowRecords(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BorrowRecord::class);
    }
}
