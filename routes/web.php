<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BooksController;
use App\Http\Controllers\Admin\UserController;
// User Side
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserBooksController;
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

// Temporary Route for User
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'is_user'])->name('dashboard');


Route::get('/books-issued', function () {
    return Inertia::render('Books-issued');
})->middleware(['auth', 'verified', 'is_user'])->name('books-issued');


Route::get('/my-transactions', function () {
    return Inertia::render('My-transactions');
})->middleware(['auth', 'verified', 'is_user'])->name('my-transactions');

// Temporary Admin Dashboard Page Route
Route::get('/admin/', function() {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'verified', 'is_admin'])->name('admin');

Route::get('/admin/issued-books', function() {
    return Inertia::render('Admin/Issued-books');
})->middleware(['auth', 'verified' ,'is_admin'])->name('issued-books');

Route::get('/admin/transactions', function() {
    return Inertia::render('Admin/Transactions');
})->middleware(['auth', 'verified', 'is_admin'])->name('transactions');


Route::get('/admin/settings', function() {
    return Inertia::render('Admin/Settings');
})->middleware(['auth', 'verified', 'is_admin'])->name('settings');

// Browse, Request, Issued Books Route
Route::middleware('auth')->group(function() {
    Route::get('/books', [UserBooksController::class, 'index'])->middleware('verified', 'is_user')->name('books');
    Route::get('/book-details/{id}', [UserBooksController::class, 'getBookDetails'])->middleware('verified', 'is_user');
    Route::post('/book-details', [UserBooksController::class, 'doRequestBook'])->middleware('verified', 'is_user')->name('book.borrow');
    Route::get('/book-request', [UserBooksController::class, 'BookRequestPage'])->middleware('verified', 'is_user')->name('book-request');
});


// User Management Controller
Route::middleware('auth')->group(function() {
    Route::get('/admin/user-management', [UserController::class, 'index'])->middleware(['auth', 'verified', 'is_admin'])->name('user-management');
    Route::patch('/admin/update-user/{id}', [UserController::class, 'update']);
    Route::post('/admin/delete-user/{id}', [UserController::class, 'destroy']);
});

// Admin Books Controller
Route::middleware('auth')->group(function() {
    Route::get('/admin/manage-books', [BooksController::class, 'index'])->middleware(['auth', 'verified', 'is_admin'])->name('manage-books');
    Route::post('/admin/add-book', [BooksController::class, 'store']);
    Route::post('/admin/delete-book/{id}', [BooksController::class, 'destroy']);
    Route::post('/admin/update-book/{id}', [BooksController::class, 'update']);
});

// Admin Category Controller
Route::middleware('auth')->group(function() {
    Route::get('/admin/book-categories', [CategoryController::class, 'index'])->middleware(['auth', 'verified', 'is_admin'])->name('book-categories');
    Route::post('/admin/add-category', [CategoryController::class, 'store']);
    Route::post('/admin/delete-category/{id}', [CategoryController::class, 'destroy']);
    Route::patch('/admin/update-category/{id}', [CategoryController::class, 'update']);
});

// Admin Author Controller
Route::middleware('auth')->group(function() {
    Route::get('/admin/book-authors', [AuthorController::class, 'index'])->middleware(['auth', 'verified', 'is_admin'])->name('book-authors');
    Route::post('/admin/add-author', [AuthorController::class, 'store']);
    Route::post('/admin/delete-author/{id}', [AuthorController::class, 'destroy']);
    Route::patch('/admin/update-author/{id}', [AuthorController::class, 'update']);
});

// Profile Group
Route::middleware('auth')->group(function () {
    Route::get('/settings', [ProfileController::class, 'edit'])->middleware('auth', 'verified', 'is_user')->name('profile.edit');
    Route::patch('/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/settings', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
