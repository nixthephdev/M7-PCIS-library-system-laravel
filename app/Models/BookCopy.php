<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- Import this

class BookCopy extends Model
{
    use HasFactory, SoftDeletes; // <--- Add this

    protected $guarded = [];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function borrowTransactions()
    {
        return $this->hasMany(BorrowTransaction::class);
    }
}