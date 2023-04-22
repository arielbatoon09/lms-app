<?php

use App\Http\Controllers\ProfileController;
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

// Guest
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Dashboard Page Route
Route::get('/admin/', function() {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'verified'])->name('admin');

Route::get('/admin/manage-books', function() {
    return Inertia::render('Admin/Manage-books');
})->middleware(['auth', 'verified'])->name('manage-books');

Route::get('/admin/book-categories', function() {
    return Inertia::render('Admin/Book-categories');
})->middleware(['auth', 'verified'])->name('book-categories');

Route::get('/admin/book-authors', function() {
    return Inertia::render('Admin/Book-authors');
})->middleware(['auth', 'verified'])->name('book-authors');

Route::get('/admin/issued-books', function() {
    return Inertia::render('Admin/Issued-books');
})->middleware(['auth', 'verified'])->name('issued-books');

Route::get('/admin/transactions', function() {
    return Inertia::render('Admin/Transactions');
})->middleware(['auth', 'verified'])->name('transactions');

Route::get('/admin/user-management', function() {
    return Inertia::render('Admin/User-management');
})->middleware(['auth', 'verified'])->name('user-management');

Route::get('/admin/settings', function() {
    return Inertia::render('Admin/Settings');
})->middleware(['auth', 'verified'])->name('settings');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
