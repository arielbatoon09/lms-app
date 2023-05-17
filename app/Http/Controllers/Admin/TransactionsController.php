<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Invoices;

class TransactionsController extends Controller
{
    // To Get List of user Transactions
    public function getAllTransactions()
    {
        try {
            $invoices = Invoices::with(['book', 'user'])
                ->get()
                ->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'book_id' => $invoice->book_id,
                        'user_name' => $invoice->user->name,
                        'book_name' => $invoice->book->book_name,
                        'book_fees' => $invoice->book_fees,
                        'action' => $invoice->action,
                    ];
                });

            return Inertia::render('Admin/Transactions', [
                'transactions' => $invoices,
            ]);
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    // Update the Status of Transactions
    public function updateTransactions(Request $request)
    {
        try {
            $invoice = Invoices::where('id', $request->id)->first();
            if ($invoice) {
                $invoice->action = $request->action;
                $invoice->save();

                return redirect()->back()
                ->with('message', 'Updated transaction status.');
            }else{
                return redirect()->back()
                ->with('message', 'Not Found Transaction.');
            }

        } catch(\Exception $error) {
            return $error->getMessage();
        }
    }
}
