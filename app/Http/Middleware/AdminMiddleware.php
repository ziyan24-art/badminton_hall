<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika sedang akses admin login page, biarkan lewat meskipun belum login
        if ($request->is('admin/login') || $request->is('admin/login/*')) {
            return $next($request);
        }

        // Jika belum login atau bukan admin
        if (!Auth::check() || Auth::user()->is_admin != 1) {
            return redirect()->route('admin.login');
        }

        // Lolos sebagai admin
        return $next($request);
    }
}
