<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library — Sign Up</title>
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
        .page { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 24px; }
        .auth-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 40px;
            width: 100%; max-width: 440px;
            display: flex; gap: 32px;
        }
        .auth-left { flex: 0 0 140px; }
        .auth-left h1 { font-family: 'DM Serif Display', serif; font-size: 30px; line-height: 1.15; margin-bottom: 10px; }
        .auth-left p  { font-size: 13px; color: var(--muted); line-height: 1.6; }
        .auth-form { flex: 1; display: flex; flex-direction: column; gap: 14px; }
        .field { display: flex; flex-direction: column; gap: 5px; }
        .field label { font-size: 12px; font-weight: 500; color: var(--muted); }
        .field input {
            border: 1px solid var(--border); border-radius: var(--radius);
            padding: 10px 14px; font-family: 'DM Sans', sans-serif; font-size: 14px;
            background: var(--bg); color: var(--text); outline: none; transition: border-color .15s;
        }
        .field input:focus { border-color: var(--accent); background: #fff; }
        .field input.is-error { border-color: var(--danger); }
        .err { font-size: 12px; color: var(--danger); }

        /* step indicator */
        .steps {
            display: flex; gap: 6px; align-items: center; margin-bottom: 8px;
        }
        .step-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--border);
        }
        .step-dot.active { background: var(--accent); }

        /* buttons */
        .btn-row { display: flex; justify-content: flex-end; margin-top: 4px; }
        .btn-next, .btn-submit {
            padding: 10px 24px;
            background: var(--accent); color: var(--accent-fg);
            border: none; border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500;
            cursor: pointer; transition: opacity .15s;
        }
        .btn-next:hover, .btn-submit:hover { opacity: .85; }
        .link-login { font-size: 13px; color: var(--muted); text-decoration: none; }
        .link-login:hover { color: var(--text); text-decoration: underline; }

        /* step panels */
        .step-panel { display: none; }
        .step-panel.active { display: contents; }
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
            <h1>Sign up</h1>
            <p>Buat akun untuk mulai menikmati fitur E-Library lebih lengkap.</p>
        </div>

        {{-- Satu form, validasi di server --}}
        <form action="{{ route('register.post') }}" method="POST" class="auth-form" id="regForm">
            @csrf

            {{-- Step 1: nama & email --}}
            <div class="step-panel active" id="step1">
                <div class="steps">
                    <span class="step-dot active" id="dot1"></span>
                    <span class="step-dot" id="dot2"></span>
                </div>

                <div class="field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="full_name"
                           placeholder="Archie Satria Pamungkas"
                           value="{{ old('full_name') }}"
                           class="{{ $errors->has('full_name') ? 'is-error' : '' }}">
                    @error('full_name') <span class="err">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email"
                           placeholder="archie@student.ub.ac.id"
                           value="{{ old('email') }}"
                           class="{{ $errors->has('email') ? 'is-error' : '' }}">
                    @error('email') <span class="err">{{ $message }}</span> @enderror
                </div>

                <div class="btn-row">
                    <button type="button" class="btn-next" id="btnNext">Berikutnya</button>
                </div>
            </div>

            {{-- Step 2: password --}}
            <div class="step-panel" id="step2">
                <div class="steps">
                    <span class="step-dot" id="dot1b"></span>
                    <span class="step-dot active" id="dot2b"></span>
                </div>

                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password"
                           class="{{ $errors->has('password') ? 'is-error' : '' }}">
                    @error('password') <span class="err">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           class="{{ $errors->has('password_confirmation') ? 'is-error' : '' }}">
                </div>

                <div class="btn-row" style="justify-content: space-between; align-items: center;">
                    <a href="{{ route('login') }}" class="link-login">Sudah punya akun?</a>
                    <button type="submit" class="btn-submit">Buat Akun</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const btnNext = document.getElementById('btnNext');

    // Kalau ada error password (dari server), langsung tampil step 2
    const hasPasswordError = {{ $errors->has('password') || $errors->has('password_confirmation') ? 'true' : 'false' }};
    if (hasPasswordError) {
        step1.classList.remove('active');
        step2.classList.add('active');
    }

    btnNext.addEventListener('click', () => {
        const name  = document.querySelector('[name=full_name]').value.trim();
        const email = document.querySelector('[name=email]').value.trim();
        if (!name || !email) { alert('Nama dan email wajib diisi.'); return; }
        step1.classList.remove('active');
        step2.classList.add('active');
    });
</script>
</body>
</html>
