<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #F5F3EE; --surface: #FFFFFF; --border: #E2DDD6;
            --text: #1A1814; --muted: #7A7570;
            --accent: #1A1814; --accent-fg: #F5F3EE;
            --danger: #C0392B; --success: #27AE60;
            --badge-available: #E8F5E9; --badge-available-text: #2E7D32;
            --badge-empty: #FDECEA;     --badge-empty-text: #C62828;
            --radius: 10px; --nav-h: 60px;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        nav {
            position: sticky; top: 0; z-index: 100;
            height: var(--nav-h); background: var(--bg);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; padding: 0 32px; gap: 16px;
        }
        .nav-brand { font-family: 'DM Serif Display', serif; font-size: 20px; color: var(--text); text-decoration: none; margin-right: auto; }
        .nav-search { display: flex; align-items: center; background: var(--surface); border: 1px solid var(--border); border-radius: 999px; overflow: hidden; height: 36px; }
        .nav-search input { border: none; outline: none; padding: 0 16px; font-family: 'DM Sans', sans-serif; font-size: 13px; width: 220px; background: transparent; color: var(--text); }
        .nav-search button { height: 36px; padding: 0 18px; background: var(--accent); color: var(--accent-fg); border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500; border-radius: 0 999px 999px 0; transition: opacity .15s; }
        .nav-search button:hover { opacity: .85; }

        .nav-icon {
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; text-decoration: none; color: var(--text);
            transition: background .15s;
            border: none; background: transparent; cursor: pointer;
        }
        .nav-icon:hover { background: var(--border); }
        .nav-icon svg { width: 20px; height: 20px; stroke: var(--text); fill: none; stroke-width: 1.6; stroke-linecap: round; stroke-linejoin: round; }

        .nav-pill { display: flex; align-items: center; gap: 8px; background: var(--accent); color: var(--accent-fg); padding: 6px 14px; border-radius: 999px; font-size: 13px; font-weight: 500; text-decoration: none; }

        main { max-width: 1100px; margin: 0 auto; padding: 32px 24px; }

        .flash { padding: 12px 18px; border-radius: var(--radius); margin-bottom: 20px; font-size: 14px; }
        .flash.success { background: var(--badge-available); color: var(--badge-available-text); }
        .flash.error   { background: var(--badge-empty);     color: var(--badge-empty-text); }

        .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; }

        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 20px; border-radius: var(--radius); font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500; cursor: pointer; border: none; text-decoration: none; transition: opacity .15s, transform .1s; }
        .btn:hover { opacity: .88; transform: translateY(-1px); }
        .btn-primary { background: var(--accent); color: var(--accent-fg); }
        .btn-outline { background: transparent; color: var(--text); border: 1px solid var(--border); }
        .btn-danger  { background: var(--danger); color: #fff; }
        .btn-sm      { padding: 6px 14px; font-size: 13px; }

        .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: 500; }
        .badge-available { background: var(--badge-available); color: var(--badge-available-text); }
        .badge-empty     { background: var(--badge-empty);     color: var(--badge-empty-text); }
        .badge-borrowed  { background: #FFF8E1; color: #F57F17; }
        .badge-returned  { background: var(--badge-available); color: var(--badge-available-text); }
        .badge-overdue   { background: var(--badge-empty);     color: var(--badge-empty-text); }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 10px 14px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); border-bottom: 1px solid var(--border); }
        td { padding: 14px; border-bottom: 1px solid var(--border); font-size: 14px; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #FAF8F5; }

        .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 18px; }
        .form-group label { font-size: 13px; font-weight: 500; color: var(--muted); }
        .form-group input, .form-group select, .form-group textarea { border: 1px solid var(--border); border-radius: var(--radius); padding: 10px 14px; font-family: 'DM Sans', sans-serif; font-size: 14px; background: var(--surface); color: var(--text); outline: none; transition: border-color .15s; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--accent); }
        .form-group .error-msg { font-size: 12px; color: var(--danger); }
        .is-error { border-color: var(--danger) !important; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .page-header h1 { font-family: 'DM Serif Display', serif; font-size: 28px; }

        .pagination { display: flex; gap: 6px; justify-content: flex-end; margin-top: 20px; }
        .pagination a, .pagination span { padding: 6px 12px; border-radius: 6px; font-size: 13px; border: 1px solid var(--border); text-decoration: none; color: var(--text); }
        .pagination .active { background: var(--accent); color: var(--accent-fg); border-color: var(--accent); }
    </style>
    @stack('styles')
</head>
<body>

<nav>
    <a href="{{ route('dashboard') }}" class="nav-brand">E-Library</a>

    @if(session('user_id'))
        {{-- Search bar hanya tampil di halaman selain dashboard --}}
        @if(!request()->routeIs('dashboard'))
        <form action="{{ route('dashboard.search') }}" method="GET" class="nav-search">
            <input type="text" name="keyword"
                   placeholder="Cari judul buku..."
                   value="{{ request('keyword') }}">
            <button type="submit">Cari</button>
        </form>
        @else
            <span style="margin-right:auto"></span>
        @endif

        {{-- Ikon Dashboard --}}
        <a href="{{ route('dashboard') }}" class="nav-icon" title="Dashboard">
            <svg viewBox="0 0 24 24"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/><path d="M9 21V12h6v9"/></svg>
        </a>

        {{-- Ikon Keranjang --}}
        <a href="{{ route('cart.index') }}" class="nav-icon" title="Keranjang">
            <svg viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </a>

        {{-- Ikon Notifikasi --}}
        <a href="{{ route('notifications.index') }}" class="nav-icon" title="Notifikasi">
            <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </a>

        <a href="{{ route('history.index') }}" class="nav-pill">{{ session('user_name') }}</a>

        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button class="btn btn-outline btn-sm" type="submit">Keluar</button>
        </form>
    @else
        <div style="margin-left:auto;display:flex;gap:12px;align-items:center">
            <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
        </div>
    @endif
</nav>

<main>
    @if(session('success'))
        <div class="flash success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash error">{{ session('error') }}</div>
    @endif
    @if($errors->has('auth'))
        <div class="flash error">{{ $errors->first('auth') }}</div>
    @endif

    @yield('content')
</main>

@stack('scripts')
</body>
</html>
