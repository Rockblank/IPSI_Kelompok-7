<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }

        if (Session::get('role') !== $role) {
            // User coba akses admin → lempar ke dashboard
            // Admin coba akses user page → lempar ke admin
            $fallback = Session::get('role') === 'admin'
                ? route('admin.books.index')
                : route('dashboard');

            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
