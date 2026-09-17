<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
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

    /**
     * Calculate the accrued fine for this record.
     * If already returned, returns the stored fine value.
     * If still out and overdue, calculates 500 UGX per day late.
     */
    public function accruedFine(): int
    {
        if ($this->returned_date || ! $this->due_date) {
            return (int) $this->fine;
        }

        // due_date is already a Carbon instance via the 'date' cast.
        $dueDate = $this->due_date->startOfDay();
        $today   = now()->startOfDay();

        return $today->greaterThan($dueDate)
            ? (int) $dueDate->diffInDays($today) * 500
            : 0;
    }
}
