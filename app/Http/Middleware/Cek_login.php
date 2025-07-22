<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Cek_login
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        // Jika belum login, redirect ke login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Jika role sesuai, lanjutkan
        if ($user->role == $roles) {
            return $next($request);
        }

        // Jika tidak sesuai role, redirect sesuai peran
        if ($user->role == 'admin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        if ($user->role == 'security') {
            return redirect('/security');
        }

        // Jika role tidak dikenal, redirect ke login
        return redirect('/login')->with('error', 'Akses ditolak.');
    }
}
