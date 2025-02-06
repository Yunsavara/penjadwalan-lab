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
        $user = auth()->user();

        // Jika tidak login, tolak akses
        if (!$user) {
            return abort(403, 'Unauthorized');
        }

        // Jika user adalah superadmin, beri akses ke semua halaman
        if ($user->role->name === 'superadmin') {
            return $next($request);
        }

        // Jika role user tidak sesuai dengan middleware, tolak akses
        if ($user->role->name !== $role) {
            return abort(403, 'Unauthorized');
        }

        return $next($request);
    }

}
