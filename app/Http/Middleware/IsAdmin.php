<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek 1: Apakah dia sudah login?
        if (!Auth::check()) {
            return redirect('/login'); // Kalau belum, suruh login
        }

        // Cek 2: Apakah role-nya ADMIN?
        if (Auth::user()->role !== 'admin') {
            abort(403, 'ANDA BUKAN ADMIN! DILARANG MASUK.'); // Tendang user biasa
        }

        // Kalau lolos, silakan lanjut
        return $next($request);
    }
}
