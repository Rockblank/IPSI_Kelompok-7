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
            return $role === 'admin'
                ? redirect()->route('admin.books.index')
                : redirect()->route('dashboard');
        }
        return $next($request);
    }
}
