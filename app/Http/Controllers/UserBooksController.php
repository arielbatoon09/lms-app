<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Books;
use App\Models\BookRequest;

class UserBooksController extends Controller
{
    // To Get Given Data and Render to Book Page
    public function index()
    {
        try {
            return Inertia::render('Browse-books', [
                'books' => Books::with(['category', 'author'])->get()->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'book_name' => $book->book_name,
                        'description' => $book->description,
                        'category' => $book->category->category_name ?? 'No category available',
                        'category_id' => $book->category_id,
                        'author' => $book->author->author_name ?? 'No author available',
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

    // To Get Specific Book Details
    public function getBookDetails(Request $request, $id)
    {
        try {
            $bookDetails = Books::leftJoin('tbl_author', 'tbl_books.author_id', '=', 'tbl_author.id')
                ->leftJoin('tbl_category', 'tbl_books.category_id', '=', 'tbl_category.id')
                ->select(
                    'tbl_books.id',
                    'tbl_books.book_name',
                    'tbl_books.description',
                    DB::raw("IFNULL(tbl_category.category_name, 'No category available') as category_name"),
                    DB::raw("IFNULL(tbl_author.author_name, 'No author available') as author_name"),
                    'tbl_books.book_fees',
                    'tbl_books.quantity',
                    'tbl_books.is_active',
                    'tbl_books.book_img'
                )
                ->where('tbl_books.id', $id)
                ->first();

            $bookRequest = BookRequest::where('user_id', $request->user_id)
                ->where('book_id', $id)
                ->first();

            if ($bookDetails) {
                return Inertia::render('Book-details', [
                    'book' => $bookDetails,
                    'is_requested' => $bookRequest,
                ]);
            } else {
                return redirect()->back()
                    ->with('message', '400');
            }
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    // To Acquire Data and Render to Book Request Page
    public function BookRequestPage()
    {
        try {
            $user_id = auth()->user()->id;

            return Inertia::render('Book-request', [
                'books' => BookRequest::with('book.category', 'book.author')
                    ->where('user_id', $user_id)
                    ->get()
                    ->map(function ($book) {
                        return [
                            'id' => $book->id,
                            'user_id' => $book->user_id,
                            'book_id' => $book->book_id,
                            'to_return' => $book->to_return,
                            'status' => $book->status,
                            'book_img' => $book->book->book_img,
                            'book_name' => $book->book->book_name,
                            'category_name' => $book->book->category->category_name ?? 'No category available',
                            'author_name' => $book->book->author->author_name ?? 'No author available',
                        ];
                    })
            ]);
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    // To Borrow A Book
    public function doRequestBook(Request $request)
    {
        try {
            $to_return = \Carbon\Carbon::parse($request->to_return)->format('Y-m-d');
            $current_time = \Carbon\Carbon::now()->format('H:i:s');
            $to_return = "{$to_return} {$current_time}";
            $request->merge(['to_return' => $to_return]);

            Validator::make($request->all(), [
                'user_id' => ['required'],
                'book_id' => ['required'],
                'to_return' => ['required'],
            ])->validate();

            // 1. Find the Book model by ID
            $book = Books::findOrFail($request->book_id);

            // 2. Check the status of the book
            if ($book->is_active == 1) {

                // 3. Find the BookRequest by user_id and book_id
                $bookRequest = BookRequest::where('user_id', $request->user_id)
                    ->where('book_id', $request->book_id)
                    ->first();

                // 4. Check if the BookRequest already exists
                if (!$bookRequest) {
                    BookRequest::create($request->all());
                    return redirect()->back()
                        ->with('message', '202');
                } else {
                    return redirect()->back()
                        ->with('message', '400');
                }
            } else {
                return redirect()->back()
                    ->with('message', '400');
            }
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
}
