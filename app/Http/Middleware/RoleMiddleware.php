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

     public function handle(Request $request, Closure $next, $role)
     {
        // Kalau belum login diarahin ke halaman login
        if(!Auth::user()){
            return redirect()->route('login');
        }

        // Jika user adalah admin, beri akses ke semua halaman
        if (Auth::user()->role->name === 'admin') {
            return $next($request);
        }

        // Kalau Tidak Sesuai Role di balikin ke halaman sebelumnya
        if(Auth::user()->role->name !== $role){
            return redirect()->back();
        }

        return $next($request);
     }

}


//     // Jika user adalah admin, beri akses ke semua halaman
    //     if ($user->role->name === 'admin') {
    //         return $next($request);
    //     }

    //     // Jika role user tidak sesuai dengan middleware, tolak akses dengan redirect
    //     if ($user->role->name !== $role) {
    //         return redirect()->back();
    //     }
