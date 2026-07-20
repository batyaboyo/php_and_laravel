<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author_id',
        'isbn',
        'category_id',
        'published_year',
        'available_copies',
    ];

    public function author(){
        return $this->belongsTo(Author::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
