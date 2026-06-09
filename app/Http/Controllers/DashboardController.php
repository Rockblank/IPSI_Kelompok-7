<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Book;
use Exception;

class DashboardController extends Controller
{
    // ── 1. METHOD INDEX (Tugas No. 1) ───────────────────────────────────
    public function index()
    {
        if (Session::get('role') === 'admin') {
            return redirect()->route('admin.books.index');
        }

        $namaUser = Session::get('user_name', 'Teman');

        try {
            $allBooks = Book::all();
        } catch (Exception $e) {
            return response()->view('errors.custom', ['message' => 'Sistem tidak tersedia'], 500);
        }

        return view('dashboard.index', compact('namaUser', 'allBooks'));
    }

    // ── 2. METHOD SEARCH (Tugas No. 2) ──────────────────────────────────
    public function search(Request $request)
    {
        $namaUser = Session::get('user_name', 'Teman');
        $keyword = $request->input('keyword');

        $query = Book::query();

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('book_title', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('author', 'LIKE', '%' . $keyword . '%');
            });
        }

        $daftarBuku = $query->get();

        return view('dashboard.search', compact('namaUser', 'keyword', 'daftarBuku'));
    }

    // ── 3. METHOD SHOW (Detail Buku — Pindahan dari BookController) ─────
    public function show($id)
    {
        $namaUser = Session::get('user_name', 'Teman');

        // Mengambil data buku berdasarkan id, jika tidak ada langsung memicu error 404
        $buku = Book::findOrFail($id);

        return view('dashboard.show', compact('namaUser', 'buku'));
    }
}
