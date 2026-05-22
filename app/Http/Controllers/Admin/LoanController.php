<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
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
    // FIX: method ini yang dipanggil route admin.loans.return
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

        return back()->with('success', 'Buku berhasil ditandai sudah dikembalikan.');
    }
}
