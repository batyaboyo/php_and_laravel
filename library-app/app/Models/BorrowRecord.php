<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    protected $table = 'borrow_records';

    protected $fillable = [
        'book_id',
        'user_id',
        'borrowed_date',
        'due_date',
        'returned_date',
        'fine',
    ];

    protected $casts = [
        'borrowed_date' => 'date',
        'due_date'      => 'date',
        'returned_date' => 'date',
        'fine'          => 'decimal:2',
    ];

    public function book(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function accruedFine(): int
    {
        if ($this->returned_date || ! $this->due_date) {
            return (int) $this->fine;
        }

        $dueDate = Carbon::parse($this->due_date)->startOfDay();
        $today = now()->startOfDay();

        return $today->greaterThan($dueDate)
            ? $dueDate->diffInDays($today) * 500
            : 0;
    }
}
