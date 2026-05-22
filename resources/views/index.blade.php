@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Peminjaman</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter --}}
    <div class="flex gap-2 mb-6">
        <a href="{{ route('history.index') }}"
            class="px-4 py-2 rounded-md text-sm font-medium transition
            {{ !$filter ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Semua
        </a>
        <a href="{{ route('history.index', ['status' => 'borrowed']) }}"
            class="px-4 py-2 rounded-md text-sm font-medium transition
            {{ $filter === 'borrowed' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Sedang Dipinjam
        </a>
        <a href="{{ route('history.index', ['status' => 'returned']) }}"
            class="px-4 py-2 rounded-md text-sm font-medium transition
            {{ $filter === 'returned' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Sudah Dikembalikan
        </a>
    </div>

    {{-- Tabel --}}
    @if ($loans->isEmpty())
        <div class="bg-gray-50 border border-gray-200 text-gray-600 px-6 py-8 rounded-lg text-center">
            <p class="text-lg">Belum ada riwayat peminjaman.</p>
            <a href="{{ route('dashboard') }}" class="mt-3 inline-block text-blue-600 hover:underline">
                Cari buku sekarang →
            </a>
        </div>
    @else
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penulis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($loans as $loan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $loan->book->book_title }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $loan->book->author }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ Carbon::parse($loan->loan_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ Carbon::parse($loan->due_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($loan->transaction_status === 'borrowed')
                                @if ($loan->sisa_hari < 0)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        Terlambat {{ abs($loan->sisa_hari) }} hari
                                    </span>
                                @elseif ($loan->sisa_hari === 0)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                        Jatuh tempo hari ini!
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        Dipinjam · sisa {{ $loan->sisa_hari }} hari
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Dikembalikan
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection