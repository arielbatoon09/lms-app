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
        'book_name',
        'description',
        'category_id',
        'author_id',
        'book_fees',
        'quantity',
        'is_active',
        'book_img',
    ];

    public function getCategory($data) 
    {
        $category = Category::find($data);
        if($category){
            return $category->category_name;
        }
    }
    public function getAuthor($data)
    {
        $author = Author::find($data);
        if($author){
            return $author->author_name;
        }
    }
}
