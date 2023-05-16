<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Models\Invoices;

class UserInvoices extends Controller
{
    // Get the specific user invoice list
    public function index()
    {
        try {
            $user_id = auth()->user()->id;

            return Inertia::render('Invoices', [
                'invoice' => Invoices::with('book')
                    ->where('user_id', $user_id)
                    ->get()
                    ->map(function ($book) {
                        return [
                            'id' => $book->id,
                            'user_id' => $book->user_id,
                            'book_id' => $book->book_id,
                            'action' => $book->action,
                            'book_fees' => $book->book->book_fees,
                            'book_name' => $book->book->book_name,
                        ];
                    })
            ]);
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

}
