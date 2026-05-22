<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library — Keranjang Buku</title>
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
                        'lib-wire-gray': '#D9D9D9'
                    },
                    fontFamily: { sans: ['DM Sans', 'sans-serif'], serif: ['DM Serif Display', 'serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-lib-bg text-lib-text min-h-screen p-8">

    <header class="flex justify-between items-center w-full max-w-4xl mx-auto pb-6 border-b border-lib-border mb-8">
        <a href="/dashboard" class="font-serif text-2xl tracking-tight text-lib-text">E-Library</a>
        
        <div class="flex items-center space-x-6">
            <a href="/dashboard" class="text-lib-muted hover:text-lib-text">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            </a>
            <div class="flex items-center bg-lib-surface border border-lib-border px-4 py-1.5 rounded-full text-xs font-medium">
                <span class="w-2.5 h-2.5 rounded-full bg-lib-muted mr-2"></span>
                {{ session('user_name', 'User') }}
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto bg-lib-surface border border-lib-border p-8 rounded-2xl shadow-sm">
        <h1 class="text-3xl font-serif mb-6">Keranjang Buku</h1>
        <hr class="border-lib-border mb-6">

        @if($cartItems->isEmpty())
            <p class="text-lib-muted italic text-center py-10">Keranjang masih kosong.</p>
        @else
            @foreach($cartItems as $item)
                <div class="flex items-center justify-between bg-lib-wire-gray p-4 rounded-xl mb-4">
                    <div>
                        <p class="text-xs text-lib-muted">oleh: <span class="font-medium">{{ $item->book->author }}</span></p>
                        <h3 class="text-lg font-medium">{{ $item->book->book_title }}</h3>
                    </div>
                    
                    <form action="{{ route('cart.destroy', $item->item_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-lib-muted text-lib-bg rounded-full hover:bg-red-600 transition">
                            -
                        </button>
                    </form>
                </div>
            @endforeach

            <div class="flex justify-end mt-8">
                <form action="{{ route('loans.store') }}" method="POST">
                    @csrf
                    @foreach($cartItems as $item)
                        <input type="hidden" name="cart_item_ids[]" value="{{ $item->item_id }}">
                    @endforeach
                    
                    <button type="submit" class="bg-lib-text text-lib-bg px-8 py-2 rounded-xl font-medium hover:opacity-90 transition">
                        Pinjam
                    </button>
                </form>
            </div>
        @endif
        
        <div class="flex justify-end items-center space-x-3 mt-6 text-xs text-lib-muted">
            <span>1/1</span>
            <div class="flex space-x-1">
                <button class="p-1 border rounded">&lt;</button>
                <button class="p-1 border rounded">&gt;</button>
            </div>
        </div>
    </main>

</body>
</html>