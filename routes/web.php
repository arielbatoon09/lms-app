<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BooksController;
use App\Http\Controllers\Admin\UserController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Admin Dashboard Page Route
Route::get('/admin/', function() {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'verified'])->name('admin');

Route::get('/admin/issued-books', function() {
    return Inertia::render('Admin/Issued-books');
})->middleware(['auth', 'verified'])->name('issued-books');

Route::get('/admin/transactions', function() {
    return Inertia::render('Admin/Transactions');
})->middleware(['auth', 'verified'])->name('transactions');


Route::get('/admin/settings', function() {
    return Inertia::render('Admin/Settings');
})->middleware(['auth', 'verified'])->name('settings');


// User Management Controller
Route::middleware('auth')->group(function() {
    Route::get('/admin/user-management', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('user-management');
    Route::patch('/admin/update-user/{id}', [UserController::class, 'update']);
    Route::post('/admin/delete-user/{id}', [UserController::class, 'destroy']);
});

// Admin Books Controller
Route::middleware('auth')->group(function() {
    Route::get('/admin/manage-books', [BooksController::class, 'index'])->middleware(['auth', 'verified'])->name('manage-books');
    Route::post('/admin/add-book', [BooksController::class, 'store']);
    Route::post('/admin/delete-book/{id}', [BooksController::class, 'destroy']);
    Route::post('/admin/update-book/{id}', [BooksController::class, 'update']);
});

// Admin Category Controller
Route::middleware('auth')->group(function() {
    Route::get('/admin/book-categories', [CategoryController::class, 'index'])->middleware(['auth', 'verified'])->name('book-categories');
    Route::post('/admin/add-category', [CategoryController::class, 'store']);
    Route::post('/admin/delete-category/{id}', [CategoryController::class, 'destroy']);
    Route::patch('/admin/update-category/{id}', [CategoryController::class, 'update']);
});

// Admin Author Controller
Route::middleware('auth')->group(function() {
    Route::get('/admin/book-authors', [AuthorController::class, 'index'])->middleware(['auth', 'verified'])->name('book-authors');
    Route::post('/admin/add-author', [AuthorController::class, 'store']);
    Route::post('/admin/delete-author/{id}', [AuthorController::class, 'destroy']);
    Route::patch('/admin/update-author/{id}', [AuthorController::class, 'update']);
});

// Profile Group
Route::middleware('auth')->group(function () {
    Route::get('/settings', [ProfileController::class, 'edit'])->middleware('auth', 'verified')->name('profile.edit');
    Route::patch('/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/settings', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
