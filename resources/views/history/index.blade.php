@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📜 Riwayat Peminjaman Buku Anda</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID Pinjam</th>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Batas Jatuh Tempo</th>
                            <th>Tanggal Dikembalikan</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($historyLoans->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Anda belum pernah melakukan peminjaman buku.</td>
                            </tr>
                        @else
                            @foreach($historyLoans as $loan)
                                <tr>
                                    <td>#{{ $loan->loan_id }}</td>
                                    <td><strong>{{ $loan->book->title ?? 'Buku Tidak Ditemukan' }}</strong></td>
                                    <td>{{ $loan->loan_date }}</td>
                                    <td>{{ $loan->due_date }}</td>
                                    <td>{{ $loan->return_date ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($loan->transaction_status === 'borrowed')
                                            <span class="badge bg-warning text-dark">SEDANG DIPINJAM</span>
                                        @else
                                            <span class="badge bg-success">SUDAH KEMBALI</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection