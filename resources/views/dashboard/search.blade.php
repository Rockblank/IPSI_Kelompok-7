@extends('layouts.app')
@section('title', 'Pencarian Buku')

@push('styles')
<style>
    .modal-overlay {
        display: none; position: fixed; inset: 0; z-index: 500;
        background: rgba(0,0,0,.35);
        align-items: center; justify-content: center;
    }
    .modal-overlay.active { display: flex; }

    .modal-box {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 14px; padding: 28px 32px;
        max-width: 400px; width: 90%;
        box-shadow: 0 12px 40px rgba(0,0,0,.18);
        animation: modalIn .18s ease;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(.95) translateY(8px); }
        to   { opacity: 1; transform: scale(1)  translateY(0); }
    }

    .modal-title { font-size: 15px; font-weight: 600; margin-bottom: 8px; }
    .modal-body  { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 22px; }
    .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
    .modal-actions .btn { min-width: 72px; justify-content: center; }

    .btn-notif {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 11px;
        border: 1px solid var(--border); border-radius: var(--radius);
        background: var(--surface); color: var(--text);
        font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 500;
        cursor: pointer; transition: background .15s, border-color .15s;
    }
    .btn-notif:hover { background: #FFF8E1; border-color: #F9A825; }
    .btn-notif svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

    .btn-notif-queued {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 11px;
        border: 1px solid #81C784; border-radius: var(--radius);
        background: var(--badge-available); color: var(--badge-available-text);
        font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 500;
        cursor: default;
    }
    .btn-notif-queued svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
</style>
@endpush

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
                    <div style="width:52px;height:72px;background:var(--border);border-radius:6px;flex-shrink:0"></div>
                    <div>
                        <p style="font-size:12px;color:var(--muted);margin-bottom:2px">
                            oleh: <strong style="color:var(--text)">{{ $buku->author }}</strong>
                        </p>
                        <a href="{{ route('books.show', $buku->book_id) }}" style="text-decoration:none;color:var(--text)">
                            <h3 style="font-size:17px;font-weight:500;margin-bottom:4px;line-height:1.3">{{ $buku->book_title }}</h3>
                        </a>
                        <p style="font-size:12px;color:var(--muted)">Penerbit: {{ $buku->publisher ?? '—' }}</p>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;align-items:flex-end;min-width:120px;gap:8px">
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

                            @php
                                $isQueued = \App\Models\CartQueue::where('user_id', session('user_id'))
                                    ->where('book_id', $buku->book_id)
                                    ->where('type', 'queue')
                                    ->exists();
                            @endphp

                            @if($isQueued)
                                <span class="btn-notif-queued">
                                    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    Notifikasi Aktif
                                </span>
                            @else
                                <button class="btn-notif"
                                        onclick="openNotifModal({{ $buku->book_id }}, '{{ addslashes($buku->book_title) }}')">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                    </svg>
                                    Notifikasi
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    @elseif($keyword)
        <div style="text-align:center;padding:60px 0;color:var(--muted)">
            <div style="font-size:40px;margin-bottom:12px">🔍</div>
            <p style="font-style:italic">"{{ $keyword }}" tidak ditemukan. Coba kata kunci lain.</p>
        </div>
    @else
        <div style="text-align:center;padding:60px 0;color:var(--muted)">
            <p>Masukkan kata kunci di kolom pencarian untuk mencari buku.</p>
        </div>
    @endif
</div>

{{-- Modal Notifikasi --}}
<div class="modal-overlay" id="notifModal">
    <div class="modal-box">
        <p class="modal-title">Buku tidak tersedia</p>
        <p class="modal-body" id="notifModalBody"></p>
        <div class="modal-actions">
            <button class="btn btn-outline btn-sm" onclick="closeNotifModal()">Tidak</button>
            <form id="notifForm" method="POST" action="" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">Ya, beritahu saya</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openNotifModal(bookId, bookTitle) {
        document.getElementById('notifModalBody').textContent =
            'Apakah Anda ingin menerima notifikasi saat "' + bookTitle + '" tersedia kembali?';
        document.getElementById('notifForm').action = '/cart/queue/' + bookId;
        document.getElementById('notifModal').classList.add('active');
    }
    function closeNotifModal() {
        document.getElementById('notifModal').classList.remove('active');
    }
    document.getElementById('notifModal').addEventListener('click', function(e) {
        if (e.target === this) closeNotifModal();
    });
</script>
@endpush
