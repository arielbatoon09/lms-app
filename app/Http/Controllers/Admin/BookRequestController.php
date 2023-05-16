<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Books;
use App\Models\BookRequest;
use App\Models\IssuedBooks;
use App\Models\Invoices;

class BookRequestController extends Controller
{
    // To Acquire Data and Render to Admin Book Request Page
    public function index()
    {
        try {
            return Inertia::render('Admin/Book-request', [
                'books' => BookRequest::with(['book.category', 'book.author', 'user'])
                    ->get()
                    ->map(function ($book) {
                        return [
                            'id' => $book->id,
                            'user_id' => $book->user_id,
                            'user_name' => $book->user->name,
                            'book_id' => $book->book_id,
                            'to_return' => $book->to_return,
                            'status' => $book->status,
                            'book_img' => $book->book->book_img,
                            'book_name' => $book->book->book_name,
                            'category_name' => $book->book->category->category_name ?? 'No category available',
                            'author_name' => $book->book->author->author_name ?? 'No author available',
                            'book_fees' => $book->book->book_fees,
                        ];
                    })
            ]);
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    // Update the BookRequest and add it to the IssuedBooks
    public function updateBookRequest(Request $request)
    {
        try {
            Validator::make($request->all(), [
                'id' => ['required'],
                'status' => ['required'],
            ])->validate();

            $bookRequest = BookRequest::find($request->id);

            if ($bookRequest) {
                if ($request->status == 1) {
                    $bookRequest->status = $request->status;
                    $bookRequest->update();

                    // Check if the book is already issued
                    $issuedBook = IssuedBooks::where('book_id', $bookRequest->book_id)
                        ->where('user_id', $bookRequest->user_id)
                        // ->where('is_return', 0)
                        ->first();

                    if (!$issuedBook) {
                        // Insert the data into the IssuedBook model
                        $issuedBook = new IssuedBooks();
                        $issuedBook->book_id = $bookRequest->book_id;
                        $issuedBook->user_id = $bookRequest->user_id;
                        $issuedBook->to_return = $bookRequest->to_return;
                        $issuedBook->is_return = 0; // Default value
                        $issuedBook->save();

                        // Insert Invoice into the Invoices model
                        $insertInvoice = new Invoices();
                        $insertInvoice->user_id = $bookRequest->user_id;
                        $insertInvoice->book_id = $bookRequest->book_id;
                        $insertInvoice->book_fees = $bookRequest->book->book_fees;

                        if($bookRequest->book->book_fees > 0){
                            $insertInvoice->action = 0;
                            $insertInvoice->save();
                        }else{
                            $insertInvoice->action = 1;
                            $insertInvoice->save();
                        }
                        
                        // Deduct 1 from the quantity column in the Books model if the status is not equal to 2
                        if ($request->status != 2) {
                            $book = Books::find($bookRequest->book_id);
                            if ($book) {
                                $book->quantity = $book->quantity - 1;
                                if ($book->quantity == 0) {
                                    $book->is_active = 0;
                                }
                                $book->update();
                            }
                        }

                        return redirect()->back()
                            ->with('message', 'Book request has been approved and book has been issued.');
                    } else {
                        $issuedBook = new IssuedBooks();
                        $issuedBook->book_id = $bookRequest->book_id;
                        $issuedBook->user_id = $bookRequest->user_id;
                        $issuedBook->to_return = $bookRequest->to_return;
                        $issuedBook->is_return = 0; // Default value
                        $issuedBook->save();
                        
                        // Insert Invoice into the Invoices model
                        $insertInvoice = new Invoices();
                        $insertInvoice->user_id = $bookRequest->user_id;
                        $insertInvoice->book_id = $bookRequest->book_id;
                        $insertInvoice->book_fees = $bookRequest->book->book_fees;

                        if($bookRequest->book->book_fees > 0){
                            $insertInvoice->action = 0;
                            $insertInvoice->save();
                        }else{
                            $insertInvoice->action = 1;
                            $insertInvoice->save();
                        }

                        // Deduct 1 from the quantity column in the Books model if the status is not equal to 2
                        if ($request->status != 2) {
                            $book = Books::find($bookRequest->book_id);
                            if ($book) {
                                $book->quantity = $book->quantity - 1;
                                if ($book->quantity == 0) {
                                    $book->is_active = 0;
                                }
                                $book->update();
                            }
                        }

                        return redirect()->back()
                            ->with('message', 'Updated book request successfully.');
                    }
                } else {
                    // Check if the book is already issued
                    $issuedBook = IssuedBooks::where('book_id', $bookRequest->book_id)
                        ->where('user_id', $bookRequest->user_id)
                        ->first();

                    if ($issuedBook) {
                        // Add 1 to the quantity column in the Books model
                        $book = Books::find($bookRequest->book_id);
                        if ($book) {
                            if ($book->quantity > 0) {
                                $book->is_active = 1;
                            }
                            $book->update();
                        }
                    }

                    // To update Book Request Status
                    $bookRequest->status = $request->status;
                    $bookRequest->update();

                    // Deduct 1 from the quantity column in the Books model if the status is not equal to 2
                    if ($request->status == 1) {
                        $book = Books::find($bookRequest->book_id);
                        if ($book) {
                            $book->quantity = $book->quantity - 1;
                            if ($book->quantity == 0) {
                                $book->is_active = 0;
                            }
                            $book->update();
                        }
                    }

                    return redirect()->back()
                        ->with('message', 'Updated book request successfully.');
                }
            } else {
                return redirect()->back()
                    ->with('message', 'Failed book request update.');
            }
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
    // To Delete Book Request
    public function deleteBookRequest(Request $request)
    {
        try {
            Validator::make($request->all(), [
                'id' => ['required'],
            ])->validate();

            $bookRequest = BookRequest::find($request->id);
            if ($bookRequest) {
                $bookRequest->delete();
                return redirect()->back()
                    ->with('message', 'Deleted book successfully.');
            } else {
                return redirect()->back()
                    ->with('message', 'Failed book request delete.');
            }
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
}
