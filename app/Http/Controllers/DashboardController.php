<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Invoices;
use App\Models\Admin\Books;
use App\Models\BookRequest;
use App\Models\IssuedBooks;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user_id = auth()->user()->id;

            $invoiceList = Invoices::where('user_id', $user_id)->where('action', 1)->get();
            // Books Count
            $allBooksCount = Books::count();
            $activeBookCount = Books::where('is_active', 1)->count();
            $inactiveBookCount = Books::where('is_active', 0)->count();
            // Book Request Count
            $pendingBookRequestCount = BookRequest::where('user_id', $user_id)->where('status', 2)->count();
            $approvedBookRequestCount = BookRequest::where('user_id', $user_id)->where('status', 1)->count();
            $rejectedBookRequestCount = BookRequest::where('user_id', $user_id)->where('status', 0)->count();
            // Invoices Count
            $unpaidInvoiceCount = Invoices::where('user_id', $user_id)->where('action', 0)->count();
            $paidInvoiceCount = Invoices::where('user_id', $user_id)->where('action', 1)->count();
            $otcInvoiceCount = Invoices::where('user_id', $user_id)->where('action', 2)->count();
            // Issued Books
            $returnedBooks = IssuedBooks::where('user_id', $user_id)->where('is_return', 1)->count();
            $issuedBooks = IssuedBooks::where('user_id', $user_id)
                ->get()
                ->map(function ($issued) {
                    $toReturn = \Carbon\Carbon::parse($issued->to_return);
                    $now = \Carbon\Carbon::now();
                    $isOverdue = false;

                    if ($issued->is_return === 0) {
                        $isOverdue = $now->greaterThan($toReturn);
                        $issued->is_return = $isOverdue ? 2 : 0;
                    } else if ($issued->is_return !== 1) {
                        $isOverdue = $now->greaterThan($toReturn);
                    }

                    return [
                        'is_return' => $issued->is_return,
                    ];
                });
            // Acquire the issuedBooks then count the group is_return and return as an associative array
            $issuedBooksCount = $issuedBooks->groupBy('is_return')
                ->map(function ($group) {
                    return count($group);
                })
                ->toArray();

            return Inertia::render('Dashboard', [
                'allBooksCount' => $allBooksCount,
                'totalActiveBooks' => $activeBookCount,
                'inactiveBookCount' => $inactiveBookCount,
                'pendingBookRequestCount' => $pendingBookRequestCount,
                'approvedBookRequestCount' => $approvedBookRequestCount,
                'rejectedBookRequestCount' => $rejectedBookRequestCount,
                'unpaidInvoiceCount' => $unpaidInvoiceCount,
                'paidInvoiceCount' => $paidInvoiceCount,
                'otcInvoiceCount' => $otcInvoiceCount,
                'returnedBooks' => $returnedBooks,
                'issuedBooksCount' => $issuedBooksCount,
            ]);
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
}
