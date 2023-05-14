<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Category; 
use App\Models\Admin\Author; 

class Books extends Model
{
    use HasFactory;
    protected $table = 'tbl_books';

    protected $fillable = [
        'id',
        'book_name',
        'description',
        'category_id',
        'author_id',
        'book_fees',
        'quantity',
        'is_active',
        'book_img',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
