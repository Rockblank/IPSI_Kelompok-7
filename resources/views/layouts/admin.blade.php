<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #F5F3EE;
            --surface:   #FFFFFF;
            --border:    #E2DDD6;
            --text:      #1A1814;
            --muted:     #7A7570;
            --accent:    #1A1814;
            --accent-fg: #F5F3EE;
            --danger:    #C0392B;
            --success:   #27AE60;
            --badge-available: #E8F5E9;
            --badge-available-text: #2E7D32;
            --badge-empty: #FDECEA;
            --badge-empty-text: #C62828;
            --radius:    10px;
            --nav-h:     60px;
        }

        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        nav {
            position: sticky; top: 0; z-index: 100;
            height: var(--nav-h);
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 32px; gap: 16px;
        }
        .nav-brand { font-family: 'DM Serif Display', serif; font-size: 20px; color: var(--text); text-decoration: none; }
        .nav-admin-badge {
            font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .08em;
            color: var(--muted); margin-right: auto;
            border: 1px solid var(--border); padding: 2px 8px; border-radius: 4px;
        }

        .nav-search {
            display: flex; align-items: center;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 999px; overflow: hidden; height: 36px;
        }
        .nav-search input {
            border: none; outline: none; padding: 0 16px;
            font-family: 'DM Sans', sans-serif; font-size: 13px; width: 200px;
            background: transparent; color: var(--text);
        }
        .nav-search button {
            height: 36px; padding: 0 18px;
            background: var(--accent); color: var(--accent-fg);
            border: none; cursor: pointer;
            font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;
            border-radius: 0 999px 999px 0;
        }
        .nav-pill {
            display: flex; align-items: center; gap: 8px;
            background: var(--accent); color: var(--accent-fg);
            padding: 6px 14px; border-radius: 999px;
            font-size: 13px; font-weight: 500; text-decoration: none;
        }

        main { max-width: 1100px; margin: 0 auto; padding: 32px 24px; }

        .flash { padding: 12px 18px; border-radius: var(--radius); margin-bottom: 20px; font-size: 14px; }
        .flash.success { background: var(--badge-available); color: var(--badge-available-text); }
        .flash.error   { background: var(--badge-empty);     color: var(--badge-empty-text); }

        .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 20px; border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500;
            cursor: pointer; border: none; text-decoration: none;
            transition: opacity .15s, transform .1s;
        }
        .btn:hover { opacity: .88; transform: translateY(-1px); }
        .btn-primary { background: var(--accent); color: var(--accent-fg); }
        .btn-outline { background: transparent; color: var(--text); border: 1px solid var(--border); }
        .btn-active { background: var(--text); color: #fff; border: 1px solid var(--text); }
        .btn-danger  { background: var(--danger); color: #fff; }
        .btn-sm      { padding: 6px 14px; font-size: 13px; }

        .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: 500; }
        .badge-available { background: var(--badge-available); color: var(--badge-available-text); }
        .badge-empty     { background: var(--badge-empty);     color: var(--badge-empty-text); }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 10px 14px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); border-bottom: 1px solid var(--border); }
        td { padding: 14px; border-bottom: 1px solid var(--border); font-size: 14px; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #FAF8F5; }

        .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 18px; }
        .form-group label { font-size: 13px; font-weight: 500; color: var(--muted); }
        .form-group input, .form-group select, .form-group textarea {
            border: 1px solid var(--border); border-radius: var(--radius);
            padding: 10px 14px; font-family: 'DM Sans', sans-serif; font-size: 14px;
            background: var(--surface); color: var(--text); outline: none; transition: border-color .15s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--accent); }
        .form-group .error-msg { font-size: 12px; color: var(--danger); }
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
    <a href="{{ route('admin.books.index') }}" class="nav-brand">E-Library</a>
    <span class="nav-admin-badge">Admin</span>

    <form action="{{ route('admin.books.index') }}" method="GET" class="nav-search">
        <input type="text" name="search" placeholder="Cari judul buku..." value="{{ request('search') }}">
        <button type="submit">Cari</button>
    </form>

    <a href="{{ route('admin.books.index') }}" class="btn btn-outline btn-sm {{ request()->routeIs('admin.books.*') ? 'btn-active' : '' }}">Buku</a>
    <a href="{{ route('admin.loans.index') }}" class="btn btn-outline btn-sm {{ request()->routeIs('admin.loans.*') ? 'btn-active' : '' }}">📚 Peminjaman</a>
    <span class="nav-pill">{{ session('user_name', 'Admin') }}</span>

    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button class="btn btn-outline btn-sm" type="submit">Keluar</button>
    </form>
</nav>

<main>
    @if(session('success'))
        <div class="flash success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash error">{{ session('error') }}</div>
    @endif

    @yield('content')
</main>

@stack('scripts')
</body>
</html>
