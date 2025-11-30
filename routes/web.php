<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// ROUTE UNTUK SEMUA USER
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // NOTIFICATIONS ROUTES
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    });

    // BOOKS ROUTES - Semua user bisa lihat buku
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    
    // REVIEWS ROUTES - Semua user bisa lihat review
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
});

// ROUTE KHUSUS ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    // Tambahkan route admin lainnya di sini

    Route::get('/users/activities', [DashboardController::class, 'allActivities'])->name('admin.users.activities');
    
    // User Management - Admin only
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    
    // Books Management - Admin only
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.delete');
});

// ROUTE KHUSUS MAHASISWA DENGAN CEK DENDA
Route::middleware(['auth', 'mahasiswa'])->prefix('mahasiswa')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('mahasiswa.dashboard');

    // Route yang diblokir jika ada denda
    Route::middleware(['check.denda'])->group(function () {
        Route::post('/books/{book}/loan', [BookController::class, 'loan'])->name('books.loan');
        Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
        Route::post('/loans/{loan}/renew', [LoanController::class, 'renew'])->name('loans.renew');
    });
    
    // Route yang tetap bisa diakses meski ada denda
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/loans/history', [LoanController::class, 'history'])->name('loans.history');

    Route::get('/reviews/create/{loan}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// ROUTE KHUSUS PEGAWAI
Route::middleware(['auth', 'pegawai'])->prefix('pegawai')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pegawai'])->name('pegawai.dashboard');
    // Tambahkan route pegawai lainnya di sini

    // Loans Management - Pegawai only
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::get('/loans/{loan}/edit', [LoanController::class, 'edit'])->name('loans.edit');
    Route::put('/loans/{loan}', [LoanController::class, 'update'])->name('loans.update');
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');

    // ✅ HANYA PEGAWAI YANG BISA PROSES PENGEMBALIAN
    Route::post('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');
    Route::get('/pegawai/notifications/log', [NotificationController::class, 'systemLog'])->name('pegawai.notifications.log');
});

// ROUTE BOOKS UNTUK SEMUA (TANPA PROTECTED)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

require __DIR__.'/auth.php';