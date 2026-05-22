<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library — Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #F5F3EE; --surface: #FFFFFF; --border: #E2DDD6;
            --text: #1A1814; --muted: #7A7570;
            --accent: #1A1814; --accent-fg: #F5F3EE;
            --danger: #C0392B; --radius: 10px;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg); color: var(--text);
            min-height: 100vh; display: flex; flex-direction: column;
        }

        /* ── NAV ── */
        nav {
            height: 60px; padding: 0 32px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }
        .nav-brand { font-family: 'DM Serif Display', serif; font-size: 20px; text-decoration: none; color: var(--text); }
        .nav-link {
            padding: 7px 18px; border-radius: 999px;
            background: var(--accent); color: var(--accent-fg);
            text-decoration: none; font-size: 13px; font-weight: 500;
        }

        /* ── LAYOUT ── */
        .page {
            flex: 1; display: flex; align-items: center; justify-content: center;
            padding: 40px 24px;
        }

        /* ── CARD ── */
        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 40px;
            width: 100%; max-width: 440px;
            display: flex; gap: 32px;
        }

        .auth-left { flex: 1; }
        .auth-left h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 32px; line-height: 1.15;
            margin-bottom: 10px;
        }
        .auth-left p { font-size: 13px; color: var(--muted); line-height: 1.6; }

        /* wide card (tidak split) */
        .auth-card.wide { flex-direction: column; gap: 24px; }
        .auth-card.wide .auth-left h1 { font-size: 28px; }

        /* ── FORM ── */
        .auth-form { flex: 1; display: flex; flex-direction: column; gap: 14px; }

        .field { display: flex; flex-direction: column; gap: 5px; }
        .field label { font-size: 12px; font-weight: 500; color: var(--muted); }
        .field input {
            border: 1px solid var(--border); border-radius: var(--radius);
            padding: 10px 14px;
            font-family: 'DM Sans', sans-serif; font-size: 14px;
            background: var(--bg); color: var(--text); outline: none;
            transition: border-color .15s;
        }
        .field input:focus { border-color: var(--accent); background: #fff; }
        .field input.is-error { border-color: var(--danger); }
        .err { font-size: 12px; color: var(--danger); }

        .form-footer {
            display: flex; align-items: center; justify-content: space-between;
            margin-top: 4px;
        }
        .form-footer a { font-size: 13px; color: var(--muted); text-decoration: none; }
        .form-footer a:hover { color: var(--text); text-decoration: underline; }

        .btn-submit {
            padding: 10px 24px;
            background: var(--accent); color: var(--accent-fg);
            border: none; border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500;
            cursor: pointer; transition: opacity .15s;
        }
        .btn-submit:hover { opacity: .85; }

        /* flash */
        .flash-success {
            padding: 10px 14px; border-radius: var(--radius); font-size: 13px;
            background: #E8F5E9; color: #2E7D32; margin-bottom: 4px;
        }
    </style>
</head>
<body>
<nav>
    <a href="{{ route('login') }}" class="nav-brand">E-Library</a>
    <a href="{{ route('login') }}" class="nav-link">Sign in</a>
</nav>

<div class="page">
    <div class="auth-card">
        <div class="auth-left">
            <h1>Sign in</h1>
            <p>Masuk ke akun E-Library untuk mengakses fitur lebih lengkap.</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="auth-form">
            @csrf

            @if(session('success'))
                <div class="flash-success">{{ session('success') }}</div>
            @endif

            <div class="field">
                <label>Email</label>
                <input type="email" name="email"
                       placeholder="archie@gmail.com"
                       value="{{ old('email') }}"
                       class="{{ $errors->has('email') ? 'is-error' : '' }}">
                @error('email') <span class="err">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password"
                       class="{{ $errors->has('password') ? 'is-error' : '' }}">
                @error('password') <span class="err">{{ $message }}</span> @enderror
            </div>

            <div class="form-footer">
                <a href="{{ route('register') }}">Belum punya akun?</a>
                <button type="submit" class="btn-submit">Login</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
