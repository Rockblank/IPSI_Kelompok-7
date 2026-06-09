<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_id')) {
            if ($request->isMethod('get')) {
                Session::put('url.intended', $request->fullUrl());
            } else {
                $routeName = optional($request->route())->getName();
                $bookId = $request->route('book');

                if ($routeName === 'cart.add' && $bookId) {
                    Session::put('login_pending_action', [
                        'type' => 'add_to_cart',
                        'book_id' => $bookId,
                    ]);
                } elseif ($routeName === 'cart.queue' && $bookId) {
                    Session::put('login_pending_action', [
                        'type' => 'add_to_queue',
                        'book_id' => $bookId,
                    ]);
                } else {
                    Session::put('url.intended', url()->previous() ?: route('dashboard'));
                }
            }

            return redirect()->route('login')
                ->withErrors(['auth' => 'Silakan login terlebih dahulu.']);
        }

        return $next($request);
    }
}
