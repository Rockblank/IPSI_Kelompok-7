<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\NotificationController;

// ── ROOT (Otomatis dialihkan ke Login) ───────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── GUEST ROUTES (Belum Login) ──────────────────────────────────
Route::middleware('guest.check')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ── AUTH LOGOUT ─────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── USER / MEMBER ROUTES (Sudah Login) ──────────────────────────
Route::middleware(['auth.check'])->group(function () {

    // Dashboard & Pencarian
    Route::get('/dashboard',        [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');
    Route::get('/books/{book}',     [DashboardController::class, 'show'])->name('books.show');

    // Keranjang (Aurel)
    Route::get('/cart',               [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}',    [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{item}',      [CartController::class, 'destroy'])->name('cart.destroy');

    // Peminjaman & Konfirmasi (Khansa)
    Route::post('/loans',              [LoanController::class, 'store'])->name('loans.store');

    // History / Riwayat (Khansa)
    Route::get('/history',             [HistoryController::class, 'index'])->name('history.index');

    // Notifikasi Kotak Masuk User (Khansa)
    Route::get('/notifications',       [NotificationController::class, 'index'])->name('notifications.index');
});

// ── ADMIN ROUTES (Khusus Admin) ──────────────────────────────────
Route::middleware(['auth.check', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Kelola Buku (Archie)
        Route::resource('books', AdminBookController::class);

        // Kelola Peminjaman Oleh Admin (Khansa)
        Route::get('/loans',                 [LoanController::class, 'adminIndex'])->name('loans.index');
        Route::post('/loans/{loan_id}/return', [LoanController::class, 'adminUpdateStatus'])->name('loans.return');
    }); // <-- BERHENTI DI SINI. KODE DUPLIKAT DI BAWAHNYA SUDAH DIHAPUS.
