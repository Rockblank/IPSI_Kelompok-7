@extends('layouts.admin')

@section('title', 'Kelola Peminjaman Buku')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📋 Daftar Peminjaman Buku (Admin)</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Pinjam</th>
                            <th>Nama Member</th>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Tanggal Kembali</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($loans->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada data transaksi peminjaman.</td>
                            </tr>
                        @else
                            @foreach($loans as $loan)
                                <tr>
                                    <td><strong>#{{ $loan->loan_id }}</strong></td>
                                    <td>{{ $loan->user->name ?? 'User Tidak Ditemukan' }}</td>
                                    <td>{{ $loan->book->title ?? 'Buku Tidak Ditemukan' }}</td>
                                    <td>{{ $loan->loan_date }}</td>
                                    <td>{{ $loan->due_date }}</td>
                                    <td>{{ $loan->return_date ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($loan->transaction_status === 'borrowed')
                                            <span class="badge bg-warning text-dark text-uppercase">Dipinjam</span>
                                        @else
                                            <span class="badge bg-success text-uppercase">Kembali</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($loan->transaction_status === 'borrowed')
                                            <form action="{{ route('admin.loans.return', $loan->loan_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin buku ini sudah dikembalikan?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                     Mark as Returned
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Selesai</button>
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