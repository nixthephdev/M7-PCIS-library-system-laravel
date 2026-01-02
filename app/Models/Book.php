<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $guarded = []; // <--- Make sure this is here
    
    public function copies() {
        return $this->hasMany(BookCopy::class);
    }
}
