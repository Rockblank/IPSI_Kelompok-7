<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Notification;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    // ========================================================
    // BAGIAN 5 & 6: LOGIKA TRIGGER JATUH TEMPO (PSPEC hal. 52)
    // ========================================================
    public static function triggerNotifications()
    {
        $today = Carbon::today()->toDateString();

        // Cari peminjaman aktif yang durasinya sudah/melewati hari ini
        $overdueLoans = Loan::where('transaction_status', 'borrowed')
            ->where('due_date', '<=', $today)
            ->get();

        $inserted = 0;

        foreach ($overdueLoans as $loan) {
            $message = "Pengingat: Buku yang Anda pinjam (ID: #{$loan->loan_id}) telah mencapai/melewati batas jatuh tempo ({$loan->due_date}). Harap segera mengembalikannya.";

            // Mencegah duplikasi notifikasi ganda di hari yang sama
            $exists = Notification::where('user_id', $loan->user_id)
                ->where('message', $message)
                ->whereDate('sent_at', Carbon::today())
                ->exists();

            if (!$exists) {
                Notification::create([
                    'user_id' => $loan->user_id,
                    'message' => $message,
                    'sent_at' => Carbon::now(),
                    'is_read' => false
                ]);
                $inserted++;
            }
        }

        return $inserted;
    }

    // ========================================================
    // BAGIAN 7: ADMIN - VIEW DAFTAR PINJAM & UPDATE 'RETURNED'
    // ========================================================
    public function adminIndex()
    {
        // Mengambil seluruh data pinjaman beserta relasi user dan book
        $loans = Loan::with(['user', 'book'])
            ->orderBy('transaction_status', 'asc')
            ->orderBy('loan_date', 'desc')
            ->get();

        // Mengarah ke folder resources/views/admin/loans/index.blade.php
        return view('admin.loans.index', compact('loans'));
    }

    public function adminUpdateStatus($loan_id)
    {
        // Sesuai Catatan Lead Programmer: Update stok wajib pakai DB::transaction()
        DB::transaction(function () use ($loan_id) {
            $loan = Loan::findOrFail($loan_id);

            if ($loan->transaction_status === 'borrowed') {
                // Set status jadi returned dan simpan tanggal pengembalian hari ini
                $loan->update([
                    'transaction_status' => 'returned',
                    'return_date' => Carbon::now()->toDateString()
                ]);

                // Kembalikan ketersediaan stok buku (+1)
                $book = Book::findOrFail($loan->book_id);
                $newStock = $book->available_stock + 1;
                $book->update([
                    'available_stock' => $newStock,
                    'book_status' => $newStock > 0 ? 'tersedia' : 'habis'
                ]);
            }
        });

        return redirect()->back()->with('success', 'Status peminjaman berhasil diperbarui menjadi Returned dan stok buku telah ditambahkan kembali!');
    }
}