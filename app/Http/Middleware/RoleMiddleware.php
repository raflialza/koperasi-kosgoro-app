<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Memeriksa apakah role pengguna tidak termasuk dalam role yang diizinkan.
        if (! in_array(Auth::user()->role, $roles)) {
            // Jika tidak sesuai, alihkan ke halaman 'home' dengan pesan error.
            return redirect()->route('home')
                ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }

        return $next($request);
    }
}
