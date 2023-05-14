<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Books;

class Category extends Model
{
    use HasFactory;
    protected $table = 'tbl_category';
    protected $fillable = [
        'id',
        'category_name',
    ];

    public function books()
    {
        return $this->hasMany(Books::class);
    }
}
