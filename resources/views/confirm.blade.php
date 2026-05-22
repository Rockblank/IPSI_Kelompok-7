@extends('layouts.app')

@section('title', 'Konfirmasi Peminjaman')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Konfirmasi Peminjaman</h1>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($cartItems->isEmpty())
        <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 px-4 py-3 rounded mb-4">
            Keranjang Anda masih kosong.
            <a href="{{ route('dashboard') }}" class="underline font-medium">Cari buku</a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b font-semibold text-gray-700">
                Buku yang akan dipinjam ({{ $cartItems->count() }} buku)
            </div>
            <ul class="divide-y divide-gray-100">
                @foreach ($cartItems as $item)
                <li class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-800">{{ $item->book->book_title }}</p>
                        <p class="text-sm text-gray-500">{{ $item->book->author }}</p>
                    </div>
                    <span class="text-sm text-green-600 font-medium">
                        Stok: {{ $item->book->available_stock }}
                    </span>
                </li>
                @endforeach
            </ul>
        </div>

        <form action="{{ route('loans.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            <div class="mb-4">
                <label for="lama_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                    Lama Peminjaman
                </label>
                <select name="lama_pinjam" id="lama_pinjam"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1">1 Hari</option>
                    <option value="2">2 Hari</option>
                    <option value="3">3 Hari</option>
                </select>
                @error('lama_pinjam')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium transition">
                    Konfirmasi Pinjam
                </button>
                <a href="{{ route('cart.index') }}"
                    class="flex-1 text-center bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 font-medium transition">
                    Kembali ke Keranjang
                </a>
            </div>
        </form>
    @endif
</div>
@endsection