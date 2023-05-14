<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Books;

class BookRequest extends Model
{
    use HasFactory;

    protected $table = 'tbl_book_request';

    protected $fillable = [
        'id',
        'user_id',
        'book_id',
        'to_return',
        'status'
    ];

    public function book()
    {
        return $this->belongsTo(Books::class);
    }
}
