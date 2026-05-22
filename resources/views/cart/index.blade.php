@extends('layouts.app')
@section('title', 'Keranjang Buku')

@section('content')
<div class="page-header">
    <h1>Keranjang Buku</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm">← Kembali</a>
</div>

@php
    $cartItems  = $cartItems ?? collect();
    $queueItems = $queueItems ?? collect();
@endphp

@if($cartItems->isEmpty() && $queueItems->isEmpty())
    <div class="card" style="text-align:center;padding:60px 24px;color:var(--muted)">
        <div style="font-size:40px;margin-bottom:12px">
            {{-- ikon keranjang SVG --}}
            <svg width="48" height="48" viewBox="0 0 24 24" style="stroke:var(--muted);fill:none;stroke-width:1.4;stroke-linecap:round;stroke-linejoin:round">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
        </div>
        <p>Keranjang Anda masih kosong.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary" style="margin-top:16px;display:inline-flex">
            Cari Buku
        </a>
    </div>

@else
    {{-- ── Bagian Keranjang Aktif (siap dipinjam) ── --}}
    @if($cartItems->isNotEmpty())
    <div class="card" style="margin-bottom: 20px">
        <p style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);margin-bottom:16px">
            Siap Dipinjam
        </p>

        @foreach($cartItems as $item)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid var(--border)">
            <div>
                <p style="font-size:12px;color:var(--muted)">oleh: <strong style="color:var(--text)">{{ $item->book->author }}</strong></p>
                <h3 style="font-size:16px;font-weight:500;margin-top:2px">{{ $item->book->book_title }}</h3>
            </div>
            <form action="{{ route('cart.destroy', $item->item_id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit"
                        class="btn btn-sm btn-outline"
                        style="width:32px;height:32px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:50%"
                        title="Hapus dari keranjang">
                    ✕
                </button>
            </form>
        </div>
        @endforeach

        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:20px">
            <span style="font-size:13px;color:var(--muted)">{{ $cartItems->count() }} buku dipilih</span>
            <a href="{{ route('loans.confirm') }}" class="btn btn-primary">Pinjam →</a>
        </div>
    </div>
    @endif

    {{-- ── Bagian Antrian Notifikasi (buku belum tersedia) ── --}}
    @if($queueItems->isNotEmpty())
    <div class="card">
        <p style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);margin-bottom:16px">
            Menunggu Ketersediaan
        </p>

        @foreach($queueItems as $item)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid var(--border)">
            <div>
                <p style="font-size:12px;color:var(--muted)">oleh: <strong style="color:var(--text)">{{ $item->book->author }}</strong></p>
                <h3 style="font-size:16px;font-weight:500;margin-top:2px">{{ $item->book->book_title }}</h3>
                <p style="font-size:12px;margin-top:4px">
                    @if($item->book->available_stock > 0)
                        {{-- Buku sudah tersedia kembali — tampilkan tombol pinjam --}}
                        <span class="badge badge-available" style="margin-right:6px">Tersedia</span>
                        <a href="{{ route('loans.confirm') }}" class="btn btn-primary btn-sm" style="display:inline-flex;margin-top:4px">
                            Pinjam Sekarang →
                        </a>
                    @else
                        <span class="badge badge-borrowed">Menunggu stok...</span>
                    @endif
                </p>
            </div>
            <form action="{{ route('cart.destroy', $item->item_id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit"
                        class="btn btn-sm btn-outline"
                        style="width:32px;height:32px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:50%"
                        title="Batalkan antrian notifikasi">
                    ✕
                </button>
            </form>
        </div>
        @endforeach

        <p style="font-size:12px;color:var(--muted);margin-top:16px">
            Anda akan menerima notifikasi otomatis saat buku di atas tersedia kembali.
        </p>
    </div>
    @endif

@endif
@endsection
