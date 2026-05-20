<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // ── LOGIN ──────────────────────────────────────────────
    public function showLogin()
    {
        if (Session::has('user_id')) {
            return $this->redirectByRole(Session::get('role'));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        Session::put('user_id',   $user->user_id);
        Session::put('user_name', $user->full_name);
        Session::put('user_email',$user->email);
        Session::put('role',      $user->role);

        return $this->redirectByRole($user->role);
    }

    // ── REGISTER ───────────────────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name'             => 'required|string|max:100',
            'email'                 => 'required|email|max:100|unique:users,email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'user',
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil, silakan login.');
    }

    // ── LOGOUT ─────────────────────────────────────────────
    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }

    // ── HELPER ─────────────────────────────────────────────
    private function redirectByRole(string $role)
    {
        return $role === 'admin'
            ? redirect()->route('admin.books.index')
            : redirect()->route('dashboard');
    }
}
