<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse; // Ditambahkan

class PreventBackHistory
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // --- PERBAIKAN DI SINI ---
        // Hanya tambahkan header jika response BUKAN unduhan file
        if (!($response instanceof BinaryFileResponse)) {
            return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                            ->header('Pragma', 'no-cache')
                            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
        }

        // Jika ini adalah unduhan file, kembalikan respons apa adanya
        return $response;
    }
}
