@extends('layouts.app')
@section('title', 'Pencarian Buku')

@section('content')
<div class="card">
    @if($keyword)
        <p style="font-size:13px;color:var(--muted);margin-bottom:16px">
            Hasil pencarian untuk: <strong style="color:var(--text)">"{{ $keyword }}"</strong>
            — {{ $daftarBuku->count() }} buku ditemukan
        </p>
    @endif

    @if($daftarBuku && $daftarBuku->count() > 0)
        <div>
            @foreach($daftarBuku as $buku)
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;padding:16px 0;border-bottom:1px solid var(--border)">
                <div style="display:flex;align-items:flex-start;gap:14px">
                    {{-- Cover placeholder --}}
                    <div style="width:52px;height:72px;background:var(--border);border-radius:6px;flex-shrink:0"></div>
                    <div>
                        <p style="font-size:12px;color:var(--muted);margin-bottom:2px">
                            oleh: <strong style="color:var(--text)">{{ $buku->author }}</strong>
                        </p>
                        <a href="{{ route('books.show', $buku->book_id) }}"
                           style="text-decoration:none;color:var(--text)">
                            <h3 style="font-size:17px;font-weight:500;margin-bottom:4px;line-height:1.3">
                                {{ $buku->book_title }}
                            </h3>
                        </a>
                        <p style="font-size:12px;color:var(--muted)">
                            Penerbit: {{ $buku->publisher ?? '—' }}
                        </p>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;align-items:flex-end;justify-content:space-between;min-width:110px;gap:8px">
                    <div style="text-align:right">
                        <span style="font-size:10px;color:var(--muted);display:block">Jumlah:</span>
                        <span style="font-size:13px;font-weight:600">{{ $buku->available_stock }}</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        @if($buku->available_stock > 0)
                            <span class="badge badge-available">Tersedia</span>
                            <form action="{{ route('cart.add', $buku->book_id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        style="width:28px;height:28px;border-radius:50%;background:var(--accent);color:var(--accent-fg);border:none;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;font-weight:300"
                                        title="Tambah ke keranjang">+</button>
                            </form>
                        @else
                            <span class="badge badge-empty">Habis</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    @elseif($keyword)
        <div style="text-align:center;padding:60px 0;color:var(--muted)">
            <div style="font-size:40px;margin-bottom:12px">🔍</div>
            <p style="font-style:italic">"{{ $keyword }}" tidak dapat ditemukan. Coba kata kunci lain.</p>
        </div>

    @else
        <div style="text-align:center;padding:60px 0;color:var(--muted)">
            <p>Masukkan kata kunci di kolom pencarian untuk mencari buku.</p>
        </div>
    @endif
</div>
@endsection
