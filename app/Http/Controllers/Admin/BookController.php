<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CartController;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query   = Book::query();
        $keyword = $request->get('search');

        if ($keyword) {
            $query->where('book_title', 'like', "%{$keyword}%");
        }

        $books = $query->latest()->paginate(10);

        return view('admin.books.index', compact('books', 'keyword'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_title'      => 'required|string|max:255',
            'author'          => 'required|string|max:100',
            'publisher'       => 'nullable|string|max:100',
            'available_stock' => 'required|integer|min:0',
        ], [
            'book_title.required'      => 'Judul buku wajib diisi.',
            'author.required'          => 'Penulis wajib diisi.',
            'available_stock.required' => 'Stok wajib diisi.',
            'available_stock.integer'  => 'Stok harus berupa angka.',
            'available_stock.min'      => 'Stok tidak boleh negatif.',
        ]);

        Book::create([
            'book_title'      => $request->book_title,
            'author'          => $request->author,
            'publisher'       => $request->publisher,
            'available_stock' => $request->available_stock,
            'book_status'     => $request->available_stock > 0 ? 'tersedia' : 'habis',
        ]);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'book_title'      => 'required|string|max:255',
            'author'          => 'required|string|max:100',
            'publisher'       => 'nullable|string|max:100',
            'available_stock' => 'required|integer|min:0',
        ]);

        $stockBefore = $book->available_stock;

        $book->update([
            'book_title'      => $request->book_title,
            'author'          => $request->author,
            'publisher'       => $request->publisher,
            'available_stock' => $request->available_stock,
            'book_status'     => $request->available_stock > 0 ? 'tersedia' : 'habis',
        ]);

        // Jika stok sebelumnya 0 dan sekarang > 0, kirim notifikasi ke user yang mengantri
        if ($stockBefore <= 0 && $request->available_stock > 0) {
            CartController::notifyQueue($book->book_id);
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
