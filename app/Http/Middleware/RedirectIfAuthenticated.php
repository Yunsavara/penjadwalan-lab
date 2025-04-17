<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // Kalau udah login langsung ke dashboard
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $role = Auth::user()->role->nama_peran;

            // Admin dan Laboran diarahkan ke dashboard masing-masing
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else if ($role === 'laboran') {
                return redirect()->route('laboran.dashboard');
            }

            // Prodi, Lembaga, dan User diarahkan ke dashboard umum
            if (in_array($role, ['prodi', 'lembaga', 'user'])) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
