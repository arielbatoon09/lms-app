<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BooksController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookRequestController;
use App\Http\Controllers\Admin\IssuedBooksController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserBooksController;
use App\Http\Controllers\UserInvoices;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// 1. User Controller
Route::middleware('auth')->group(function () {
    Route::get('/admin/user-management', [UserController::class, 'index'])->middleware(['auth', 'verified', 'is_admin'])->name('user-management');
    Route::patch('/admin/update-user/{id}', [UserController::class, 'update']);
    Route::post('/admin/delete-user/{id}', [UserController::class, 'destroy']);

    // Browse, Request, Issued Books Route
    Route::get('/books', [UserBooksController::class, 'index'])->middleware('verified', 'is_user')->name('books');
    Route::get('/book-details/{id}', [UserBooksController::class, 'getBookDetails'])->middleware('verified', 'is_user');
    Route::post('/book-details', [UserBooksController::class, 'doRequestBook'])->middleware('verified', 'is_user')->name('book.borrow');
    Route::get('/book-request', [UserBooksController::class, 'BookRequestPage'])->middleware('verified', 'is_user')->name('book-request');
    Route::get('/book-issued', [UserBooksController::class, 'getIssuedBookDetails'])->middleware('verified', 'is_user')->name('books-issued');

    // Profile Management
    Route::get('/settings', [ProfileController::class, 'edit'])->middleware('auth', 'verified', 'is_user')->name('profile.edit');
    Route::patch('/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/settings', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Invoices
    Route::get('/invoices', [UserInvoices::class, 'index'])->middleware('verified', 'is_user')->name('invoices.user');

    // Temporary Route for User
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified', 'is_user'])->name('dashboard');

});

// 2. Admin Controllers
Route::middleware('auth')->group(function () {
    // Temporary Admin Page Route
    Route::get('/admin/', function () {
        return Inertia::render('Admin/Dashboard');
    })->middleware(['auth', 'verified', 'is_admin'])->name('admin');

    // Manage Books
    Route::get('/admin/manage-books', [BooksController::class, 'index'])->middleware(['verified', 'is_admin'])->name('manage-books');
    Route::post('/admin/add-book', [BooksController::class, 'store']);
    Route::post('/admin/delete-book/{id}', [BooksController::class, 'destroy']);
    Route::post('/admin/update-book/{id}', [BooksController::class, 'update']);

    // Manage Category
    Route::get('/admin/book-categories', [CategoryController::class, 'index'])->middleware(['verified', 'is_admin'])->name('book-categories');
    Route::post('/admin/add-category', [CategoryController::class, 'store']);
    Route::post('/admin/delete-category/{id}', [CategoryController::class, 'destroy']);
    Route::patch('/admin/update-category/{id}', [CategoryController::class, 'update']);

    // Manage Author
    Route::get('/admin/book-authors', [AuthorController::class, 'index'])->middleware(['verified', 'is_admin'])->name('book-authors');
    Route::post('/admin/add-author', [AuthorController::class, 'store']);
    Route::post('/admin/delete-author/{id}', [AuthorController::class, 'destroy']);
    Route::patch('/admin/update-author/{id}', [AuthorController::class, 'update']);

    // Manage Book Request
    Route::get('/admin/book-request', [BookRequestController::class, 'index'])->middleware(['verified', 'is_admin'])->name('admin-book-request');
    Route::put('/admin/book-request', [BookRequestController::class, 'updateBookRequest'])->middleware(['verified', 'is_admin'])->name('admin-book-request.update');
    Route::delete('/admin/book-request', [BookRequestController::class, 'deleteBookRequest'])->middleware(['verified', 'is_admin'])->name('admin-book-request.destroy');

    // Manage Book Issued
    Route::get('/admin/issued-books', [IssuedBooksController::class, 'getAllIssuedBookDetails'])->middleware(['verified', 'is_admin'])->name('issued-books');
    Route::put('/admin/issued-books', [IssuedBooksController::class, 'updateBookIssued'])->middleware('verified', 'is_admin')->name('issued-books.update');
    Route::delete('/admin/issued-books', [IssuedBooksController::class, 'deleteBookIssued'])->middleware('verified', 'is_admin')->name('issued-books.destroy');


    Route::get('/admin/transactions', function () {
        return Inertia::render('Admin/Transactions');
    })->middleware(['auth', 'verified', 'is_admin'])->name('transactions');

    Route::get('/admin/settings', function () {
        return Inertia::render('Admin/Settings');
    })->middleware(['auth', 'verified', 'is_admin'])->name('settings');

});


require __DIR__ . '/auth.php';
