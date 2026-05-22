<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('user_id')) {
            $role = Session::get('role');
            
            // Jika admin, arahkan ke rute admin. Jika user biasa, arahkan langsung ke URL /dashboard
            return $role === 'admin'
                ? redirect()->route('admin.books.index')
                : redirect('/dashboard'); // <-- DIUBAH DI SINI (Pakai URL langsung, bukan route name)
        }
        return $next($request);
    }
}