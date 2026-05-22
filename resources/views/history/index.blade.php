@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="page-header">
    <h1>Riwayat Peminjaman</h1>
    {{-- Filter status --}}
    <div style="display:flex;gap:8px">
        <a href="{{ route('history.index') }}"
           class="btn btn-sm {{ !$filter ? 'btn-primary' : 'btn-outline' }}">Semua</a>
        <a href="{{ route('history.index', ['status' => 'borrowed']) }}"
           class="btn btn-sm {{ $filter === 'borrowed' ? 'btn-primary' : 'btn-outline' }}">Masa Pinjam</a>
        <a href="{{ route('history.index', ['status' => 'returned']) }}"
           class="btn btn-sm {{ $filter === 'returned' ? 'btn-primary' : 'btn-outline' }}">Dikembalikan</a>
    </div>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th style="text-align:center">Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- FIX: ganti $historyLoans → $loans (sesuai HistoryController) --}}
            @forelse($loans as $loan)
            <tr>
                <td style="font-weight:500">{{ $loan->book->book_title ?? '—' }}</td>
                <td style="color:var(--muted)">{{ $loan->book->author ?? '—' }}</td>
                <td>{{ $loan->loan_date }}</td>
                <td>
                    {{ $loan->due_date }}
                    @if($loan->transaction_status === 'borrowed' && $loan->sisa_hari !== null)
                        @if($loan->sisa_hari < 0)
                            <div style="font-size:11px;color:var(--danger);font-weight:500">
                                Terlambat {{ abs($loan->sisa_hari) }} hari
                            </div>
                        @elseif($loan->sisa_hari === 0)
                            <div style="font-size:11px;color:var(--danger);font-weight:500">Hari ini!</div>
                        @else
                            <div style="font-size:11px;color:var(--muted)">{{ $loan->sisa_hari }} hari lagi</div>
                        @endif
                    @endif
                </td>
                <td>{{ $loan->return_date ?? '—' }}</td>
                <td style="text-align:center">
                    @if($loan->transaction_status === 'borrowed')
                        @if(isset($loan->sisa_hari) && $loan->sisa_hari < 0)
                            <span class="badge badge-overdue">Terlambat</span>
                        @else
                            <span class="badge badge-borrowed">Masa Pinjam</span>
                        @endif
                    @else
                        <span class="badge badge-returned">Dikembalikan</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">
                    Belum ada riwayat peminjaman.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
