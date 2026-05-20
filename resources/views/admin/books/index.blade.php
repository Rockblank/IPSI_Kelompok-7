@extends('layouts.admin')
@section('title', 'Kelola Buku')

@section('content')
<div class="page-header">
    <h1>Koleksi Buku</h1>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">+ Tambah Buku</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th style="text-align:center">Stok</th>
                <th style="text-align:center">Status</th>
                <th style="text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $book)
            <tr>
                <td>
                    <div style="font-weight:500">{{ $book->book_title }}</div>
                </td>
                <td style="color:var(--muted)">{{ $book->author }}</td>
                <td style="color:var(--muted)">{{ $book->publisher ?? '—' }}</td>
                <td style="text-align:center">{{ $book->available_stock }}</td>
                <td style="text-align:center">
                    @if($book->book_status === 'tersedia')
                        <span class="badge badge-available">Tersedia</span>
                    @else
                        <span class="badge badge-empty">Habis</span>
                    @endif
                </td>
                <td style="text-align:center">
                    <div style="display:flex;gap:8px;justify-content:center">
                        <a href="{{ route('admin.books.edit', $book->book_id) }}"
                           class="btn btn-outline btn-sm">Ubah</a>

                        <form method="POST"
                              action="{{ route('admin.books.destroy', $book->book_id) }}"
                              onsubmit="return confirm('Hapus buku ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">
                    Belum ada buku. <a href="{{ route('admin.books.create') }}">Tambah sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($books->hasPages())
    <div class="pagination">
        @if($books->onFirstPage())
            <span>‹</span>
        @else
            <a href="{{ $books->previousPageUrl() }}">‹</a>
        @endif

        @foreach($books->getUrlRange(1, $books->lastPage()) as $page => $url)
            @if($page == $books->currentPage())
                <span class="active">{{ $page }}</span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if($books->hasMorePages())
            <a href="{{ $books->nextPageUrl() }}">›</a>
        @else
            <span>›</span>
        @endif
    </div>
    @endif
</div>
@endsection
