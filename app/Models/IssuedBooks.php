<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssuedBooks extends Model
{
    use HasFactory;

    protected $table = 'tbl_issued_books';

    protected $fillable = [
        'id',
        'book_id',
        'user_id',
        'to_return',
        'is_return',
    ];
}
