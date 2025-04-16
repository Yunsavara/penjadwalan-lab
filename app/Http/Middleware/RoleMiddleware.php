<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

     public function handle(Request $request, Closure $next, ...$roles)
     {
         // Kalau belum login, redirect ke login
         if (!Auth::check()) {
             return redirect()->route('login');
         }

        //  Auth::logout();

         $userRole = Auth::user()->role->nama_peran;

         // Cek apakah role user ada dalam daftar yang diizinkan
         if (!in_array($userRole, $roles)) {
             return redirect()->back();
         }

         return $next($request);
     }


}


        // Jika user adalah admin, beri akses ke semua halaman
        // if (Auth::user()->role->name === 'admin') {
        //     return $next($request);
        // }
