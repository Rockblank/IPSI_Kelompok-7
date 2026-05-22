@extends('layouts.app')
@section('title', 'Keranjang Buku')

@section('content')
<div class="page-header">
    <h1>Keranjang Buku</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm">← Kembali</a>
</div>

@if($cartItems->isEmpty())
    <div class="card" style="text-align:center;padding:60px 24px;color:var(--muted)">
        <div style="font-size:40px;margin-bottom:12px">🛒</div>
        <p>Keranjang Anda masih kosong.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary" style="margin-top:16px;display:inline-flex">
            Cari Buku
        </a>
    </div>
@else
    <div class="card">
        {{-- Daftar buku --}}
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

        {{-- Footer keranjang --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:20px">
            <span style="font-size:13px;color:var(--muted)">{{ $cartItems->count() }} buku dipilih</span>
            {{-- FIX: arahkan ke loans.confirm dulu, bukan langsung loans.store
                 LoanController@store butuh input lama_pinjam dari form confirm --}}
            <a href="{{ route('loans.confirm') }}" class="btn btn-primary">Pinjam →</a>
        </div>
    </div>
@endif
@endsection
