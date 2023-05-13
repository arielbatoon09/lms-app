<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Admin\Books; 
// use App\Models\Admin\Category; 
// use App\Models\Admin\Author; 
// use App\Models\User; 

class IssuedBooks extends Model
{
    use HasFactory;

    protected $table = 'tbl_issued_books';

    protected $fillable = [
        'book_id',
        'user_id',
        'to_return',
        'is_return',
    ];
}
