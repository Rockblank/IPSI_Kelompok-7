@extends('layouts.admin')
@section('title', 'Ubah Data Buku')

@section('content')
<div class="page-header">
    <h1>Ubah Data Buku</h1>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline">← Kembali</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start">

    {{-- Preview buku (kiri) - sesuai wireframe Detail Update Admin --}}
    <div class="card">
        <div style="display:flex;gap:20px">
            {{-- Placeholder cover --}}
            <div style="width:80px;height:110px;background:var(--border);border-radius:6px;flex-shrink:0"></div>
            <div style="flex:1">
                <h2 style="font-family:'DM Serif Display',serif;font-size:20px;margin-bottom:8px">
                    {{ $book->book_title }}
                </h2>
                <div style="font-size:13px;color:var(--muted);display:flex;flex-direction:column;gap:4px">
                    <span>Penulis: <strong style="color:var(--text)">{{ $book->author }}</strong></span>
                    <span>Penerbit: <strong style="color:var(--text)">{{ $book->publisher ?? '—' }}</strong></span>
                    <span>Stok:
                        <strong style="color:var(--text)">{{ $book->available_stock }}</strong>
                    </span>
                </div>
                <div style="margin-top:12px">
                    @if($book->book_status === 'tersedia')
                        <span class="badge badge-available">Tersedia</span>
                    @else
                        <span class="badge badge-empty">Habis</span>
                    @endif
                </div>

                <div style="display:flex;gap:10px;margin-top:20px">
                    {{-- Hapus buku --}}
                    <form method="POST"
                          action="{{ route('admin.books.destroy', $book->book_id) }}"
                          onsubmit="return confirm('Hapus buku ini secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus Buku</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Form edit (kanan) --}}
    <div class="card">
        <h3 style="font-size:16px;font-weight:600;margin-bottom:20px">Edit Data</h3>
        <form method="POST" action="{{ route('admin.books.update', $book->book_id) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Judul Buku <span style="color:var(--danger)">*</span></label>
                <input type="text" name="book_title"
                       value="{{ old('book_title', $book->book_title) }}"
                       class="{{ $errors->has('book_title') ? 'is-error' : '' }}">
                @error('book_title') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Penulis <span style="color:var(--danger)">*</span></label>
                <input type="text" name="author"
                       value="{{ old('author', $book->author) }}"
                       class="{{ $errors->has('author') ? 'is-error' : '' }}">
                @error('author') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="publisher"
                       value="{{ old('publisher', $book->publisher) }}">
            </div>

            <div class="form-group">
                <label>Stok <span style="color:var(--danger)">*</span></label>
                <input type="number" name="available_stock" min="0"
                       value="{{ old('available_stock', $book->available_stock) }}"
                       class="{{ $errors->has('available_stock') ? 'is-error' : '' }}">
                @error('available_stock') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:8px">
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline btn-sm">Batal</a>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</div>
@endsection
