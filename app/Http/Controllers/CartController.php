<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\CartQueue;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Lihat Keranjang
    public function index()
    {
        $userId = session('user_id');
        $cartItems = CartQueue::where('user_id', $userId)
            ->where('type', 'cart')
            ->with('book')
            ->get();
        $namaUser = session('user_name', 'User');
        return view('cart.index', compact('cartItems', 'namaUser'));
    }

    //Tambah ke Keranjang
    public function add($bookId)
    {
        $userId = session('user_id');
        $book = Book::findOrFail($bookId);

        // Validasi Stok
        if ($book->available_stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok buku ini sudah habis.');
        }

        // Validasi Duplikat
        if (CartQueue::where('user_id', $userId)->where('book_id', $bookId)->where('type', 'cart')->exists()) {
            return redirect()->back()->with('error', 'Buku sudah ada di keranjang Anda.');
        }

        // Validasi Maksimal 10 item
        $count = CartQueue::where('user_id', $userId)->where('type', 'cart')->count();
        if ($count >= 10) {
            return redirect()->back()->with('error', 'Keranjang maksimal hanya 10 buku.');
        }

        CartQueue::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'type'    => 'cart',
            'is_reminder_active' => 0,
        ]);

        return redirect()->back()->with('success', 'Buku "' . $book->book_title . '" berhasil ditambahkan ke keranjang!');
        
    }

    //Hapus dari Keranjang
    public function destroy($itemId)
    {
        $item = CartQueue::where('item_id', $itemId)
                         ->where('user_id', session('user_id'))
                         ->firstOrFail();

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Buku berhasil dihapus dari keranjang.');
    }
}