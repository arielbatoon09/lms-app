<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\IssuedBooks;

class IssuedBooksController extends Controller
{
    // To Get List of user Issued Books
    public function getAllIssuedBookDetails()
    {
        try {
            $issuedBooks = IssuedBooks::with(['book', 'user'])
                ->get()
                ->map(function ($book) {
                    $toReturn = \Carbon\Carbon::parse($book->to_return);
                    $now = \Carbon\Carbon::now();
                    $isOverdue = false;

                    if ($book->is_return === 0) {
                        $isOverdue = $now->greaterThan($toReturn);
                        $book->is_return = $isOverdue ? 2 : 0;
                    } else if ($book->is_return !== 1) {
                        $isOverdue = $now->greaterThan($toReturn);
                    }

                    return [
                        'id' => $book->id,
                        'book_id' => $book->book_id,
                        'user_name' => $book->user->name,
                        'book_name' => $book->book->book_name,
                        'description' => $book->book->description,
                        'category' => $book->book->category->category_name ?? 'No category available',
                        'category_id' => $book->book->category_id,
                        'author' => $book->book->author->author_name ?? 'No author available',
                        'author_id' => $book->book->author_id,
                        'book_fees' => $book->book->book_fees,
                        'quantity' => $book->book->quantity,
                        'is_return' => $book->is_return,
                        'book_img' => $book->book->book_img,
                        'to_return' => $book->to_return,
                        'is_overdue' => $isOverdue,
                    ];
                });

            return Inertia::render('Admin/Issued-books', [
                'books' => $issuedBooks,
            ]);
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    // To Update Book issued return status
    public function updateBookIssued(Request $request)
    {
        try{
            Validator::make($request->all(), [
                'id' => ['required'],
                'is_return' => ['required'],
            ])->validate();

            $bookIssued = IssuedBooks::find($request->id);

            if($bookIssued){
                $bookIssued->is_return = $request->is_return;
                $bookIssued->update();

                return redirect()->back()
                    ->with('message', 'Updated book return status.');
            }else{
                return redirect()->back()
                    ->with('message', 'Failed to update book return status.');
            }

        }catch(\Exception $error){
            return $error->getMessage();
        }
    }

    // To Delete Book Issued return status
    public function deleteBookIssued(Request $request)
    {
        try{
            Validator::make($request->all(), [
                'id' => ['required'],
            ])->validate();
    
            $bookIssued = IssuedBooks::find($request->id);
            if ($bookIssued) {
                $bookIssued->delete();
                return redirect()->back()
                    ->with('message', 'Deleted book successfully.');
            } else {
                return redirect()->back()
                    ->with('message', 'Failed book request delete.');
            }

        }catch(\Exception $error){
            return $error->getMessage();
        }
    }
}
