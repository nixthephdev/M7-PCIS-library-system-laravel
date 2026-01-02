<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    use HasFactory;
    protected $guarded = []; // <--- Make sure this is here

    public function book() {
        return $this->belongsTo(Book::class);
    }
}