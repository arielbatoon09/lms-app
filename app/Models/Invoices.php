<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Books;

class Invoices extends Model
{
    use HasFactory;

    protected $table = 'tbl_invoices';

    protected $fillable = [
        'id',
        'user_id',
        'book_id',
        'book_fees',
        'action',
    ];

    public function book()
    {
        return $this->belongsTo(Books::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
