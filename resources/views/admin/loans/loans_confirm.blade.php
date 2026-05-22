@extends('layouts.app')
@section('title', 'Konfirmasi Peminjaman')

@section('content')
<div class="page-header">
    <h1>Konfirmasi Peminjaman</h1>
    <a href="{{ route('cart.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
</div>

<div style="display:grid;grid-template-columns:1fr 420px;gap:24px;align-items:start">

    {{-- Daftar buku yang akan dipinjam --}}
    <div class="card">
        <h3 style="font-size:15px;font-weight:600;margin-bottom:16px;color:var(--muted)">
            BUKU YANG AKAN DIPINJAM
        </h3>
        @forelse($cartItems as $item)
        <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border)">
            <div style="width:40px;height:56px;background:var(--border);border-radius:4px;flex-shrink:0"></div>
            <div>
                <p style="font-size:12px;color:var(--muted)">{{ $item->book->author }}</p>
                <p style="font-size:14px;font-weight:500">{{ $item->book->book_title }}</p>
            </div>
        </div>
        @empty
        <p style="color:var(--muted);text-align:center;padding:24px">Keranjang kosong.</p>
        @endforelse
    </div>

    {{-- Form konfirmasi --}}
    <div class="card">
        <h3 style="font-size:16px;font-weight:600;margin-bottom:4px">Peminjaman</h3>
        <p style="font-size:13px;color:var(--muted);margin-bottom:20px;line-height:1.5">
            Dengan meminjam buku-buku ini saya bersedia untuk mengikuti aturan peminjaman yang berlaku.
        </p>

        <form action="{{ route('loans.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Tanggal Peminjaman</label>
                <input type="text" value="{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}" readonly
                       style="background:var(--bg);cursor:not-allowed">
            </div>

            {{-- FIX: input lama_pinjam (1-3 hari) sesuai LoanController@store --}}
            <div class="form-group">
                <label>Lama Peminjaman (hari) <span style="color:var(--danger)">*</span></label>
                <select name="lama_pinjam" class="{{ $errors->has('lama_pinjam') ? 'is-error' : '' }}">
                    <option value="">-- Pilih --</option>
                    <option value="1" {{ old('lama_pinjam') == 1 ? 'selected' : '' }}>1 Hari</option>
                    <option value="2" {{ old('lama_pinjam') == 2 ? 'selected' : '' }}>2 Hari</option>
                    <option value="3" {{ old('lama_pinjam') == 3 ? 'selected' : '' }}>3 Hari (Maks.)</option>
                </select>
                @error('lama_pinjam')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="flex-direction:row;align-items:center;gap:10px">
                <input type="checkbox" id="setuju" required style="width:16px;height:16px;flex-shrink:0">
                <label for="setuju" style="font-size:13px;color:var(--muted);cursor:pointer">
                    Setuju dengan aturan peminjaman
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                Konfirmasi
            </button>
        </form>
    </div>

</div>
@endsection
