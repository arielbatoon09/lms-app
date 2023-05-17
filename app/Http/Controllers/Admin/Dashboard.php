<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Invoices;
use App\Models\User;
use App\Models\Admin\Books;
use App\Models\BookRequest;

class Dashboard extends Controller
{
    //
    public function index()
    {
        try{
            $invoiceList = Invoices::where('action', 1)->get();
            $userCount = User::count();
            $activeBookCount = Books::where('is_active', 1)->count();
            $bookRequestCount = BookRequest::where('status', 2)->count();
            $sumTotalIncome = Invoices::where('action', 1)->sum('book_fees');
    
            $chartData = $invoiceList->groupBy(function ($invoice) {
                return Carbon::parse($invoice->created_at)->format('F Y');
            })->map(function ($group) {
                return $group->sum('book_fees');
            });
        
            return Inertia::render('Admin/Dashboard', [
                'chartData' => $chartData,
                'totalUser' => $userCount,
                'totalActiveBooks' => $activeBookCount,
                'bookRequestCount' => $bookRequestCount,
                'sumTotalIncome' => $sumTotalIncome,
            ]);
        }catch(\Exception $error){
            return $error->getMessage();
        }
    }

}
