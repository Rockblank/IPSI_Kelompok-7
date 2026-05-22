@extends('layouts.admin')
@section('title', 'Kelola Peminjaman')

@section('content')
<div class="page-header">
    <h1>Kelola Peminjaman</h1>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Member</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th style="text-align:center">Status</th>
                <th style="text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
            <tr>
                <td style="color:var(--muted)">{{ $loan->loan_id }}</td>
                {{-- FIX: pakai full_name bukan name --}}
                <td>{{ $loan->user->full_name ?? '—' }}</td>
                {{-- FIX: pakai book_title bukan title --}}
                <td>{{ $loan->book->book_title ?? '—' }}</td>
                <td>{{ $loan->loan_date }}</td>
                <td>{{ $loan->due_date }}</td>
                <td>{{ $loan->return_date ?? '—' }}</td>
                <td style="text-align:center">
                    @if($loan->transaction_status === 'borrowed')
                        <span class="badge badge-borrowed">Dipinjam</span>
                    @else
                        <span class="badge badge-returned">Kembali</span>
                    @endif
                </td>
                <td style="text-align:center">
                    @if($loan->transaction_status === 'borrowed')
                        <form action="{{ route('admin.loans.return', $loan->loan_id) }}" method="POST"
                              onsubmit="return confirm('Tandai buku ini sudah dikembalikan?')">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Tandai Kembali</button>
                        </form>
                    @else
                        <span style="font-size:13px;color:var(--muted)">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:40px;color:var(--muted)">
                    Belum ada data peminjaman.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

