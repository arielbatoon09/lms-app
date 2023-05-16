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
        'issued_id',
        'payment_method',
        'action',
    ];

    public function book()
    {
        return $this->belongsTo(Books::class);
    }
}
