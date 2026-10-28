<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu sebagai Administrator.');
        }

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('jadwal.semua')->with('error', 'Akses ditolak! Anda masuk sebagai ' . ucfirst(Auth::user()->role) . '. Mahasiswa hanya memiliki akses untuk melihat jadwal perkuliahan.');
        }

        return $next($request);
    }
}
