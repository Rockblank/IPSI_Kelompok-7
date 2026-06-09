<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\CartQueue;
use App\Models\Notification;

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

        $queueItems = CartQueue::where('user_id', $userId)
            ->where('type', 'queue')
            ->with('book')
            ->get();

        $namaUser = session('user_name', 'User');

        return view('cart.index', compact('cartItems', 'queueItems', 'namaUser'));
    }

    // Tambah ke Keranjang (buku tersedia)
    public function add($bookId)
    {
        $userId = session('user_id');
        $result = self::addBookToCart($userId, $bookId);

        return redirect()->back()->with($result['status'], $result['message']);
    }

    public static function addBookToCart(int $userId, int $bookId): array
    {
        $book = Book::find($bookId);

        if (!$book) {
            return ['status' => 'error', 'message' => 'Buku tidak ditemukan.'];
        }

        if ($book->available_stock <= 0) {
            return ['status' => 'error', 'message' => 'Maaf, stok buku ini sudah habis.'];
        }

        if (CartQueue::where('user_id', $userId)->where('book_id', $bookId)->where('type', 'cart')->exists()) {
            return ['status' => 'error', 'message' => 'Buku sudah ada di keranjang Anda.'];
        }

        $count = CartQueue::where('user_id', $userId)->where('type', 'cart')->count();
        if ($count >= 10) {
            return ['status' => 'error', 'message' => 'Keranjang maksimal hanya 10 buku.'];
        }

        CartQueue::create([
            'user_id'            => $userId,
            'book_id'            => $bookId,
            'type'               => 'cart',
            'is_reminder_active' => 0,
        ]);

        return ['status' => 'success', 'message' => 'Buku "' . $book->book_title . '" berhasil ditambahkan ke keranjang!'];
    }

    // Daftar antrian notifikasi untuk buku yang sedang habis
    public function addToQueue($bookId)
    {
        $userId = session('user_id');
        $result = self::addBookToQueue($userId, $bookId);

        return redirect()->back()->with($result['status'], $result['message']);
    }

    public static function addBookToQueue(int $userId, int $bookId): array
    {
        $book = Book::find($bookId);

        if (!$book) {
            return ['status' => 'error', 'message' => 'Buku tidak ditemukan.'];
        }

        if ($book->available_stock > 0) {
            return ['status' => 'success', 'message' => 'Buku tersedia, sudah dapat ditambahkan ke keranjang.'];
        }

        if (CartQueue::where('user_id', $userId)->where('book_id', $bookId)->exists()) {
            return ['status' => 'error', 'message' => 'Buku sudah ada di keranjang atau daftar notifikasi Anda.'];
        }

        CartQueue::create([
            'user_id'            => $userId,
            'book_id'            => $bookId,
            'type'               => 'queue',
            'is_reminder_active' => 1,
        ]);

        return ['status' => 'success', 'message' => 'Notifikasi untuk "' . $book->book_title . '" diaktifkan. Anda akan diberitahu saat buku tersedia.'];
    }

    // Hapus dari Keranjang / Antrian
    public function destroy($itemId)
    {
        $item = CartQueue::where('item_id', $itemId)
                         ->where('user_id', session('user_id'))
                         ->firstOrFail();

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Buku berhasil dihapus dari keranjang.');
    }

    /**
     * Dipanggil oleh AdminBookController (saat stok buku diupdate dari 0 ke > 0)
     * dan AdminLoanController (saat buku dikembalikan).
     *
     * Mengirim notifikasi ke semua user yang mengantri buku tersebut,
     * lalu memindahkan entry dari type=queue ke type=cart agar bisa langsung dipinjam.
     */
    public static function notifyQueue(int $bookId): void
    {
        $queueEntries = CartQueue::where('book_id', $bookId)
            ->where('type', 'queue')
            ->where('is_reminder_active', 1)
            ->get();

        foreach ($queueEntries as $entry) {
            // Ambil judul buku untuk pesan notifikasi
            $bookTitle = $entry->book ? $entry->book->book_title : 'yang Anda tunggu';

            Notification::create([
                'user_id' => $entry->user_id,
                'message' => 'Buku "' . $bookTitle . '" kini tersedia. Buka keranjang untuk meminjamnya.',
                'sent_at' => now(),
                'is_read' => false,
            ]);

            // Pindahkan ke cart agar langsung bisa dipinjam
            $entry->update([
                'type'               => 'cart',
                'is_reminder_active' => 0,
            ]);
        }
    }
}
