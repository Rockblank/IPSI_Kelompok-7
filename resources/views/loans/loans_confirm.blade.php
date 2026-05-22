@extends('layouts.app')

@section('title', 'Konfirmasi Peminjaman')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">🛒 Konfirmasi Form Peminjaman</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Harap periksa kembali detail buku sebelum melakukan konfirmasi peminjaman.</p>
                    
                    <div class="mb-3 border-bottom pb-2">
                        <label class="text-secondary small d-block">Judul Buku</label>
                        <span class="fw-bold fs-5">{{ $book->title }}</span>
                    </div>

                    <form action="{{ route('loans.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->book_id }}">

                        <div class="mb-3">
                            <label for="loan_date" class="form-label fw-bold">Tanggal Peminjaman</label>
                            <input type="text" class="form-control bg-light" id="loan_date" name="loan_date" value="{{ \Carbon\Carbon::now()->toDateString() }}" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="due_date" class="form-label fw-bold">Pilih Tanggal Jatuh Tempo Pengembalian</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success py-2 fw-bold">Konfirmasi Pinjam Buku</button>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection