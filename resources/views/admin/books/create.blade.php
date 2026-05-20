@extends('layouts.admin')
@section('title', 'Tambah Buku')

@section('content')
<div class="page-header">
    <h1>Tambah Data Buku</h1>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline">← Kembali</a>
</div>

<div class="card" style="max-width:700px">
    <form method="POST" action="{{ route('admin.books.store') }}">
        @csrf

        <div class="form-group">
            <label>Judul Buku <span style="color:var(--danger)">*</span></label>
            <input type="text" name="book_title"
                   placeholder="Masukkan judul buku"
                   value="{{ old('book_title') }}"
                   class="{{ $errors->has('book_title') ? 'is-error' : '' }}">
            @error('book_title') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Penulis Buku <span style="color:var(--danger)">*</span></label>
                <input type="text" name="author"
                       placeholder="Nama penulis"
                       value="{{ old('author') }}"
                       class="{{ $errors->has('author') ? 'is-error' : '' }}">
                @error('author') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="publisher"
                       placeholder="Nama penerbit"
                       value="{{ old('publisher') }}">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Stok <span style="color:var(--danger)">*</span></label>
                <input type="number" name="available_stock" min="0"
                       placeholder="0"
                       value="{{ old('available_stock', 0) }}"
                       class="{{ $errors->has('available_stock') ? 'is-error' : '' }}">
                @error('available_stock') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:8px">
            <a href="{{ route('admin.books.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Tambah Buku</button>
        </div>
    </form>
</div>
@endsection
