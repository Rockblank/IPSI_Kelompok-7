@extends('layouts.app')
@section('title', $buku->book_title)

@section('content')
<div style="margin-bottom:16px">
    <a href="{{ url()->previous() }}" class="btn btn-outline btn-sm">← Kembali</a>
</div>

<div style="display:grid;grid-template-columns:200px 1fr;gap:32px;align-items:start">

    {{-- Cover placeholder --}}
    <div style="width:200px;height:280px;background:var(--border);border-radius:10px"></div>

    {{-- Detail buku --}}
    <div>
        <h1 style="font-family:'DM Serif Display',serif;font-size:28px;margin-bottom:8px">
            {{ $buku->book_title }}
        </h1>

        <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:20px;font-size:14px">
            <span>Penulis: <strong>{{ $buku->author }}</strong></span>
            <span>Penerbit: <strong>{{ $buku->publisher ?? '—' }}</strong></span>
        </div>

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
            @if($buku->available_stock > 0)
                <span class="badge badge-available">Tersedia</span>
                <span style="font-size:13px;color:var(--muted)">Stok: {{ $buku->available_stock }}</span>
            @else
                <span class="badge badge-empty">Habis</span>
            @endif
        </div>

        <div style="display:flex;gap:12px">
            @if($buku->available_stock > 0)
                <form action="{{ route('cart.add', $buku->book_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline">Tambah ke Keranjang</button>
                </form>
            @else
                <button class="btn btn-outline" disabled style="opacity:.4;cursor:not-allowed">
                    Stok Habis
                </button>
            @endif
            <a href="{{ route('cart.index') }}" class="btn btn-primary">Lihat Keranjang</a>
        </div>

        @if(session('success'))
            <div class="flash success" style="margin-top:16px">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash error" style="margin-top:16px">{{ session('error') }}</div>
        @endif
    </div>
</div>
@endsection
