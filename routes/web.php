<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;

Route::get('/', fn() => redirect()->route('dashboard'));

// ── GUEST ────────────────────────────────────────────────────────
Route::middleware('guest.check')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ── LOGOUT ───────────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── PUBLIC USER ROUTES ─────────────────────────────────────────────
Route::get('/dashboard',        [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');
Route::get('/books/{book}',     [DashboardController::class, 'show'])->name('books.show');

// ── USER ROUTES ──────────────────────────────────────────────────
Route::middleware(['auth.check'])->group(function () {

    Route::get('/cart',                    [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}',        [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/queue/{book}',      [CartController::class, 'addToQueue'])->name('cart.queue'); // <<< Baru: antri notifikasi buku habis
    Route::delete('/cart/{item}',          [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/loans/confirm',    [LoanController::class, 'confirm'])->name('loans.confirm');
    Route::post('/loans',           [LoanController::class, 'store'])->name('loans.store');

    Route::get('/history',          [HistoryController::class, 'index'])->name('history.index');
    Route::get('/notifications',    [NotificationController::class, 'index'])->name('notifications.index');
});

// ── ADMIN ROUTES ─────────────────────────────────────────────────
Route::middleware(['auth.check', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('books', AdminBookController::class);

        Route::get('/loans',                   [AdminLoanController::class, 'index'])->name('loans.index');
        Route::post('/loans/{loan_id}/return', [AdminLoanController::class, 'update'])->name('loans.return');
    });
