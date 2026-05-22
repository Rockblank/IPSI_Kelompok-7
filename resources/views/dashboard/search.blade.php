<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library — Pencarian Buku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        'lib-bg': '#F5F3EE', 
                        'lib-surface': '#FFFFFF', 
                        'lib-border': '#E2DDD6', 
                        'lib-text': '#1A1814', 
                        'lib-muted': '#7A7570',
                        'lib-gray-wire': '#D9D9D9'
                    },
                    fontFamily: { 
                        sans: ['DM Sans', 'sans-serif'], 
                        serif: ['DM Serif Display', 'serif'] 
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-lib-bg text-lib-text min-h-screen flex flex-col justify-between p-8">

    <header class="flex justify-between items-center w-full max-w-6xl mx-auto border-b border-lib-border pb-4 mb-6">
        <div class="flex items-center space-x-8 flex-grow">
            <a href="/dashboard" class="font-serif text-2xl tracking-tight flex-shrink-0 text-lib-text no-underline">E-Library</a>
            
            <a href="/dashboard" class="text-lib-muted hover:text-lib-text transition flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </a>

            <form action="/dashboard/search" method="GET" class="flex items-center space-x-2 flex-grow max-w-xl">
                <div class="relative flex-grow">
                    <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="Cari judul buku atau penulis..." 
                        class="w-full bg-lib-surface border border-lib-border text-lib-text px-4 py-2 rounded-xl focus:outline-none focus:border-lib-text text-sm shadow-sm">
                </div>
                <button type="submit" class="bg-lib-text hover:bg-opacity-80 text-lib-bg px-5 py-2 rounded-xl text-sm font-medium transition shadow-sm">
                    Cari
                </button>
            </form>
        </div>

        <div class="flex items-center space-x-4 flex-shrink-0">
            <a href="/cart" class="text-lib-muted hover:text-lib-text transition" title="Lihat Keranjang">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </a>
            
            <a href="#" class="text-lib-muted hover:text-lib-text transition" title="Notifikasi">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </a>

            <div class="flex items-center space-x-2 bg-lib-surface border border-lib-border px-4 py-1.5 rounded-full text-xs font-medium shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-lib-muted inline-block"></span>
                <span class="capitalize tracking-wide text-lib-text font-semibold">{{ $namaUser }}</span>
            </div>
        </div>
    </header>

    <div class="flex flex-col md:flex-row gap-6 w-full max-w-6xl mx-auto flex-grow items-stretch">
        
        <main class="w-full flex flex-col justify-between bg-lib-surface border border-lib-border p-6 rounded-2xl shadow-sm">
            
            <div class="w-full">
                @if($daftarBuku && $daftarBuku->count() > 0)
                    <div class="divide-y divide-lib-border">
                        @foreach($daftarBuku as $buku)
                            <div class="flex items-start justify-between py-4 first:pt-0 last:pb-0 gap-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-16 h-20 bg-lib-gray-wire rounded-md flex-shrink-0"></div>
                                    <div>
                                        <p class="text-xs text-lib-muted">oleh: <span class="font-medium text-lib-text">{{ $buku->author }}</span></p>
                                        
                                        <a href="/books/{{ $buku->book_id }}" class="hover:underline text-lib-text hover:text-opacity-80">
                                            <h3 class="text-xl font-medium tracking-tight mt-0.5 mb-1">{{ $buku->book_title }}</h3>
                                        </a>
                                        
                                        <p class="text-xs text-lib-muted line-clamp-2 max-w-xl">Penerbit: {{ $buku->publisher }}. Deskripsi singkat koleksi buku e-library kelompok 7.</p>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end justify-between h-20 flex-shrink-0 min-w-[120px]">
                                    <div class="text-right">
                                        <span class="text-[10px] text-lib-muted block">Jumlah:</span>
                                        <span class="text-xs font-bold">{{ $buku->available_stock }}</span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        @if($buku->available_stock > 0)
                                            <span class="text-center bg-lib-bg border border-lib-border text-lib-text text-[11px] px-3 py-1 rounded-full font-medium">
                                                Tersedia
                                            </span>
                                            
                                            <form action="{{ route('cart.add', ['book' => $buku->book_id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-lib-text hover:bg-opacity-80 text-lib-bg text-sm font-bold w-7 h-7 rounded-full flex items-center justify-center transition shadow-sm" title="Tambah ke keranjang">
                                                    +
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-center bg-gray-100 text-gray-400 border border-gray-200 text-[11px] px-3 py-1 rounded-full font-medium">
                                                Habis
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else
                    <div class="flex flex-col items-center justify-center py-20">
                        <div class="w-20 h-20 bg-lib-bg border border-lib-border rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-lib-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l-2-2m0 0l2-2m-2 2h12" />
                            </svg>
                        </div>
                        <p class="text-sm text-lib-muted text-center italic font-medium">
                            "Buku yang Anda cari tidak terdaftar"
                        </p>
                    </div>
                @endif
            </div>

            <div class="flex justify-end items-center space-x-3 mt-6 pt-4 border-t border-lib-border text-xs text-lib-muted">
                <span>1/1</span>
                <div class="flex space-x-1">
                    <button class="p-1 border border-lib-border rounded hover:bg-lib-bg transition">&lt;</button>
                    <button class="p-1 border border-lib-border rounded hover:bg-lib-bg transition">&gt;</button>
                </div>
            </div>
        </main>
    </div>

    <footer class="w-full text-center text-[11px] text-lib-muted tracking-wide mt-8">
        E-Library Kelompok 7 &copy; 2026
    </footer>
</body>
</html>