<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library — Dashboard</title>
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
                        'lib-wire-gray': '#D9D9D9' /* Abu-abu solid sesuai wireframe */
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

    <header class="flex justify-between items-center w-full max-w-6xl mx-auto pb-4">
        <div>
            <a href="/dashboard" class="font-serif text-2xl tracking-tight no-underline text-lib-text">E-Library</a>
        </div>

        <div class="flex items-center space-x-4">
            <a href="/dashboard" class="text-lib-muted hover:text-lib-text transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </a>
            
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
            
            <div class="flex items-center space-x-2 bg-lib-wire-gray border border-transparent px-4 py-1.5 rounded-full text-white text-xs font-medium shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-lib-muted inline-block"></span>
                <span class="capitalize tracking-wide text-lib-text font-semibold">{{ $namaUser }}</span>
            </div>
        </div>
    </header>

    <main class="flex flex-col items-start justify-center flex-grow max-w-xl mx-auto w-full my-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center">
                {{ session('error') }}
            </div>
        @endif
        <div class="w-full mb-4">
            <h1 class="text-3xl font-serif leading-tight">Halo, <span class="font-bold">{{ $namaUser }}</span></h1>
            <p class="text-2xl font-serif text-lib-text mt-0.5">Mau baca apa hari ini?</p>
        </div>

        <form action="/dashboard/search" method="GET" class="flex items-center w-full bg-lib-wire-gray p-1 rounded-2xl shadow-md border border-lib-border">
            <div class="relative flex-grow">
                <input type="text" name="keyword" placeholder='"Laut Bercerita"' required
                    class="w-full bg-transparent text-lib-text placeholder-lib-muted italic px-5 py-3 focus:outline-none text-md">
            </div>
            <button type="submit" class="bg-lib-muted hover:bg-lib-text text-lib-bg font-medium px-8 py-3 rounded-xl transition shadow-lg text-md">
                Cari
            </button>
        </form>

    </main>

    <footer class="w-full text-center text-[11px] text-lib-muted tracking-wide mt-8">
        E-Library Kelompok 7 &copy; 2026
    </footer>
</body>
</html>