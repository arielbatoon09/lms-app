<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Inertia\Inertia;
// use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Books;

class BrowseBooksController extends Controller
{
    // Acquire data and Render
    public function index()
    {
        try {
            return Inertia::render('Browse-books', [
                'books' => Books::all()->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'book_name' => $book->book_name,
                        'description' => $book->description,
                        'category' => $book->getCategory($book->category_id),
                        'category_id' => $book->category_id,
                        'author' => $book->getAuthor($book->author_id),
                        'author_id' => $book->author_id,
                        'book_fees' => $book->book_fees,
                        'quantity' => $book->quantity,
                        'is_active' => $book->is_active,
                        'book_img' => $book->book_img,
                    ];
                })
            ]);
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
    public function getProductDetails($id)
    {
        try {
            $bookDetails = Books::join('tbl_author', 'tbl_books.author_id', '=', 'tbl_author.id')
                ->join('tbl_category', 'tbl_books.category_id', '=', 'tbl_category.id')
                ->select('tbl_books.id', 'tbl_books.book_name', 'tbl_books.description', 'tbl_category.category_name', 
                'tbl_author.author_name', 'tbl_books.book_fees', 'tbl_books.quantity', 'tbl_books.is_active', 'tbl_books.book_img')
                ->where('tbl_books.id', $id)
                ->first();

            if ($bookDetails) {
                return Inertia::render('Book-details', [
                    'book' => $bookDetails,
                ]);
                
            } else {
                return 404;
            }       

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
}
