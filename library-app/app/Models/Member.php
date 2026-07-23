<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Member extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'membership_date',
        'status',
    ];

    protected $casts = [
        'membership_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Member $member): void {
            if (! Schema::hasColumn($member->getTable(), 'membership_date')) {
                return;
            }

            $member->membership_date ??= now()->toDateString();
        });
    }

    public function borrowRecords()
    {
        return $this->hasMany(Loan::class);
    }

    public function loans()
    {
        return $this->borrowRecords();
    }
}
