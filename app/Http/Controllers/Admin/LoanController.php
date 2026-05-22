<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CartController;
use App\Models\Loan;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // GET /admin/loans
    public function index()
    {
        $loans = Loan::with(['user', 'book'])
            ->orderBy('loan_date', 'desc')
            ->get();

        return view('admin.loans.index', compact('loans'));
    }

    // POST /admin/loans/{loan_id}/return
    public function update(Request $request, $loan_id)
    {
        $loan = Loan::findOrFail($loan_id);

        if ($loan->transaction_status === 'returned') {
            return back()->with('error', 'Buku ini sudah ditandai kembali sebelumnya.');
        }

        $loan->update([
            'transaction_status' => 'returned',
            'return_date'        => Carbon::now()->toDateString(),
        ]);

        // Kembalikan stok buku
        $loan->book->increment('available_stock');
        if ($loan->book->book_status === 'habis') {
            $loan->book->update(['book_status' => 'tersedia']);
        }

        // Kirim notifikasi ke semua user yang mengantri buku ini
        CartController::notifyQueue($loan->book_id);

        return back()->with('success', 'Buku berhasil ditandai sudah dikembalikan.');
    }

    /**
     * Kirim notifikasi denda keterlambatan ke semua peminjam yang belum mengembalikan
     * melebihi due_date. Denda Rp2.000 per hari.
     *
     * Dipanggil otomatis setiap hari pukul 08.00 via: php artisan notifications:overdue
     */
    public static function sendOverdueNotifications(): void
    {
        $today = Carbon::today();

        $overdueLoans = Loan::with(['user', 'book'])
            ->where('transaction_status', 'borrowed')
            ->where('due_date', '<', $today->toDateString())
            ->get();

        foreach ($overdueLoans as $loan) {
            $daysLate  = Carbon::parse($loan->due_date)->diffInDays($today);
            $totalFine = $daysLate * 2000;

            Notification::create([
                'user_id' => $loan->user_id,
                'message' => 'Buku "' . $loan->book->book_title . '" terlambat dikembalikan '
                           . $daysLate . ' hari. Total denda: Rp' . number_format($totalFine, 0, ',', '.') . '.',
                'sent_at' => now(),
                'is_read' => false,
            ]);
        }
    }
}
