<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Books;

class Author extends Model
{
    use HasFactory;
    protected $table = 'tbl_author';
    protected $fillable = [
        'author_name',
    ];

    public function books()
    {
        return $this->hasMany(Books::class);
    }
}
