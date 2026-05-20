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

// ── ROOT ───────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── GUEST (belum login) ────────────────────────────────────────
Route::middleware('guest.check')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.post');
});

// ── AUTH (sudah login) ─────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── USER ROUTES ────────────────────────────────────────────────
Route::middleware(['auth.check'])->group(function () {

    // Dashboard & Pencarian  → Aurel
    Route::get('/dashboard',        [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');
    Route::get('/books/{book}',     [DashboardController::class, 'show'])->name('books.show');

    // Keranjang  → Aurel
    Route::get('/cart',                [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}',    [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{item}',      [CartController::class, 'destroy'])->name('cart.destroy');

    // Peminjaman  → Khansa
    Route::post('/loans',              [LoanController::class, 'store'])->name('loans.store');

    // History  → Khansa
    Route::get('/history',             [HistoryController::class, 'index'])->name('history.index');

    // Notifikasi  → Khansa
    Route::get('/notifications',       [NotificationController::class, 'index'])->name('notifications.index');
});

// ── ADMIN ROUTES ───────────────────────────────────────────────
Route::middleware(['auth.check', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Kelola Buku  → Archie
        Route::resource('books', AdminBookController::class);

        // Kelola Peminjaman  → Khansa
        Route::get('/loans',          [AdminLoanController::class, 'index'])->name('loans.index');
        Route::put('/loans/{loan}',   [AdminLoanController::class, 'update'])->name('loans.update');
    });
