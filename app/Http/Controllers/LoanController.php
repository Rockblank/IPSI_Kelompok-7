<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Notification;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\CartQueue;

class LoanController extends Controller
{
    // GET /loans/confirm → tampilkan halaman konfirmasi
    public function confirm()
    {
        $user_id   = session('user_id');
        $cartItems = CartQueue::with('book')
            ->where('user_id', $user_id)
            ->where('type', 'cart')
            ->get();

        return view('loans.confirm', compact('cartItems'));
    }

    // POST /loans → proses konfirmasi pinjam
    public function store(Request $request)
    {
        $user_id = session('user_id');

        $request->validate([
            'lama_pinjam' => 'required|integer|min:1|max:3',
        ], [
            'lama_pinjam.required' => 'Lama peminjaman wajib diisi.',
            'lama_pinjam.integer'  => 'Lama peminjaman harus berupa angka.',
            'lama_pinjam.min'      => 'Minimal peminjaman adalah 1 hari.',
            'lama_pinjam.max'      => 'Maksimal peminjaman adalah 3 hari.',
        ]);

        $lama_pinjam = (int) $request->lama_pinjam;

        $keranjang = CartQueue::where('user_id', $user_id)
            ->where('type', 'cart')
            ->get();

        if ($keranjang->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Tidak ada buku yang dipilih. Tambahkan buku ke keranjang terlebih dahulu.');
        }

        $loan_date = Carbon::now()->toDateString();
        $due_date  = Carbon::now()->addDays($lama_pinjam)->toDateString();

        DB::transaction(function () use ($keranjang, $user_id, $loan_date, $due_date) {
            foreach ($keranjang as $item) {
                $book = Book::find($item->book_id);

                if (!$book || $book->available_stock <= 0) {
                    continue;
                }

                Loan::create([
                    'user_id'            => $user_id,
                    'book_id'            => $item->book_id,
                    'loan_date'          => $loan_date,
                    'due_date'           => $due_date,
                    'return_date'        => null,
                    'transaction_status' => 'borrowed',
                ]);

                $book->decrement('available_stock');

                if ($book->available_stock <= 0) {
                    $book->update(['book_status' => 'habis']);
                }
            }

            CartQueue::where('user_id', $user_id)
                ->where('type', 'cart')
                ->delete();
        });

        return redirect()->route('history.index')
            ->with('success', 'Peminjaman Buku Anda telah dikonfirmasi.');
    }
}
