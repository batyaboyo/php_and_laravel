<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'membership_status',
        'membership_number',
        'max_books',
        'joined_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'joined_date'       => 'date',
        'max_books'         => 'integer',
    ];

    protected static function booted(): void
    {
        static::created(function (self $user): void {
            if (empty($user->membership_number)) {
                $user->membership_number = 'LIB-' . str_pad((string) $user->id, 4, '0', STR_PAD_LEFT);
            }
            if (empty($user->joined_date)) {
                $user->joined_date = $user->created_at ? $user->created_at->toDateString() : now()->toDateString();
            }
            $user->saveQuietly();
        });
    }

    public function borrowRecords(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BorrowRecord::class);
    }
}
